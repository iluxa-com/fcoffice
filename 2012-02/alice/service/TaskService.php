<?php
/**
 * 任务服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class TaskService extends Service {
    /**
     * 互斥锁互斥时间(秒)
     * @var float
     */
    private $_lockTime = 0.8;
    /**
     * 互斥锁模型实例
     * @var MutexModel
     */
    private $_lockModel;

    /**
     * 拉取NPC任务(当没有NPC任务时,会call此服务)
     * @param int $zoneId 区域ID
     * @param int $placeId 地区ID
     */
    public function fetchTask($zoneId = NULL, $placeId = NULL) {
        $this->_data = array('task_id' => User::fetchNPCTask());
        $this->_ret = 0;
    }

    /**
     * 接受任务(只能是NPC任务)
     * @param int $taskId 任务ID
     */
    public function acceptTask($taskId = NULL) {
        if (!is_numeric($taskId)) { // 无效的任务ID,直接返回
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $taskDataModel = new TaskDataModel($taskId);
        $taskDataArr = $taskDataModel->hGetAll();
        if (empty($taskDataArr)) { // 任务数据不存在,直接返回
            $this->_data['msg'] = 'task data not exists';
            return;
        }
        switch ($taskDataArr['type']) { // 新的任务系统里面,只有NPC任务才需要手动接受,其他都自动推送
            case '2': // NPC任务
                break;
            case '0': // 主线任务
            case '1': // 系统任务
            default: // 未知类型
                $this->_data['msg'] = 'unknown or unsupported task type';
                return;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel();
        if ($unfinishedTaskModel->sAdd($taskId) === false) { // 添加到未完成任务集合中
            $this->_data['msg'] = 'unknown error';
            return;
        }
        // 更新每日任务进度
        User::updateDayTask(13);
        $this->_data = array('status' => 0, 'task_id' => $taskId);
        $this->_ret = 0;
    }

    /**
     * 取消任务(只能是NPC任务)
     * @param int $taskId 任务ID
     */
    public function cancelTask($taskId = NULL) {
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $taskDataModel = new TaskDataModel($taskId);
        $dataArr = $taskDataModel->hGetAll();
        if ($dataArr === false || empty($dataArr)) { // 任务数据没取到
            $this->_data['msg'] = 'cannot get task data';
            return;
        }
        if ($dataArr['type'] != 2) { // 不是NPC任务(目前只允许NPC任务取消)
            $this->_data['msg'] = 'not a npc task';
            return;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel();
        if (!$unfinishedTaskModel->sIsMember($taskId)) { // 不存在未完成任务集合中
            $this->_data['msg'] = 'task not accept';
            return;
        }
        $cancelTask = $this->_userModel->hGet('cancel_task', false);
        if ($cancelTask !== false && $cancelTask <= 0) { // 达到取消任务最大次数限制
            $this->_data['msg'] = 'reach max times';
            return;
        }
        if (!$unfinishedTaskModel->sRemove($taskId)) { // 从未完成任务集合中删除失败
            $this->_data['msg'] = 'sRemove fail';
            return;
        }
        $this->_userModel->hIncrBy('cancel_task', -1); // 剩余次数-1
        $this->_ret = 0;
    }

    /**
     * 完成任务(非好友任务)
     * @param int $taskId 任务ID
     */
    public function finishTask($taskId = NULL) {
        if (!is_numeric($taskId)) { // 无效的任务ID
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $taskDataModel = new TaskDataModel($taskId);
        $taskDataArr = $taskDataModel->hGetAll();
        if (empty($taskDataArr)) { // 任务数据不存在,直接返回
            $this->_data['msg'] = 'task data not exists';
            return;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel();
        if ($unfinishedTaskModel->sIsMember($taskId) === false) { // 不存在未完成任务集合中
            $this->_data['msg'] = 'not accept task';
            return;
        }
        switch ($taskDataArr['type']) {
            case '0': // 主线任务
                break;
            case '1': // 系统任务
                break;
            case '2': // NPC任务
                if ($unfinishedTaskModel->sRemove($taskId) === false) { // 从未完成任务集合中删除失败
                    if ($taskDataArr['type'] == 0) { // 回滚
                        $finishedTaskModel->sRemove($taskId);
                    }
                    $this->_data['msg'] = 'sRemove task id error';
                    return;
                }
                $taskModel = new TaskModel();
                $taskModel->hDel('info');
                break;
            default: // 未知类型
                $this->_data['msg'] = 'unknown task type';
                return;
        }
        if (!empty($taskDataArr['need'])) { // 任务需求不为空
            $needArr = json_decode($taskDataArr['need'], true);
            if ($needArr === NULL) { // 解码失败
                $this->_data['msg'] = 'json decode need data error';
                return;
            }
            foreach ($needArr as $key => $val) { // 检测操作(只检测,不扣除)
                if ($key === 'silver') { // 金币
                    $silver = $this->_userModel->hGet('silver');
                    if ($val > $silver) { // 金币不足,直接返回
                        $this->_data['msg'] = 'not enough silver';
                        return;
                    }
                } else if ($key === 'items') { // 道具
                    foreach ($val as $item) {
                        $curNum = Bag::getItemNum($item['id']);
                        if ($curNum === false || $curNum < $item['num']) { // 道具数量不足,直接返回
                            $this->_data['msg'] = 'not enough item';
                            return;
                        }
                    }
                }
            }
            foreach ($needArr as $key => $val) { // 扣除操作
                if ($key === 'silver') { // 金币
                    Bag::decrItem(Common::ITEM_SILVER, $val);
                } else if ($key === 'items') { // 道具
                    foreach ($val as $item) {
                        Bag::decrItem($item['id'], $item['num']);
                    }
                }
            }
        }

        if (!empty($taskDataArr['reward'])) { // 任务将励不为空
            $rewardArr = json_decode($taskDataArr['reward'], true);
            if ($rewardArr === NULL) { // 解码失败
                $this->_data['msg'] = 'json decode reward data error';
                return;
            }
            foreach ($rewardArr as $key => $val) { // 遍历奖励
                if ($key === 'silver') {
                    Bag::incrItem(Common::ITEM_SILVER, $val);
                } else if ($key === 'items') {
                    foreach ($val as $item) {
                        Bag::incrItem($item['id'], $item['num']);
                    }
                } else if ($key === 'dress') {
                    $gender = $this->_userModel->hGet('gender');
                    $sex = ($gender == 0) ? 'girl' : 'boy';
                    $item = $val[$sex];
                    Bag::incrItem($item['id'], $item['num']);
                }
            }
        }
        $newTaskId = NULL;
        switch ($taskDataArr['type']) {
            case '0':
                break;
            case '1':
                break;
            case '2':
                $newTaskId = User::fetchNPCTask();
                break;
        }
        // 写动作日志
        $dataArr = array(
            'val1' => $taskId,
            'val2' => 0, // 暂时没用
            'val3' => 0, // 暂时没用
            'val4' => '', // 暂时没用
        );
        User::actionLog(User::ACTION_TYPE_FINISH_TASK, $dataArr); // 写动作日志(完成任务)
        // 更新每日任务进度
        User::updateDayTask(14);
        $this->_data = array('status' => 1, 'task_id' => $taskId, 'new_task_id' => $newTaskId);
        $this->_ret = 0;
    }

    /**
     * 给选定的好友发布任务(发布到日志)
     * @param int $taskId 任务ID
     * @param array $friendUserIdArr 好友用户ID数组
     */
    public function pubFriendTask($taskId = NULL, $friendUserIdArr = NULL) {
        if (!is_numeric($taskId)) { // 无效的任务ID
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        if (!is_array($friendUserIdArr) || empty($friendUserIdArr)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        // 去掉重复的元素
        $friendUserIdArr = array_unique($friendUserIdArr);
        if (in_array($this->_userId, $friendUserIdArr)) { // 不能包含自己
            $this->_data['msg'] = 'cannot include self';
            return;
        }
        $taskModel = new TaskModel();
        $oldVal = $taskModel->hGet('info', false); // 当前NPC任务发布信息
        if ($oldVal === false || $oldVal === '') {
            $dataArr = array(
                'task_id' => $taskId,
                'friends' => array(),
            );
        } else {
            $dataArr = json_decode($oldVal, true); // 解码旧数据
            if ($dataArr === NULL || !isset($dataArr['task_id']) || $dataArr['task_id'] != $taskId) { // 任务ID不匹配,数据丢掉
                $dataArr = array(
                    'task_id' => $taskId,
                    'friends' => array(),
                );
            } else { // 旧数据有效
                if (count($dataArr['friends']) + count($friendUserIdArr) > TaskModel::MAX_INVITE_TIMES) { // 判断邀请数
                    $this->_data['msg'] = 'too much friend user id2';
                    return;
                }
            }
        }
        $num = 0;
        $tempArr = array();
        $inviteModel = new InviteModel();
        foreach ($friendUserIdArr as $friendUserId) {
            if (!is_numeric($friendUserId)) { // 跳过无效的
                continue;
            }
            if (in_array($friendUserId, $dataArr['friends'])) { // 跳过已存在的
                continue;
            }
            if ($inviteModel->sAdd($friendUserId) === false) { // 跳过已经邀请过的
                continue;
            }
            $logDataArr = array(
                'from_user_id' => $this->_userId, // 发布人用户ID
                'task_id' => $taskId,
            );
            User::log(User::LOG_TYPE_FRIEND_TASK, $logDataArr, $friendUserId); // 写好友日志(邀请好友完成任务)
            $tempArr[] = $friendUserId;
            ++$num;
            
        }
        if ($num > 0) {
            // 更新发布好友帮助任务次数
            $this->_userModel->hIncrBy('pub_task_times', $num);
        }
        if (!empty($tempArr)) {
            $dataArr['friends'] = array_unique(array_merge($dataArr['friends'], $tempArr));
            $taskModel->hSet('info', json_encode($dataArr));
        }
        $this->_ret = 0;
    }

    /**
     * 接受好友任务
     * @param int $friendUserId 好友的用户ID
     * @param int $taskId 任务ID
     */
    public function acceptFriendTask($friendUserId = NULL, $taskId = NULL) {
        if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $times = $this->_userModel->hGet('help_times', 0);
        if ($times <= 0) {
            $this->_data['msg'] = 'no more times';
            return;
        }
        $defaultFriendInfoArr = App::get('DefaultFriend');
        $defaultUserId = $defaultFriendInfoArr['user_id'];
        $fromDefaultUser = false;
        if ($defaultUserId == $friendUserId) { // 默认好友
            $count = 1;
            $fromDefaultUser = true;
        } else {
            $friendTaskSQLModel = new FriendTaskSQLModel();
            $whereArr = array(
                'from_user_id' => $friendUserId,
                'user_id' => $this->_userId,
                'task_id' => $taskId,
            );
            $count = $friendTaskSQLModel->SH()->find($whereArr)->count();
        }
        if ($count < 1) { // 没找到
            $this->_data['msg'] = 'friend task not exists';
            return;
        }
        $taskDataModel = new TaskDataModel($taskId);
        $taskDataArr = $taskDataModel->hGetAll();
        if (empty($taskDataArr)) { // 任务数据获取失败
            $this->_data['msg'] = 'task data not exists';
            return;
        }
        if ($taskDataArr['type'] != 2) { // 不是NPC任务
            $this->_data['msg'] = 'not a npc task';
            return;
        }
        if (empty($taskDataArr['need'])) { // 任务需求数据为空
            $this->_data['msg'] = 'task no need data';
            return;
        }
        $needArr = json_decode($taskDataArr['need'], true);
        if ($needArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode need data error';
            return;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel($friendUserId);
        if ($unfinishedTaskModel->sIsMember($taskId)) { // 如果好友的NPC任务存在
            $collectItemId = $needArr['items'][0]['id']; // 收集道具ID,取第一个
            Bag::incrItem($collectItemId, TaskModel::NEED_COLLECT_NUM, $friendUserId); // 收集的道具,添加到好友背包里面
        }
        $rewardArr = array(
            array('id' => Common::ITEM_SILVER, 'num' => 20),
            array('id' => Common::ITEM_EXP, 'num' => 20),
        );
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        // 日志数据
        $contentArr = array(
            'from_user_id' => $this->_userId,
            'task_id' => $taskId,
        );
        $logDataArr = array(
            'log_type' => 2, // 好友帮我完成了任务
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $logDataArr, $friendUserId); // 写好友日志(帮好友完成任务)
        // 更新每日任务进度
        User::updateDayTask(3);
        // 写动作日志
        $dataArr = array(
            'val1' => $taskId,
            'val2' => 0, // 暂时没用
            'val3' => 0, // 暂时没用
            'val4' => '', // 暂时没用
        );
        User::actionLog(User::ACTION_TYPE_FINISH_TASK, $dataArr); // 写动作日志(完成任务)
        !$fromDefaultUser && $friendTaskSQLModel->SH()->find($whereArr)->delete(); // 删除日志
        $this->_userModel->hIncrBy('help_times', -1);
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 拒绝好友任务
     * @param int $friendUserId 好友的用户ID
     * @param int $taskId 任务ID
     */
    public function refuseFriendTask($friendUserId = NULL, $taskId = NULL) {
        if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'bad friend user id';
            return;
        }
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'bad task id';
            return;
        }
        $friendTaskSQLModel = new FriendTaskSQLModel();
        $whereArr = array(
            'from_user_id' => $friendUserId,
            'user_id' => $this->_userId,
            'task_id' => $taskId
        );
        $count = $friendTaskSQLModel->SH()->find($whereArr)->count();
        if ($count > 0) {
            $friendTaskSQLModel->SH()->find($whereArr)->delete();
            $this->__removeFromFriendTask($friendUserId, $taskId);
        }
        $this->_ret = 0;
    }

    /**
     * 完成好友任务
     * @param string $hashKey 好友任务关联的hashKey
     */
    public function finishFriendTask($hashKey = NULL) {
        if (strpos($hashKey, ':') === false) { // 无效的hashKey
            $this->_data['msg'] = 'invalid hash key';
            return;
        }
        $friendTaskModel = new FriendTaskModel();
        $jsonStr = $friendTaskModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 未获取到
            $this->_data['msg'] = 'friend task not exists';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode error';
            return;
        }
        if ($jsonArr['num'] < TaskModel::NEED_COLLECT_NUM) { // 收集物品数量不够
            $this->_data['msg'] = 'collect item not enough';
            return;
        }
        $taskId = $jsonArr['task_id'];
        $taskDataModel = new TaskDataModel($taskId);
        $taskDataArr = $taskDataModel->hGetAll();
        if (empty($taskDataArr)) { // 任务数据获取失败
            $this->_data['msg'] = 'task data not exists';
            return;
        }
        if ($taskDataArr['type'] != 2) { // 不是NPC任务
            $this->_data['msg'] = 'not a npc task';
            return;
        }
        if (empty($taskDataArr['need'])) { // 任务需求数据为空
            $this->_data['msg'] = 'task no need data';
            return;
        }
        $needArr = json_decode($taskDataArr['need'], true);
        if ($needArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode need data error';
            return;
        }
        if ($friendTaskModel->hDel($hashKey) === false) { // 从好友任务列表当中删除失败
            $this->_data['msg'] = 'del fail';
            return;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel($jsonArr['user_id']);
        if ($unfinishedTaskModel->sIsMember($taskId)) { // 如果好友的NPC任务存在
            $collectItemId = $needArr['items'][0]['id']; // 收集道具ID,取第一个
            Bag::incrItem($collectItemId, TaskModel::NEED_COLLECT_NUM, $jsonArr['user_id']); // 收集的道具,添加到好友背包里面
        }
        $rewardArr = array(
            array('id' => Common::ITEM_SILVER, 'num' => 10),
            array('id' => Common::ITEM_EXP, 'num' => 100),
        );
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $taskModel = new TaskModel();
        $taskModel->hSet('active', '');
        // 日志数据
        $contentArr = array(
            'from_user_id' => $this->_userId,
            'task_id' => $taskId,
        );
        $logDataArr = array(
            'log_type' => 2, // 好友帮我完成了任务
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $logDataArr, $jsonArr['user_id']); // 写好友日志(帮好友完成任务)
        // 更新每日任务进度
        User::updateDayTask(3);
        // 写动作日志
        $dataArr = array(
            'val1' => $taskId,
            'val2' => 0, // 暂时没用
            'val3' => 0, // 暂时没用
            'val4' => '', // 暂时没用
        );
        User::actionLog(User::ACTION_TYPE_FINISH_TASK, $dataArr); // 写动作日志(完成任务)
        $this->_ret = 0;
    }

    /**
     * 设置活动的好友任务
     * @param string $hashKey 好友任务关联的hashKey
     */
    public function activeFriendTask($hashKey = NULL) {
        if (strpos($hashKey, ':') === false) {
            $this->_data['msg'] = 'invalid hash key';
            return;
        }
        $friendTaskModel = new FriendTaskModel();
        if ($friendTaskModel->hExists($hashKey) === false) { // 不存在这样的$hashKey
            $this->_data ['msg'] = 'hash key not exists';
            return;
        }
        $taskModel = new TaskModel();
        if ($taskModel->hSet('active', $hashKey) === false) { // 设置失败
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 暂停好友任务
     */
    public function pauseFriendTask() {
        $taskModel = new TaskModel();
        if ($taskModel->hSet('active', '') === false) { // 设置失败
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 移除好友任务
     * @param string $hashKey 好友任务关联的hashKey
     */
    public function removeFriendTask($hashKey = NULL) {
        if (strpos($hashKey, ':') === false) {
            $this->_data['msg'] = 'invalid hash key';
            return;
        }
        $friendTaskModel = new FriendTaskModel();
        $jsonStr = $friendTaskModel->hGet($hashKey, false);
        if ($jsonStr !== false) {
            $jsonArr = json_decode($jsonStr, true);
            if ($jsonArr !== NULL) {
                list($friendUserId, $taskId) = explode(':', $hashKey);
                $this->__removeFromFriendTask($friendUserId, $taskId);
                if ($friendTaskModel->hDel($hashKey) === false) { // 删除失败
                    $this->_data['msg'] = 'hDel fail or hash key not exists';
                    return;
                }
            }
        }
        $this->_ret = 0;
    }

    /**
     * 解锁发布任务槽
     * @param int $slotId 任务槽ID
     * @param int $type 类型(0=达到等级手动解锁,1=使用FH币解锁)
     */
    public function unlockSlotItem($slotId = NULL, $type = NULL) {
        if (!in_array($type, array(0, 1))) {
            $this->_data['msg'] = 'invalid type';
        }
        // 任务槽配置
        $configArr = array(
            1 => array('grade' => 4, 'gold' => 5),
            2 => array('grade' => 10, 'gold' => 10),
            3 => array('grade' => 17, 'gold' => 20),
            4 => array('grade' => 22, 'gold' => 30),
            5 => array('grade' => 26, 'gold' => 40),
            6 => array('grade' => 9999, 'gold' => 60),
            7 => array('grade' => 9999, 'gold' => 80),
            8 => array('grade' => 9999, 'gold' => 100),
        );
        if (!isset($configArr[$slotId])) { // 不存在
            $this->_data['msg'] = 'invalid slot id';
            return;
        }
        $taskSlotModel = new TaskSlotModel();
        if ($taskSlotModel->hExists($slotId)) { // 已经解锁
            $this->_data['msg'] = 'already unlock';
            return;
        }
        if ($type == 0) {
            $curGrade = Common::getGradeByExp($this->_userModel->hGet('exp'));
            if ($curGrade < $configArr[$slotId]['grade']) { // 等级不够
                $this->_data['msg'] = 'grade too lower';
                return;
            }
        } else {
            $needNum = $configArr[$slotId]['gold'];
            $curNum = Bag::getItemNum(Common::ITEM_GOLD);
            if ($curNum < $needNum) { // FH币不够
                $this->_data['msg'] = 'not enough gold';
                return;
            }
            if (Bag::decrItem(Common::ITEM_GOLD, $needNum) === false) { // 扣除失败
                $this->_data['msg'] = 'decr item fail';
                return;
            }
        }
        if ($taskSlotModel->hSetNx($slotId, '{}') === false) {
            if ($type == 1) {
                Bag::incrItem(Common::ITEM_GOLD, $needNum); // 失败回滚
            }
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 发布任务到任务槽
     * @param int $slotId 任务槽ID
     */
    public function pubSlotTask($slotId = NULL) {
        if (!is_numeric($slotId)) {
            $this->_data['msg'] = 'invalid slot id';
            return;
        }
        $taskSlotModel = new TaskSlotModel();
        $jsonStr = $taskSlotModel->hGet($slotId, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'slot unlock';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'data decode error';
            return;
        }
        $lastPubTime = isset($jsonArr['time']) ? $jsonArr['time'] : 0;
        if (CURRENT_TIME - $lastPubTime < 3600) { // 时间不同步
            $this->_data['server_time'] = time();
            $this->_ret = UserException::ERROR_NEED_SYNC_TIME;
            return;
        }
        // 随机抽取一个任务
        $taskId = User::fetchSlotTask();
        if ($taskId === NULL) {
            $this->_data['msg'] = 'cannot get slot task';
            return;
        }
        $taskDataModel = new TaskDataModel($taskId);
        $needStr = $taskDataModel->hGet('need', false);
        $needGrade = $taskDataModel->hGet('grade', 0);
        if ($needStr === false) {
            $this->_data['msg'] = 'cannot get task need data';
            return;
        }
        $needArr = json_decode($needStr, true);
        if ($needArr === NULL) {
            $this->_data['msg'] = 'json decode error';
            return;
        }
        $grade = Common::getGradeByExp($this->_userModel->hGet('exp'));
        $itemId = $needArr['items'][0]['id'];
        $dataArr = array(
            'task_id' => $taskId,
            'time' => CURRENT_TIME,
            'user_id' => 0,
            'need' => array('items' => array(array('id' => $itemId, 'num' => Common::randSlotTaskItemNum()))),
            'reward' => array('items' => Common::randSlotTaskReward($grade)),
            'grade' => $needGrade,
        );
        if ($taskSlotModel->hSet($slotId, json_encode($dataArr)) === false) {
            $this->_data['msg'] = 'pub task fail';
            return;
        }
        // 更新每日任务进度
        User::updateDayTask(8);
        $this->_data = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 接受任务槽中的任务
     * @param int $friendUserId 好友用户ID
     * @param int $slotId 任务槽ID
     */
    public function acceptSlotTask($friendUserId = NULL, $slotId = NULL) {
        if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if (!is_numeric($slotId)) {
            $this->_data['msg'] = 'invalid slot id';
            return;
        }
        if ($friendUserId == $this->_userId) { // 不能是自己
            $this->_data['msg'] = 'cannot be self';
            return;
        }
        $defaultFriendInfoArr = App::get('DefaultFriend');
        $defaultUserId = $defaultFriendInfoArr['user_id'];
        if ($defaultUserId == $friendUserId) { // 默认好友
            $isDefaultUser = true;
        } else {
            $isDefaultUser = false;
        }
        $taskModel = new TaskModel();
        $jsonStr = $taskModel->hGet('temp', false);
        if ($jsonStr !== false) {
            $jsonArr = json_decode($jsonStr, true);
            if (!isset($jsonArr['cancel']) || (CURRENT_TIME - $jsonArr['cancel']) < 3600) { // 任务没完成或取消时间还未到
                $this->_data['msg'] = 'slot task already exists';
                return;
            }
        }
        if (!$isDefaultUser && $this->__addLock($friendUserId, $slotId) === false) { // 加锁失败
            $this->_data['msg'] = 'add mutex fail';
            $this->_ret = UserException::ERROR_SLOT_TASK;
            return;
        }
        $taskSlotModel = new TaskSlotModel($friendUserId);
        $jsonStr = $taskSlotModel->hGet($slotId, false);
        if ($jsonStr === false) { // 没取到
            $this->__removeLock();
            $this->_data['msg'] = 'slot not exists';
            $this->_ret = UserException::ERROR_SLOT_TASK;
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->__removeLock();
            $this->_data['msg'] = 'data decode error';
            return;
        }
        if (empty($jsonArr)) { // 任务没有发布
            $this->__removeLock();
            $this->_data['msg'] = 'task not pub';
            return;
        }
        if (!$isDefaultUser) { // 不是默认好友
            $curGrade = Common::getGradeByExp($this->_userModel->hGet('exp'));
            if (isset($jsonArr['grade']) && $curGrade < $jsonArr['grade']) { // 等级不够
                $this->__removeLock();
                $this->_data['msg'] = 'grade too lower';
                return;
            }
            if ($jsonArr['user_id'] != 0) { // 已经被其他人先接了
                $this->__removeLock();
                $this->_data['msg'] = 'task already accepted by other friend';
                $this->_ret = UserException::ERROR_SLOT_TASK;
                return;
            }
            $jsonArr['user_id'] = $this->_userId;
            if ($taskSlotModel->hSet($slotId, json_encode($jsonArr)) === false) {
                $this->__removeLock();
                $this->_data['msg'] = 'update data fail';
                return;
            }
        }
        $dataArr = array(
            'from_user_id' => $friendUserId,
            'slot_id' => $slotId,
            'task_id' => $jsonArr['task_id'],
            'need' => $jsonArr['need'],
            'reward' => $jsonArr['reward'],
        );
        if ($taskModel->hSet('temp', json_encode($dataArr)) === false) { // 更新失败
            $this->__removeLock();
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        // 更新每日任务进度
        User::updateDayTask(11);
        $this->__removeLock();
        $this->_ret = 0;
    }

    /**
     * 取消任务槽任务
     */
    public function cancelSlotTask() {
        $taskModel = new TaskModel();
        $jsonStr = $taskModel->hGet('temp', false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'task not exists';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'data decode error';
            return;
        }
        if (isset($jsonArr['cancel'])) { // 已经取消过了
            $this->_data['msg'] = 'already canceled';
            return;
        }
        $jsonArr['cancel'] = CURRENT_TIME;
        if ($taskModel->hSet('temp', json_encode($jsonArr)) === false) { // 更新失败
            $this->_data['msg'] = 'cancel fail';
            return;
        }
        $this->_data['cancel'] = CURRENT_TIME;
        $this->_ret = 0;
    }

    /**
     * 完成任务槽任务
     */
    public function finishSlotTask() {
        $taskModel = new TaskModel();
        $jsonStr = $taskModel->hGet('temp', false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'task not exists';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'data decode error';
            return;
        }
        if (isset($jsonArr['cancel'])) { // 已经取消过了
            $this->_data['msg'] = 'already canceled';
            return;
        }
        $friendUserId = $jsonArr['from_user_id'];
        $slotId = $jsonArr['slot_id'];
        $taskId = $jsonArr['task_id'];
        $needArr = $jsonArr['need']['items'];
        $rewardArr = $jsonArr['reward']['items'];
        if ($taskModel->hDel('temp') == false) { // 删除失败
            $this->_data['msg'] = 'del task fail';
            return;
        }
        foreach ($needArr as $item) { // 检测操作(只检测,不扣除)
            $curNum = Bag::getItemNum($item['id']);
            if ($curNum === false || $curNum < $item['num']) { // 道具数量不足,直接返回
                $taskModel->hSetNx('temp', $jsonStr); // 回滚
                $this->_data['msg'] = 'not enough item';
                return;
            }
        }
        foreach ($needArr as $item) { // 扣除操作
            Bag::decrItem($item['id'], $item['num']);
        }
        foreach ($rewardArr as $item) { // 遍历奖励
            Bag::incrItem($item['id'], $item['num']);
        }
        // 日志数据
        $contentArr = array(
            'from_user_id' => $this->_userId,
            'slot_id' => $slotId,
            'task_id' => $taskId,
        );
        $dataArr = array(
            'log_type' => 7, // 完成发布任务
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $dataArr, $friendUserId); // 写好友日志(完成发布任务)
        //给好友加奖励
        foreach ($rewardArr as &$item) {
            $item['num'] = max(1, round($item['num'] / 2));
            Bag::incrItem($item['id'], $item['num'], $friendUserId); // 添加道具到好友的背包
        }
        // 日志数据
        $contentArr = array(
            'from_user_id' => $this->_userId,
            'slot_id' => $slotId,
            'task_id' => $jsonArr['task_id'],
            'reward' => $rewardArr,
        );
        $dataArr = array(
            'log_type' => 6, // 接受发布任务
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $dataArr, $friendUserId); // 写好友日志(接受发布任务)
        // 更新每日任务进度
        User::updateDayTask(5);
        // 写动作日志
        $dataArr = array(
            'val1' => $taskId,
            'val2' => 0, // 暂时没用
            'val3' => 0, // 暂时没用
            'val4' => '', // 暂时没用
        );
        User::actionLog(User::ACTION_TYPE_FINISH_TASK, $dataArr); // 写动作日志(完成任务)
        $this->_ret = 0;
    }

    /**
     * 获取新手任务状态
     */
    public function getInitTaskStatus() {
        $initTaskModel = new InitTaskModel();
        $this->_data['status'] = $initTaskModel->hGetAll();
        $this->_ret = 0;
    }

    /**
     * 完成新手任务
     * @param int $taskId 任务ID
     */
    public function finishInitTask($taskId = NULL) {
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $initTaskModel = new InitTaskModel();
        $val = $initTaskModel->hGet($taskId, false);
        if ($val === false) { // 没取到
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        if ($val >= 10000) { // 已经完成
            $this->_data['msg'] = 'already finished';
            return;
        }
        // 更新任务状态(>=10000表示完成)
        $val = $initTaskModel->hIncrBy($taskId, 10000);
        if ($val === false) {
            $this->_data['msg'] = 'unknown error';
            return;
        }
        if ($val >= 20000) {
            $this->_data['msg'] = 'already finished2';
            return;
        }
//        if ($taskId < 12 && $initTaskModel->hSet($taskId + 1, 0) === false) {
//            $this->_data['msg'] = 'reset next task fail';
//            return;
//        }
        $rewardArr = Common::getInitTaskReward($taskId);
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
//        if ($taskId == 12) { // 完成了所有的新手任务,重置每日任务计数
//            // 每日任务重置
//            $dayTaskModel = new DayTaskModel();
//            $dayTaskDataArr = array(
//                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0,
//                6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0,
//                11 => 0, 12 => 0, 13 => 0, 14 => 0,
//                101 => 0, 102 => 0, 103 => 0,
//            );
//            $dayTaskModel->hMset($dayTaskDataArr);
//        }
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 完成每日任务
     * @param int $taskId 任务ID
     */
    public function finishDayTask($taskId = NULL) {
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        if ($taskId > 100) { // 积分奖励
            $this->__getDayTaskPointReward($taskId);
            return;
        }
        $configArr = Common::getDayTaskConfig($taskId);
        if ($configArr === false) { // 配置信息没取到
            $this->_data['msg'] = 'invalid task or config error';
            return;
        }
        $dayTaskModel = new DayTaskModel();
        $num = $dayTaskModel->hGet($taskId, false);
        if ($num === false) { // 没取到
            $this->_data['msg'] = 'unknown error';
            return;
        }
        if ($num >= 10000) { // 已完成
            $this->_data['msg'] = 'already finished';
            return;
        }
        if ($num < $configArr['num']) { // 没完成
            $this->_data['msg'] = 'not enough num';
            return;
        }
        $val = $dayTaskModel->hIncrBy($taskId, 10000);
        if ($val === false) {
            $this->_data['msg'] = 'hIncrBy fail';
            return;
        }
        if ($val >= 20000) {
            $this->_data['msg'] = 'already finished2';
            return;
        }
        foreach ($configArr['reward'] as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_ret = 0;
    }

    /**
     * 获取每日任务积分奖励
     * @param int $taskId 任务ID(101=30积分,102=60积分,103=100积分)
     */
    private function __getDayTaskPointReward($taskId = NULL) {
        if (!in_array($taskId, array(101, 102, 103))) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        switch ($taskId) { // 不同类型需要的积分数
            case 101:
                $needPoint = 30;
                break;
            case 102:
                $needPoint = 60;
                break;
            case 103:
                $needPoint = 100;
                break;
        }
        $dayTaskModel = new DayTaskModel();
        $dataArr = $dayTaskModel->hGetAll();
        if ($dataArr[$taskId] >= 10000) {
            $this->_data['msg'] = 'already finished';
            return;
        }
        $finishNum = 0;
        foreach ($dataArr as $key => $val) {
            if ($key <= 100 && $val >= 10000) {
                ++$finishNum;
            }
        }
        $curPoint = $finishNum * 10; // 每个任务10分
        if ($curPoint < $needPoint) { // 积分不够
            $this->_data['msg'] = 'not enough point';
            return;
        }
        $val = $dayTaskModel->hIncrBy($taskId, 10000);
        if ($val === false) {
            $this->_data['msg'] = 'hIncrBy fail';
            return;
        }
        if ($val >= 20000) {
            $this->_data['msg'] = 'already finished2';
            return;
        }
        // 获取随机奖励
        $rewardArr = Common::getDayTaskPointReward($taskId);
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 将自己从好友任务邀请中移除
     * @param int $friendUserId 好友用户ID
     * @param int $taskId 任务ID
     */
    private function __removeFromFriendTask($friendUserId, $taskId) {
        $taskModel = new TaskModel($friendUserId);
        $jsonStr = $taskModel->hGet('info', false);
        if ($jsonStr !== false) {
            $jsonArr = json_decode($jsonStr, true);
            if ($jsonArr !== NULL && $jsonArr['task_id'] == $taskId) {
                $idArr = array();
                foreach ($jsonArr['friends'] as $id) {
                    if ($id != $this->_userId) {
                        $idArr[] = $id;
                    }
                }
                if (count($idArr) != count($jsonArr['friends'])) {
                    $jsonArr['friends'] = $idArr;
                    $taskModel->hSet('info', json_encode($jsonArr));
                }
            }
        }
    }

    /**
     * 加锁
     * @param int $friendUserId 好友用户ID
     * @param int $slotId 任务槽ID
     */
    private function __addLock($friendUserId, $slotId) {
        $this->_lockModel = new MutexModel($friendUserId, MutexModel::SLOT_TASK, $slotId);
        return $this->_lockModel->addMutex($this->_lockTime);
    }

    /**
     * 移除锁
     */
    private function __removeLock() {
        $this->_lockModel && $this->_lockModel->removeMutex();
    }
}
?>
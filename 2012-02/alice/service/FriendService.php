<?php
/**
 * 好友服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class FriendService extends Service {
    /**
     * 普通用户祝福点数上限
     * @var int
     */
    const NORMAL_MAX_BENISON = 3;
    /**
     * VIP用户祝福点数上限
     * @var int
     */
    const VIP_MAX_BENISON = 5;
    /**
     * 每天对同一好友留言/回复的最大次数
     * @var int
     */
    const LEAVE_MSG_MAX_TIMES = 6;

    /**
     * 获取指定好友的信息
     * @param int $friendUserId 好友用户ID
     */
    public function getFriendInfo($friendUserId = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        $dataArr = array();

        // 好友信息
        $friendUserModel = new UserModel($friendUserId);
        $userDataArr = $friendUserModel->hGetAll();
        if (empty($userDataArr)) {
            $this->_data['msg'] = 'cannot get friend info';
            $this->_ret = UserException::ERROR_USER_NOT_EXISTS;
            return;
        }

        $dataArr['user'] = $userDataArr;

        // 数据互通处理
        $keySuffix = Common::getKeySuffix();
        if ($keySuffix !== '') { // 数据互通处理
            $keyArr = array('username', 'head_img');
            foreach ($keyArr as $key) {
                $dataArr['user'][$key] = isset($dataArr['user'][$key . $keySuffix]) ? $dataArr['user'][$key . $keySuffix] : '';
            }
        }

        // 服装信息
        $dressModel = new DressModel($friendUserId);
        $dataArr['user']['avatar'] = $dressModel->hGetAll();

        // 任务信息
        $taskModel = new TaskModel($friendUserId);
        $dataArr['user']['task'] = $taskModel->hGetAll();

        // 任务槽列表
        $taskSlotModel = new TaskSlotModel($friendUserId);
        $dataArr['user']['task']['slot'] = $taskSlotModel->hGetAll();

        // 家
        $frinedHomeModel = new HomeModel($friendUserId);
        $homeDataArr = $frinedHomeModel->hGetAll();
        $dataArr['home'] = $homeDataArr;

        // 图鉴奖励标志
        $flagModel = new FlagModel($friendUserId, FlagModel::TYPE_COLLECTION);
        $dataArr['user']['collection_flag'] = $flagModel->getBinStr();

        // 更新每日任务进度
        if ($this->_userId != $friendUserId) { // 排除掉自己

                User::updateDayTask(2);

        }
        $this->_data = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 获取指定好友的信息(排名显示用)
     * @param int $friendUserId 好友用户ID
     */
    public function getFriendInfo2($friendUserId = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }

        $dataArr = array();

        // 好友信息
        $friendUserModel = new UserModel($friendUserId);
        $userDataArr = $friendUserModel->hGetAll();
        if (empty($userDataArr)) {
            $this->_data['msg'] = 'cannot get friend info';
            return;
        }

        // 体力处理
        $userDataArr['energy_time'] = User::getEnergyTime($userDataArr['user_id']); // 体力值最后同步时间
        $userDataArr['energy'] = User::getEnergy($userDataArr['energy_time'], $userDataArr['user_id']);

        $dataArr['user'] = $userDataArr;

        // 数据互通处理
        $keySuffix = Common::getKeySuffix();
        if ($keySuffix !== '') { // 数据互通处理
            $keyArr = array('username', 'head_img');
            foreach ($keyArr as $key) {
                $dataArr['user'][$key] = isset($dataArr['user'][$key . $keySuffix]) ? $dataArr['user'][$key . $keySuffix] : '';
            }
        }

        // 服装信息
        $dressModel = new DressModel($friendUserId);
        $dataArr['user']['avatar'] = $dressModel->hGetAll();

        $this->_data = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 给好友祝福
     * @param int $friendUserId 好友的用户ID
     */
    public function bless($friendUserId) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if ($friendUserId == $this->_userId) { // 不能是自己
            $this->_data['msg'] = 'cannot bless self';
            return;
        }
        // 获取祝福点数上限
        $maxBenison = $this->__isVip() ? self::VIP_MAX_BENISON : self::NORMAL_MAX_BENISON;
        $blessModel = new BlessModel(); //实例化祝福模型
        if ($blessModel->sAdd($friendUserId) === false) { // 添加到集合失败
            $this->_data['msg'] = 'already bless';
            return;
        }
        $friendUserModel = new UserModel($friendUserId);
        $benison = $friendUserModel->hGet('benison');
        if ($benison === false) { // 没取到,直接返回
            $this->_data['msg'] = 'get benison error';
            return;
        } else if ($benison < $maxBenison) { // 判断祝福点数是否达到上限
            if ($friendUserModel->hIncrBy('benison', 1) === false) {
                $blessModel->sRemove($friendUserId);
                $this->_data['msg'] = 'incr benison error';
                return;
            }
        }
        if ($blessModel->sCard() <= 20) {
            Bag::incrItem(Common::ITEM_SILVER, 5);
        }
        // 日志数据
        $contentArr = array(
            'from_user_id' => $this->_userId,
        );
        $dataArr = array(
            'log_type' => 0, // 祝福好友
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $dataArr, $friendUserId); // 写好友日志(祝福好友)
        User::updateCredit(User::CREDIT_BLESS_FRIEND, 1); // 更新祝福好友荣誉次数
        User::updateCredit(User::CREDIT_BLESSED_BY_FRIEND, 1, $friendUserId); // 更新被好祝福荣誉次数
        // 更新每日任务进度
        User::updateDayTask(12);
        $this->_ret = 0;
    }

    /**
     * 获取指定好友的服装信息(城镇显示用)
     * @param array $friendUserIdArr 好友用户ID数组
     */
    public function getDressData($friendUserIdArr = NULL) {
        if (!is_array($friendUserIdArr) || count($friendUserIdArr) < 1 || count($friendUserIdArr) > 10) {
            $this->_data['msg'] = 'too much or too less friend user id';
            $this->_ret = 0; // 这里不报错
            return;
        }
        $tempArr = array();
        foreach ($friendUserIdArr as $friendUserId) {
            $dressModel = new DressModel($friendUserId);
            $tempArr['dress'][$friendUserId] = $dressModel->hGetAll(); // 获取服装数据
        }
        $tempArr = array(); // AS报错，先屏蔽掉
        $this->_data = $tempArr;
        $this->_ret = 0;
    }

    /**
     * 获取图鉴数据
     * @param int $friendUserId 好友用户ID
     */
    public function getCollectionData($friendUserId = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        // 图鉴
        $collectionModel = new CollectionModel($friendUserId);
        // 图鉴奖励标志
        $flagModel = new FlagModel($friendUserId, FlagModel::TYPE_COLLECTION);
        $this->_data = array(
            'collection' => $collectionModel->sMembers(),
            'collection_flag' => $flagModel->getBinStr(),
        );
        $this->_ret = 0;
    }

    /**
     * 获取奖杯数据
     * @param int $friendUserId 好友用户ID
     */
    public function getCupData($friendUserId = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        $flagModel = new FlagModel($friendUserId, FlagModel::TYPE_CUP);
        $this->_data['cup_flag'] = $flagModel->getBinStr();
        $this->_ret = 0;
    }

    /**
     * 给好友发送留言
     * @param int $friendUserId 好友的用户ID
     * @param int $msg 留言内容
     */
    public function leaveMessage($friendUserId = NULL, $msg = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if ($msg === '') {
            $this->_data['msg'] = 'msg cannot empty';
            return;
        }
        if (strlen($msg) > 420) { // 留言字数太多
            $this->_data['msg'] = 'msg too long';
            return;
        }
        $sns = App::getSNS();
        if (!$sns->isContentValid($msg)) {
            $this->_data['msg'] = 'msg include bad word';
            return;
        }
        $sendMsgModel = new SendMsgModel();
        $num = $sendMsgModel->hGet($friendUserId, false);
        if ($num !== false && $num >= self::LEAVE_MSG_MAX_TIMES) { // 留言达到最大次数
            $this->_data['msg'] = 'reach max times';
            return;
        }
        $dataArr = array(
            'from_user_id' => $this->_userId,
            'msg' => $msg,
        );
        User::log(User::LOG_TYPE_LEAVE_MSG, $dataArr, $friendUserId); // 写好友日志
        $sendMsgModel->hIncrBy($friendUserId, 1);
        $this->_ret = 0;
    }

    /**
     * 删除留言
     * @param int $uid 唯一ID
     */
    public function deleteMessage($uid = NULL) {
        if (!is_numeric($uid)) {
            $this->_data['msg'] = 'invalid uid';
            return;
        }
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
        );
        $leaveMsgSQLModel = new LeaveMsgSQLModel();
        $leaveMsgSQLModel->SH()->find($whereArr)->delete();
        $this->_ret = 0;
    }

    /**
     * 阅读留言
     * @param int $uid 唯一ID
     */
    public function readMessage($uid = NULL) {
        if (!is_numeric($uid)) {
            $this->_data['msg'] = 'invalid uid';
            return;
        }
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
        );
        $leaveMsgSQLModel = new LeaveMsgSQLModel();
        $dataArr = array('is_read' => 1);
        $leaveMsgSQLModel->SH()->find($whereArr)->update($dataArr);
        $this->_ret = 0;
    }

    /**
     * 给好友赠送卡片
     * @param int $friendUserId 好友用户ID
     * @param int $itemId 道具ID
     */
    public function sendCard($friendUserId = NULL, $itemId = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        $dataArr = array(
            'from_user_id' => $this->_userId,
            'type' => 0, // 0为卡片留言,1为好友邀请
            'item_id' => $itemId,
        );
        User::log(User::LOG_TYPE_SEND_CARD, $dataArr, $friendUserId);
        $this->_ret = 0;
    }

    /**
     * 删除卡片
     * @param int $uid 唯一ID
     */
    public function deleteCard($uid = NULL) {
        if (!is_numeric($uid)) {
            $this->_data['msg'] = 'invalid uid';
            return;
        }
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
            'type' => 0,
        );
        $otherMsgSQLModel = new OtherMsgSQLModel();
        $otherMsgSQLModel->SH()->find($whereArr)->delete();
        $this->_ret = 0;
    }

    /**
     * 给好友发送邀请
     * @param int $friendUserId 好友用户ID
     * @param int $itemId 道具ID
     */
    public function sendInvite($friendUserId = NULL, $itemId = NULL) {
        if (!is_numeric($friendUserId) || $friendUserId <= 0) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        $dataArr = array(
            'from_user_id' => $this->_userId,
            'type' => 1, // 0为卡片留言,1为好友邀请
            'item_id' => $itemId,
        );
        User::log(User::LOG_TYPE_SEND_INVITE, $dataArr, $friendUserId);
        $this->_ret = 0;
    }

    /**
     * 删除邀请
     * @param int $uid 唯一ID
     */
    public function deleteInvite($uid = NULL) {
        if (!is_numeric($uid)) {
            $this->_data['msg'] = 'invalid uid';
            return;
        }
        $dataArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
            'type' => 1,
        );
        $otherMsgSQLModel = new OtherMsgSQLModel();
        $otherMsgSQLModel->SH()->find($whereArr)->delete();
        $this->_ret = 0;
    }

    /**
     * 判断用户是否是VIP
     * @return bool
     */
    private function __isVip() {
        return false;
    }
}
?>
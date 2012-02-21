<?php
/**
 * 用户类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class User {
    /**
     * 体力恢复间隔时间(秒)
     * @var int
     */
    const ENERGY_RECOVERY_INTERVAL = 480;
    /**
     * 日志类型-好友任务
     * @var int
     */
    const LOG_TYPE_FRIEND_TASK = 1;
    /**
     * 日志类型-赠送礼物
     * @var int
     */
    const LOG_TYPE_SEND_GIFT = 2;
    /**
     * 日志类型-索求礼物
     * @var int
     */
    const LOG_TYPE_REQUEST_GIFT = 3;
    /**
     * 日志类型-用户动态
     * @var int
     */
    const LOG_TYPE_NEWS = 4;
    /**
     * 日志类型-好友留言
     * @var int
     */
    const LOG_TYPE_LEAVE_MSG = 5;
    /**
     * 日志类型-发送卡片
     * @var int
     */
    const LOG_TYPE_SEND_CARD = 6;
    /**
     * 日志类型-发送邀请
     * @var int
     */
    const LOG_TYPE_SEND_INVITE = 7;
    /**
     * 日志类型-宝箱邀请
     * @var int
     */
    const LOG_TYPE_CHEST_INVITE = 8;
    /**
     * 日志类型-金币充值
     */
    const LOG_TYPE_GOLD_RECHARGE = 9;
    /**
     * 日志类型-金币日志
     */
    const LOG_TYPE_GOLD_LOG = 10;
    /**
     * 荣誉-成功挑战关卡
     * @var int
     */
    const CREDIT_LEVEL_CHALLENGE = 1;
    /**
     * 荣誉-兑换收藏品
     * @var int
     */
    const CREDIT_COLLECTION_EXCHANGE = 2;
    /**
     * 荣誉-祝福好友
     * @var int
     */
    const CREDIT_BLESS_FRIEND = 3;
    /**
     * 荣誉-被好友祝福
     * @var int
     */
    const CREDIT_BLESSED_BY_FRIEND = 4;
    /**
     * 荣誉-访问好友
     * @var int
     */
    const CREDIT_VISIST_FRIEND = 5;
    /**
     * 荣誉-闯关失败
     * @var int
     */
    const CREDIT_LEVEL_FAIL = 6;
    /**
     * 动作类型-购买金币道具
     * @var int
     */
    const ACTION_TYPE_BUY_SILVER_ITEM = 100;
    /**
     * 动作类型-购买FH币道具
     * @var int
     */
    const ACTION_TYPE_BUY_GOLD_ITEM = 101;
    /**
     * 动作类型-使用体力
     * @var int
     */
    const ACTION_TYPE_USE_ENERGY = 102;
    /**
     * 动作类型-完成任务
     * @var int
     */
    const ACTION_TYPE_FINISH_TASK = 103;
    /**
     * 动作类型-使用道具
     * @var int
     */
    const ACTION_TYPE_USE_ITEM = 104;

    /**
     * 获取指定用户的用户模型
     * @param int $userId 用户ID
     * @return UserModel
     */
    private static function __userModel($userId) {
        if ($userId === NULL) { // 如果用户ID为NULL,取当前登录用户的用户ID
            $userId = App::get('user_id');
        }
        return App::getInst('UserModel', $userId, false, $userId);
    }

    /**
     * 创建用户(存在时直接返回用户ID)
     * @param string $snsUid sns uid
     * @param &bool $isNewUser 是否是新用户
     * @return int/bool
     */
    public static function createUser($snsUid, &$isNewUser = NULL) {
        $isNewUser = false;
        $relationModel = new RelationModel($snsUid);
        $userId = $relationModel->get();
        if (is_numeric($userId)) { // 有效的用户ID,返回用户ID
            return $userId;
        } else if ($userId === false) { // 角色不存在
            $userId = $relationModel->get();
            if ($userId !== false) {
                return false;
            }
            $userId = $relationModel->genNextUserId(); // 生成下一个用户ID
            if ($userId === false) { // ID生成失败
                return false;
            }
            if ($relationModel->setnx($userId . '=INIT') === false) { // 设置失败
                return false;
            }
        } else { // 上次角色初始化时出错了
            $userId = intval($userId);
        }
        $userDataArr = array(
            'user_id' => $userId,
            'sns_uid' => $snsUid,
            'gender' => -1, // 性别(-1=未设置,0=girl,1=boy)
            'title' => 0, // 称号
            'exp' => 0, // 经验值
            'last_exp' => 0, // 上一次升级后的经验值
            'energy_time' => 0, // 体力值最后同步时间
            'benison' => 0, // 祝福值
            'silver' => 5000, // 金币
            'gold' => 0, // FH币
            'charm' => 0, // 魅力值
            'heart' => 0, // 爱心值
            'cancel_task' => 3, // 今天可取消任务剩余次数
            'npc_times' => 20, // 今天可自动推送NPC任务剩余次数
            'help_times' => 20, // 今天可帮助好友完成NPC任务剩余次数
            'feed_times' => 10, // 今天可吃食物剩余次数
            'play_times1' => 10, // 今天系统剩余的可玩次数
            'friends_num' => 0, // 好友数
            'invite_num' => 0, // 邀请好友数
            'invite_reward_num' => 0, // 邀请好友奖励领取数
            'chest_no' => 0, // 宝箱编号
            'chest_invite_num' => 0, // 今天宝箱邀请人数
            'newer_test' => '', // 新手引导调查标志(0=放弃,1=完成)
            'progress' => '1-0-0', // 普通模式下的进度
            'revive_times1' => 3, // 今天可使用祝福复活剩余次数
            'revive_times2' => 3, // 今天可使用金币复活剩余次数
            'level_success' => 0, // 闯关成功数
            'level_fail' => 0, // 闯关失败数
            'activity_flag' => '00000000', // 活动标志
            'wish_items' => '', // 许愿道具(逗号分隔)
            'carry_pets' => '', // 携带宠物(逗号分隔)
            'login_reward' => 0, // 今天登录奖励领取标志(0=未领取,1=已领取)
            'status' => 1, // 帐号状态
            'last_login' => 0, // 上一次登录时间
            'month_login_times' => 0, // 当月登录次数
            'continue_times' => 0, // 连续登录次数
            'sign_times' => 0, // 签到次数
            'last_sign' => 0, // 最后签到时间
            'create_time' => CURRENT_TIME,
        );
        $userModel = new UserModel($userId);
        if ($userModel->hMset($userDataArr) === false) {
            return false;
        }
        $homeModel = new HomeModel($userId);
        $homeDataArr = array(
            'background' => 2080, // 背景
            'house' => '{"id":2094,"level":0}', // 房子
            'board' => '{"id":2090,"level":0}', // 告示板
            'postbox' => '{"id":2082,"level":0}', // 邮箱
            'tree' => '{"id":2084,"level":0,"last_pick":0}', // 魔法果树
            'horse' => '{"id":"2086","level":0,"last_level_up":0}', // 马车
            'honeybee' => '{"id":2085,"level":0,"last_pick":0,"last_level_up":0}', // 魔法蜂巢
            'workshop' => '{"id":2088,"level":0,"last_level_up":0}', // 精灵工坊
            'farm' => '{"id":2083,"level":0}', // 磨坊农田
            'trade' => '{"id":2092,"level":0}', // 摊位
            'spirit' => '{"id":2089,"level":0,"last_level_up":0}', // 精灵屋
            'totem' => '{"id":2091,"level":0}', // 图腾柱
            'radio' => '{"id":2093,"level":0}', // 收音机
            'pet' => '{"id":2081,"level":0}', // 宠物屋
        );
        if ($homeModel->hMset($homeDataArr) === false) {
            return false;
        }
        $bagModel = new BagModel($userId);
        if ($bagModel->delete() === false) {
            return false;
        }
        $bagDataArr = array(
            3004 => 1, // 木箱
            7200 => 2, // 木棒
            7202 => 2, // 草绳
            7204 => 2, // 铁丝
            7205 => 2, // 蜂胶
        );
        if ($bagModel->hMset($bagDataArr) === false) {
            return false;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel($userId);
        if ($unfinishedTaskModel->delete() === false) {
            return false;
        }
        if ($unfinishedTaskModel->sAdd(100) === false) {
            return false;
        }
        $initTaskDataArr = array(
            1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0,
            6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0,
            11 => 0, 12 => 0, 13 => 0,
        );
        $initTaskModel = new InitTaskModel($userId);
        if ($initTaskModel->hMset($initTaskDataArr) === false) {
            return false;
        }
        // 默认开启第一个任务槽(新手引导需要)
        $taskSlotModel = new TaskSlotModel($userId);
        if ($taskSlotModel->hSet(1, '{}') === false) {
            return false;
        }
        // 内测玩家奖励处理
        if (App::callFunc('neiceReward', array($snsUid, $userId)) === false) {
            return false;
        }
        if ($relationModel->set($userId) === false) {
            return false;
        }
        $isNewUser = true;
        return $userId; // 只有到这里才表示角色初始化完全成功了
    }

    /**
     * 获取体力恢复时间间隔(秒)
     * @param int $userId 用户ID
     * @return int
     */
    public static function getEnergyRecoveryInterval($userId = NULL) {
        if ($userId === NULL) {
            $userId = App::get('user_id');
        }
        $effectArr = Effect::getFixedEffect($userId);
        return (self::ENERGY_RECOVERY_INTERVAL * (1 - $effectArr['e4']) - $effectArr['e3']);
    }

    /**
     * 获取指定用户的体力上限
     * @param int $userId 用户ID
     * @return int
     */
    public static function getMaxEnergy($userId = NULL) {
        if ($userId === NULL) {
            $userId = App::get('user_id');
        }
        $userModel = App::getInst('UserModel', $userId, false, $userId);
        $effectArr = Effect::getFixedEffect($userId);
        return (Common::getMaxEnergyByExp($userModel->hGet('exp')) * (1 + $effectArr['e2']) + $effectArr['e1']);
    }

    /**
     * 获取体力恢复时间
     * @param int $userId 用户ID
     * @return int
     */
    public static function getEnergyTime($userId = NULL) {
        $dataArr = self::__userModel($userId)->hMget(array('exp', 'energy_time'));
        $exp = $dataArr['exp'];
        $energyTime = $dataArr['energy_time'];
        // 当前等级体力上限
        $maxEnergy = self::getMaxEnergy($userId);
        $energyRecoveryInterval = self::getEnergyRecoveryInterval($userId);
        if ($energyTime > CURRENT_TIME) {
            $energyTime = CURRENT_TIME;
        } else if ($energyTime < CURRENT_TIME - $maxEnergy * $energyRecoveryInterval) {
            $energyTime = CURRENT_TIME - $maxEnergy * $energyRecoveryInterval;
        }
        return $energyTime;
    }

    /**
     * 获取当前体力点数
     * @param int $energyTime 体力恢复时间
     * @param int $userId 用户ID
     * @return int
     */
    public static function getEnergy($energyTime = NULL, $userId = NULL) {
        if ($energyTime === NULL) {
            return floor((CURRENT_TIME - self::getEnergyTime($userId)) / self::getEnergyRecoveryInterval($userId));
        } else {
            return floor((CURRENT_TIME - $energyTime) / self::getEnergyRecoveryInterval($userId));
        }
    }

    /**
     * 更新体力
     * @param int $point 点数(正表示加体力,负表示扣体力)
     * @param int $userId 用户ID
     * @return int/false
     */
    public static function updateEnergy($point, $userId = NULL) {
        return self::__userModel($userId)->hSet('energy_time', self::getEnergyTime(NULL, $userId) - $point * self::getEnergyRecoveryInterval($userId));
    }

    /**
     * 写用户日志
     * @param int $logType 日志类型
     * @param array $dataArr 数据数组
     * @param int/NULL $userId 用户ID
     */
    public static function log($logType, $dataArr, $userId = NULL) {
        switch ($logType) { // 根据日志类型,设置不同的日志模型
            case self::LOG_TYPE_FRIEND_TASK:
                $class = 'FriendTaskSQLModel';
                break;
            case self::LOG_TYPE_SEND_GIFT:
                $class = 'SendGiftSQLModel';
                break;
            case self::LOG_TYPE_REQUEST_GIFT:
                $class = 'RequestGiftSQLModel';
                break;
            case self::LOG_TYPE_NEWS: // 0=祝福好友 1=给好友喂宠物 2=帮好友完成任务 3=给了好友索求的礼物 4=邀请好友成功 5=称号额外奖励 6=接受发布任务 7=完成发布任务 8=领取贸易收益 9=收到马车贸易
                $class = 'NewsLogSQLModel';
                break;
            case self::LOG_TYPE_LEAVE_MSG:
                $class = 'LeaveMsgSQLModel';
                break;
            case self::LOG_TYPE_SEND_CARD:
                $class = 'SendCardSQLModel';
                break;
            case self::LOG_TYPE_SEND_INVITE:
                $class = 'SendInviteSQLModel';
                break;
            case self::LOG_TYPE_CHEST_INVITE:
                $class = 'ChestInviteSQLModel';
                break;
            case self::LOG_TYPE_GOLD_RECHARGE:
                $class = 'GoldRechargeSQLModel';
                break;
            case self::LOG_TYPE_GOLD_LOG:
                $class = 'GoldLogSQLModel';
                break;
            default:
                throw new UserException(UserException::ERROR_SYSTEM, 'UNKNOWN_LOG_TYPE');
        }
        if (!is_array($dataArr) || empty($dataArr)) { // 必须给定数据
            throw new UserException(UserException::ERROR_SYSTEM, 'BAD_LOG_DATA');
        }
        if ($userId === NULL) { // 如果用户ID为NULL,取当前登录用户的用户ID
            $userId = App::get('user_id');
        }
        $dataArr['user_id'] = $userId;
        $dataArr['create_time'] = CURRENT_TIME;
        $logSQLModel = App::getInst($class, $userId, false, $userId);
        $logSQLModel->SH()->insert($dataArr);
    }

    /**
     * 获取当前登录用户的最新日志
     * @return array
     */
    public static function getLog() {
        $userId = App::get('user_id'); // 获取当前登录用户的用户ID
        $classArr = array(
            'task' => 'FriendTaskSQLModel',
            'gift' => 'SendGiftSQLModel',
            'request' => 'RequestGiftSQLModel',
            'news' => 'NewsLogSQLModel',
            'chest' => 'ChestInviteSQLModel',
        );
        $dataArr = array();
        foreach ($classArr as $key => $class) {
            if ($key === 'chest') {
                $whereArr = array(
                    'user_id' => $userId,
                );
            } else {
                $whereArr = array(
                    'user_id' => $userId,
                    '>=' => array(
                        'create_time' => CURRENT_TIME - 72000, // 当前时间往后20小时
                    ),
                );
            }
            $obj = App::getInst($class, $userId, false, $userId);
            $dataArr[$key] = $obj->SH()->find($whereArr)->getAll();
        }
        return $dataArr;
    }

    /**
     * 获取当前登录用户的消息
     * @return array
     */
    public static function getMsg() {
        $userId = App::get('user_id'); // 获取当前登录用户的用户ID
        $classArr = array(
            'msg' => 'LeaveMsgSQLModel',
            'other' => 'OtherMsgSQLModel',
        );
        $dataArr = array();
        foreach ($classArr as $key => $class) {
            $obj = App::getInst($class, $userId, false, $userId);
            if ($key === 'msg') {
                $whereArr = array(
                    'user_id' => $userId,
                    '>=' => array(
                        'create_time' => CURRENT_TIME - 172800, // 当前时间往后48小时
                    ),
                );
                $orderByArr = array(
                    'create_time' => 'DESC',
                );
                $dataArr[$key] = $obj->SH()->find($whereArr)->orderBy($orderByArr)->limit(0, 30)->getAll();
            } else {
                $whereArr = array(
                    'user_id' => $userId,
                );
                $dataArr[$key] = $obj->SH()->find($whereArr)->getAll();
            }
        }
        return $dataArr;
    }

    /**
     * 动作记录
     * @param int $actionType
     * @param array $dataArr
     * @param int $userId
     */
    public static function actionLog($actionType, $dataArr, $userId = NULL) {
        if ($userId === NULL) {
            $userId = App::get('user_id');
        } else if (!is_numeric($userId)) { // 用户ID有效性判断
            throw new UserException(UserException::ERROR_SYSTEM, 'INVALID_USER_ID');
        }
        if (!is_array($dataArr) || empty($dataArr)) { // 必须给定数据
            throw new UserException(UserException::ERROR_SYSTEM, 'BAD_LOG_DATA');
        }
        $dataArr['user_id'] = $userId;
        $dataArr['type'] = $actionType;
        $dataArr['time'] = CURRENT_TIME;
        $actionRecordSQLModel = new ActionRecordSQLModel($userId);
        $actionRecordSQLModel->SH()->insert($dataArr);
    }

    /**
     * 更新荣誉
     * @param int $creditId 荣誉ID
     * @param int $num 数量
     * @param int $userId 用户ID(默认为NULL,取当前登录用户的用户ID)
     * @return int/false
     */
    public static function updateCredit($creditId, $num = 1, $userId = NULL) {
        if (!is_numeric($num)) {
            throw new UserException(UserException::ERROR_SYSTEM, 'BAD_CREDIT_NUM');
        }
        if ($userId === NULL) {
            $userId = App::get('user_id');
        }
        $creditModel = new CreditModel($userId);
        return $creditModel->hIncrBy($creditId, $num);
    }

    /**
     * 更新每日任务进度
     * @param int $taskId 任务编号
     * @param int $num 数量
     * @return int
     */
    public static function updateDayTask($taskId, $num = 1) {
        $dayTaskModel = new DayTaskModel();
        return $dayTaskModel->hIncrBy($taskId, $num);
    }

    /**
     * 获取NPC任务
     * @return int/NULL 任务ID
     */
    public static function fetchNPCTask() {
//        $firstSystemModel = new FirstSystemModel();
//        $val = $firstSystemModel->getBit(7);
//        if ($val != 1) { // 固定新手引导任务
//            return 250;
//        }
        $userModel = new UserModel();
        if ($userModel->hGet('npc_times', 0) <= 0) { // 剩余自动推送次数没了
            return NULL;
        }
        $unfinishedTaskModel = new UnfinishedTaskModel();
        if ($unfinishedTaskModel->sCard() > 0) { // 已经存在支线任务
            return NULL;
        }
        $grade = Common::getGradeByExp($userModel->hGet('exp'));
        $newTaskId = NULL;
        // 获取所有的NPC任务编号
        for ($i = $grade; $i >= 1; --$i) {
            $npcTaskSetModel = new NpcTaskSetModel($i);
            $taskIdArr = $npcTaskSetModel->sMembers();
            if (empty($taskIdArr)) { // 没有设置任务NPC任务
                continue;
            }
            shuffle($taskIdArr); // 打乱顺序
            $newTaskId = array_shift($taskIdArr);
            break;
        }
        if ($newTaskId !== NULL && $unfinishedTaskModel->sAdd($newTaskId) !== false) {
            $userModel->hIncrBy('npc_times', -1);
        } else {
            $newTaskId = NULL;
        }
        return $newTaskId;
    }

    /**
     * 获取Slot任务
     * @return int/NULL 任务ID
     */
    public static function fetchSlotTask() {
        $userModel = new UserModel();
        $grade = Common::getGradeByExp($userModel->hGet('exp'));
        // 获取所有的NPC任务编号
        $taskIdArr = array();
        for ($i = $grade; $i >= 1; --$i) {
            $slotTaskSetModel = new SlotTaskSetModel($i);
            $tempArr = $slotTaskSetModel->sMembers();
            if (empty($tempArr)) { // 没有设置任务NPC任务
                continue;
            }
            $taskIdArr = array_merge($taskIdArr, $tempArr);
        }
        shuffle($taskIdArr); // 打乱顺序
        return array_shift($taskIdArr);
    }

    /**
     * 获取好友(自动追加默认好友到好友列表)
     * @param bool $readFromCache 是否从缓存读取
     * @return array
     */
    public static function getFriends($readFromCache = true) {
        $currentUser = App::getCurrentUser();
        $debugMode = isset($currentUser['user_id_old']);
        if ($debugMode || $readFromCache) { // 调试模式或从缓存读取的情况下,才读取缓存
            $friendModel = new FriendModel();
            $friendsStr = $friendModel->get();
            if ($friendsStr !== false) { // 有取到
                $friendsStr = self::uncompress($friendsStr);
                if ($friendsStr !== false) { // 解压缩成功
                    $friendsArr = json_decode($friendsStr, true);
                    if ($friendsArr !== NULL) { // 解码成功
                        return $friendsArr;
                    }
                }
            }
        }
        $sns = App::getSNS();
        $friendsArr = $sns->getFriends();
        $snsUidArr = array();
        $relationModel = new RelationModel();
        $storeKeyPrefix = $relationModel->getStoreKeyPrefix();
        foreach ($friendsArr as $friends) {
            $snsUidArr[] = $storeKeyPrefix . ':' . $friends['sns_uid'];
        }
        $userIdArr = $relationModel->RH()->mget($snsUidArr);
        if (is_array($userIdArr)) {
            foreach ($userIdArr as $index => $userId) {
                if ($userId === false || !is_numeric($userId)) { // 关系没取到
                    $friendsArr[$index]['user_id'] = 0;
                    $friendsArr[$index]['exp'] = 0;
                    $friendsArr[$index]['silver'] = 0;
                    $friendsArr[$index]['heart'] = 0;
                    $friendsArr[$index]['wish_items'] = '';
                    continue;
                }
                $userModel = new UserModel($userId);
                $userDataArr = $userModel->hMget(array('user_id', 'exp', 'silver', 'heart', 'wish_items'));
                if (empty($userDataArr)) { // 没取到(网络原因或其他)
                    $friendsArr[$index]['user_id'] = $userId;
                    $friendsArr[$index]['exp'] = 0;
                    $friendsArr[$index]['silver'] = 0;
                    $friendsArr[$index]['heart'] = 0;
                    $friendsArr[$index]['wish_items'] = '';
                } else { // 取到了
                    $friendsArr[$index]['user_id'] = $userDataArr['user_id'];
                    $friendsArr[$index]['exp'] = $userDataArr['exp'];
                    $friendsArr[$index]['silver'] = $userDataArr['silver'];
                    $friendsArr[$index]['heart'] = $userDataArr['heart'];
                    $friendsArr[$index]['wish_items'] = $userDataArr['wish_items'];
                }
            }
        }
        $defaultFriend = App::get('DefaultFriend', false);
        if (!empty($defaultFriend)) { // 有设置
            $friendsArr[] = $defaultFriend;
        }
        if (!$debugMode) { // 非调试模式下才更新
            $friendModel = new FriendModel();
            if ($friendModel->set(self::compress(json_encode($friendsArr))) !== false) {
                // 缓存到后天零点
                $expireTime = strtotime('today', CURRENT_TIME) + 86400 * 2;
                if ($friendModel->expireAt($expireTime) === false) {
                    $friendModel->expireAt($expireTime);
                }
            }
        }
        return $friendsArr;
    }

    /**
     * 给称号奖励
     * @param int $titleId 称号ID
     */
    public static function giveTitleReward($titleId) {
        $configArr = Common::getTitleConfig($titleId);
        if ($configArr !== false) {
            foreach ($configArr['reward'] as $item) {
                Bag::incrItem($item['id'], $item['num']);
            }
            // 日志数据
            $contentArr = array(
                'items' => $configArr['reward'],
            );
            $dataArr = array(
                'log_type' => 5, // 称号额外奖励
                'content' => json_encode($contentArr),
            );
            User::log(User::LOG_TYPE_NEWS, $dataArr); // 写日志(称号额外奖励)
        }
    }

    /**
     * 获取银币上限
     * @param int $exp 经验值
     * @return int
     */
    public static function getMaxSilver($exp) {
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet('house', false);
        $rate = 100; // 预设一个很大的值,避免极端情况取数据异常时错扣钱
        if ($jsonStr !== false) { // 有取到
            $jsonArr = json_decode($jsonStr, true);
            if ($jsonArr !== NULL) { // 解码成功
                $level = $jsonArr['level'];
                if ($level == 0) {
                    $rate = 0;
                } else if ($level > 0) {
                    $configArr = Common::getHouseConfig($level);
                    if ($configArr !== false) {
                        $rate = $configArr['silver_rate'];
                    }
                }
            }
        }
        $grade = Common::getGradeByExp($exp);
        return 10000 * $grade * (1 + $rate);
    }

    /**
     * 删除过期的Key
     * @param int $userId 用户ID
     * @return int/false
     */
    public static function delExpiredKey($userId = NULL) {
        $keyArr = array();
        $visitModel = new VisitModel($userId); // 访问列表
        $keyArr[] = $visitModel->getStoreKey();
        $requestModel = new RequestModel($userId); // 已索求列表
        $keyArr[] = $requestModel->getStoreKey();
        $sendModel = new SendModel($userId); // 已赠送列表
        $keyArr[] = $sendModel->getStoreKey();
        $sendMsgModel = new SendMsgModel($userId); // 已留言列表
        $keyArr[] = $sendMsgModel->getStoreKey();
        $blessModel = new BlessModel($userId); // 祝福列表
        $keyArr[] = $blessModel->getStoreKey();
        $hiddenLevelModel = new HiddenLevelModel($userId, HiddenLevelModel::TYPE_FINISHED); // 已完成隐藏关卡列表
        $keyArr[] = $hiddenLevelModel->getStoreKey();
        $horseTradeModel = new HorseTradeModel(); // 马车贸易好友列表
        $keyArr[] = $horseTradeModel->getStoreKey();
        $inviteModel = new InviteModel(); // 好友帮助任务邀请列表
        $keyArr[] = $inviteModel->getStoreKey();
        $flagModel = new FlagModel(NULL, FlagModel::TYPE_FEED); // Feed发送标志
        $keyArr[] = $flagModel->getStoreKey();
        return $blessModel->RH()->delete($keyArr);
    }

    /**
     * 压缩数据
     * @param string $dataStr 数据
     * @return string/false
     */
    public static function compress($dataStr) {
        return gzcompress($dataStr, 6);
    }

    /**
     * 解压缩数据
     * @param string $dataStr 数据
     * @return string
     */
    public static function uncompress($dataStr) {
        return gzuncompress($dataStr);
    }
}
?>
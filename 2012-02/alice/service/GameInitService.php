<?php
/**
 * 游戏初始化服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class GameInitService extends Service {
    /**
     * 获取初始化数据
     */
    public function getInitData() {
        $dataArr = array();

        // 今天00:00:00
        $today = strtotime('today', CURRENT_TIME);
        // 昨天00:00:00
        $yesterday = $today - 86400;
        // 明天00:00:00
        $tomorrow = $today + 86400;
        // 本月1日00:00:00
        $thisMonth = strtotime('first day of this month 00:00:00', CURRENT_TIME);

        // 第一次登录处理
        $lastLogin = $this->_userModel->hGet('last_login');
        $isTodayFirst = $lastLogin < $today;
        $newUserDataArr = array();
        if ($isTodayFirst) { // 今天第一次登录
            // 回头用户统计
            $createTime = $this->_userModel->hGet('create_time');
            if ($today > $createTime) {
                $ago1 = $today - 86400;
                $ago3 = $today - 259200;
                $ago7 = $today - 604800;
                if ($ago1 < $createTime) {
                    Stat::userDayIncr('back_user1');
                    Stat::userDayIncr('back_user3');
                    Stat::userDayIncr('back_user7');
                } else if ($ago3 < $createTime) {
                    Stat::userDayIncr('back_user3');
                    Stat::userDayIncr('back_user7');
                } else if ($ago7 < $createTime) {
                    Stat::userDayIncr('back_user7');
                }
            }
            // 连续登录次数处理
            if ($lastLogin >= $yesterday) { // 昨天有登录,连续登录次数++
                $continueTimes = $this->_userModel->hIncrBy('continue_times', 1);
            } else { // 昨天没登录,重置连续登录次数
                $continueTimes = 1;
                $this->_userModel->hSet('continue_times', 1);
            }
            // 月登录次数统计
            if ($lastLogin >= $thisMonth) {
                $this->_userModel->hIncrBy('month_login_times', 1);
            } else {
                $this->_userModel->hSet('month_login_times', 1);
            }
            // 删除过期的Key
            User::delExpiredKey($this->_userId);
            $newUserDataArr['cancel_task'] = 3; // 重置今天可取消任务剩余次数
            $newUserDataArr['npc_times'] = 20; // 重置今天可自动推送NPC任务剩余次数
            $newUserDataArr['help_times'] = 20; // 重置今天可帮助好友完成NPC任务剩余次数
            $newUserDataArr['feed_times'] = 10; // 重置今天可吃食物剩余次数
            $newUserDataArr['chest_invite_num'] = 0; // 重置今天宝箱可邀请人数
            $newUserDataArr['play_times1'] = 10; //   重置今天系统剩余可玩次数
            $newUserDataArr['revive_times1'] = 3; // 重置今天可使用祝福复活剩余次数
            $newUserDataArr['revive_times2'] = 3; // 重置今天可使用金币复活剩余次数
            // 称号奖励处理
            $titleId = $this->_userModel->hGet('title');
            if (in_array($titleId, array(5, 6, 7, 8, 17, 18, 19, 20))) {
                User::giveTitleReward($titleId);
            }

            // 每日任务重置
            $dayTaskModel = new DayTaskModel();
            $dayTaskDataArr = array(
                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0,
                6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0,
                11 => 0, 12 => 0, 13 => 0, 14 => 0,
                101 => 0, 102 => 0, 103 => 0,
            );
            $dayTaskModel->hMset($dayTaskDataArr);
        }
        $newUserDataArr['last_login'] = CURRENT_TIME;
        $this->_userModel->hMset($newUserDataArr);

        // 是否是测试用户
        $newUserDataArr['is_test_user'] = false;

        // 体力处理
        $newUserDataArr['energy_time'] = User::getEnergyTime(); // 体力值最后同步时间
        $newUserDataArr['energy'] = User::getEnergy($newUserDataArr['energy_time']);
        // 家
        $homeModel = new HomeModel();
        $dataArr['home'] = $homeModel->hGetAll();

        // 用户日志
        $dataArr['log'] = User::getLog();

        // 免费礼物
        $dataArr['gift'] = Common::getFreeGift();

        // 背包
        $dataArr['bag'] = Bag::getAllItem();

        // SNS用户信息
        $snsUserInfo = $this->_currentUser['sns_user_info'];

        // 用户信息
        $userDataArr = $this->_userModel->hGetAll();
        $dataArr['user'] = array_merge($snsUserInfo, $userDataArr, $newUserDataArr);

        // 1、缓存平台用户名和头像地址;2、数据互通处理
        $keySuffix = Common::getKeySuffix();
        $keyArr = array('username', 'head_img');
        if ($isTodayFirst) { // 缓存平台用户名和头像地址
            $debugMode = isset($this->_currentUser['user_id_old']);
            if (!$debugMode) { // 非调试模式才操作
                $tempArr = array();
                foreach ($keyArr as $key) {
                    if (!isset($userDataArr[$key]) || $userDataArr[$key] != $snsUserInfo[$key]) {
                        $tempArr[$key . $keySuffix] = $snsUserInfo[$key];
                        $dataArr['user'][$key . $keySuffix] = $snsUserInfo[$key];
                    }
                }
                if (!empty($tempArr)) {
                    $this->_userModel->hMset($tempArr);
                }
            }
        }
        if ($keySuffix !== '') { // 数据互通处理
            foreach ($keyArr as $key) {
                $dataArr['user'][$key] = isset($dataArr['user'][$key . $keySuffix]) ? $dataArr['user'][$key . $keySuffix] : '';
            }
        }

        // 签到次数计算
        $lastSign = $dataArr['user']['last_sign'];
        if ($lastSign >= $yesterday && $lastSign < $today) {
            $signTimes = min(12, $dataArr['user']['sign_times'] + 1);
        } else {
            $signTimes = 1;
        }
        $dataArr['user']['sign_times'] = $signTimes;

        // 好友列表
        $dataArr['user']['friend_list'] = User::getFriends($isTodayFirst);
        if ($isTodayFirst) { // 今天第一次登录,更新好友数
            $friendsNum = count($dataArr['user']['friend_list']);
            $dataArr['user']['friends_num'] = $friendsNum;
            $this->_userModel->hSet('friends_num', $friendsNum);
        }

        // 服装信息
        $dressModel = new DressModel();
        $dataArr['user']['avatar'] = $dressModel->hGetAll();

        // 任务信息
        $taskModel = new TaskModel();
        $dataArr['user']['task'] = $taskModel->hGetAll();

        // 接取任务
        User::fetchNPCTask();

        // 未完成任务
        $unfinishedTaskModel = new UnfinishedTaskModel();
        $dataArr['user']['task']['status'] = $unfinishedTaskModel->sMembers();

        // 好友任务列表
        $friendTaskModel = new FriendTaskModel();
        $dataArr['user']['task']['list'] = array();

        // 任务槽列表
        $taskSlotModel = new TaskSlotModel();
        $dataArr['user']['task']['slot'] = $taskSlotModel->hGetAll();

        // 新手任务状态
        $initTaskModel = new InitTaskModel();
        $dataArr['user']['task']['new_init'] = $initTaskModel->hGetAll();

        // 每日任务进度
        if (!$isTodayFirst) {
            $dayTaskModel = new DayTaskModel();
            $dayTaskDataArr = $dayTaskModel->hGetAll();
        }
        $dataArr['user']['day_task'] = $dayTaskDataArr;

        // 访问列表
        $visitModel = new VisitModel();
        $dataArr['user']['visit_list'] = $visitModel->sMembers();

        // 已索求列表
        $requestModel = new RequestModel();
        $dataArr['user']['request_list'] = $requestModel->sMembers();

        // 已赚送列表
        $sendModel = new SendModel();
        $dataArr['user']['send_list'] = $sendModel->sMembers();

        // 已留言列表
        $sendMsgModel = new SendMsgModel();
        $dataArr['user']['msg_list'] = $sendMsgModel->hGetAll();

        // 已祝福列表
        $blessModel = new BlessModel();
        $dataArr['user']['bless_list'] = $blessModel->sMembers();

        // 好友帮助任务已邀请列表
        $inviteModel = new InviteModel();
        $dataArr['user']['invite_list'] = $inviteModel->sMembers();

        // 已解锁的隐藏关卡列表
        $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_UNLOCKED);
        $dataArr['user']['hidden_level_unlocked'] = $hiddenLevelModel->hGetAll();

        // 已闯过的隐藏关卡列表
        $hiddenLevelModel = new HiddenLevelModel(NULL, HiddenLevelModel::TYPE_FINISHED);
        $dataArr['user']['hidden_level_finished'] = $hiddenLevelModel->sMembers();

        // 宝箱邀请发送列表
        $chestInviteModel0 = new ChestInviteModel(NULL, 0);
        $dataArr['user']['chest_invite_send'] = $chestInviteModel0->sMembers();

        // 宝箱邀请接受列表
        $chestInviteModel1 = new ChestInviteModel(NULL, 1);
        $dataArr['user']['chest_invite_accept'] = $chestInviteModel1->sMembers();

        // 图鉴
        $collectionModel = new CollectionModel();
        $dataArr['user']['collection'] = $collectionModel->sMembers();

        // 马车已贸易的好友
        $horseTradeModel = new HorseTradeModel();
        $dataArr['user']['horse_trade'] = $horseTradeModel->sMembers();

        // 图鉴奖励标志
        $flagModel = new FlagModel(NULL, FlagModel::TYPE_COLLECTION);
        $dataArr['user']['collection_flag'] = $flagModel->getBinStr();

        // 荣誉
        $creditModel = new CreditModel();
        $dataArr['user']['credit'] = $creditModel->hGetAll();

        // 留言
        $dataArr['user']['info'] = User::getMsg();

        // 第一次系统标志
        $firstSystemModel = new FirstSystemModel();
        $dataArr['user']['first_flag'] = $firstSystemModel->getBinStr();

        // Feed标志
        $flagModel = new FlagModel(NULL, FlagModel::TYPE_FEED);
        $dataArr['user']['feed_flag'] = $flagModel->getBinStr();

        // 新手礼包标志
        $novieGiftModel = new NoviceGiftModel();
        $dataArr['user']['gift_flag'] = $novieGiftModel->getBinStr();

        // 道具指导
        $itemGuideModel = new ItemGuideModel();
        $dataArr['user']['item_guide'] = $itemGuideModel->sMembers();

        // 帐号迁移标志
        $dataArr['user']['account_moved'] = 1;

        // 是否是新用户
        $dataArr['user']['is_new'] = ($dataArr['user']['create_time'] > 1325647697);

        // 七夕活动标志
        if (defined('FESTIVAL_7_7_FLAG')) {
            $dataArr['user']['qixi'] = FESTIVAL_7_7_FLAG;
        }

        // 10人开宝箱活动标志
        if (defined('ACTIVITY_OPEN_CHEST')) {
            $dataArr['user']['flag_open_chest'] = 1;
        }

        // 百人达活动标志
        if (defined('ACTIVITY_INVITE_FRIEND')) {
            $dataArr['user']['flag_invite_friend'] = 1;
        }

//        $feedbackSQLModel = new FeedbackSQLModel();
//        $whereArr = array(
//            'user_id' => $this->_userId,
//        );
//        $count = $feedbackSQLModel->SH()->find($whereArr)->count();
//        if ($count > 0) {
//            $content = "尊敬的玩家：\n　　您好！非常感谢您参与<迷城有奖问卷>，我们准备了一个酬谢礼包来感谢您的支持，";
//            $content .= "礼包包含：奶酥包*5、甜甜圈*2、小木板*5、1000金币。点击领取就能获得酬谢礼包。";
//            $content .= "感谢您的给力支持，我们会尽力将《童话迷城》完善的更好！祝您游戏愉快，天天开心！";
//            $dataArr['user']['notice'][] = array(
//                'title' => '迷城问卷奖励',
//                'content' => $content,
//                'time' => '2011年08月10日-2011年08月31日',
//                'items' => array(
//                    array('id' => 4001, 'num' => 5),
//                    array('id' => 4002, 'num' => 2),
//                    array('id' => 3002, 'num' => 5),
//                    array('id' => 7901, 'num' => 1000),
//                ),
//                'id' => 1,
//            );
//        }
//        $content = "尊敬的迷友们：\n　　昨天下午服务器的停机维护，影响了大家的正常游戏，为表歉意，给予大家补偿：经验*300，甜甜圈*5。补偿将于 2011年8月31日14：00开始发放，直接发放到背包，请查收。";
//        $dataArr['user']['notice'][] = array(
//            'title' => '赔偿公告',
//            'content' => $content,
//            'time' => '2011年8月31日',
//        );
//        $content = "神秘问号开放兑换了，即日起只要身上持有神秘的问号，即可到彩虹村找玛丽亚进行兑换，还等什么，赶紧兑换吧！！";
//        $dataArr['user']['notice'][] = array(
//            'title' => '问号兑换活动',
//            'content' => $content,
//            'time' => '2011年08月19日-2011年08月31日',
//        );
        // 服务器当前时间
        $dataArr['server_time'] = time();

        $this->_data = $dataArr;
        $this->_ret = 0;
    }
}
?>
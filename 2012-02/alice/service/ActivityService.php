<?php
/**
 * 活动服务类
 * 
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ActivityService extends Service {
    /**
     * 开宝箱邀请
     * @param array $friendUserIdArr 好友用户ID数组
     */
    public function chestInvite($friendUserIdArr) {
        if (!is_array($friendUserIdArr) || empty($friendUserIdArr)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $chestInviteModel = new ChestInviteModel(NULL, 0);
        $chestInviteNum = $this->_userModel->hGet('chest_invite_num', 0);
        if ($chestInviteNum + count($friendUserIdArr) > 50) {
            $this->_data['msg'] = 'too many friend user id';
            return;
        }
        $num = 0;
        foreach ($friendUserIdArr as $friendUserId) {
            if ($chestInviteModel->sAdd($friendUserId) !== false) {
                $dataArr = array(
                    'from_user_id' => $this->_userId,
                );
                User::log(User::LOG_TYPE_CHEST_INVITE, $dataArr, $friendUserId); // 写宝箱邀请日志
                ++$num;
            }
        }
        $this->_userModel->hIncrBy('chest_invite_num', $num);
        $this->_ret = 0;
    }

    /**
     * 接受开宝箱邀请
     * @param int $uid 唯一ID
     */
    public function acceptChestInvite($uid) {
        $chestInviteSQLModel = new ChestInviteSQLModel();
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
        );
        $row = $chestInviteSQLModel->SH()->find($whereArr)->getOne();
        if (empty($row)) {
            $this->_data['msg'] = 'not found';
            return;
        }
        $chestInviteSQLModel->SH()->find($whereArr)->delete();
        $chestInviteModel = new ChestInviteModel($row['from_user_id'], 1);
        $chestInviteModel->sAdd($this->_userId);
        $this->_ret = 0;
    }

    /**
     * 拒绝开宝箱邀请
     * @param int $uid 唯一ID
     */
    public function refuseChestInvite($uid) {
        $chestInviteSQLModel = new ChestInviteSQLModel();
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
        );
        $chestInviteSQLModel->SH()->find($whereArr)->delete();
        $this->_ret = 0;
    }

    /**
     * 获取宝箱奖励
     * @param int $no 宝箱编号
     */
    public function getChestReward($no) {
        if (!is_numeric($no)) {
            $this->_data['msg'] = 'invalid chest no';
            return;
        }
        $lastNo = $this->_userModel->hGet('chest_no');
        if ($lastNo === false) {
            $this->_data['msg'] = 'cannot get chest no';
            return;
        }
        if ($lastNo + 1 != $no) {
            $this->_data['msg'] = 'bad chest no';
            return;
        }
        $rewardArr = Common::getChestRewardConfig($no);
        if ($rewardArr === false) {
            $this->_data['msg'] = 'cannot get chest reward';
            return;
        }
        $chestInviteModel = new ChestInviteModel(NULL, 1); //1 已经接受的邀请
        if ($chestInviteModel->sCard() < 10) {
            $this->_data['msg'] = 'not enough invite friend';
            return;
        }
        $chestInviteModel->delete();
        $chestInviteModel = new ChestInviteModel(NULL, 0); //0 未接受的邀请
        $chestInviteModel->delete();
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_data['reward'] = $rewardArr;
        $this->_userModel->hIncrBy('chest_no', 1);
        $this->_ret = 0;
    }

    /**
     * 获取新手测试奖励
     * @param int $flag 标志(0=放弃,1=完成)
     */
    public function finishNewerTest($flag) {
        $testFlag = $this->_userModel->hGet('newer_test');
        if ($testFlag === false || $testFlag !== '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $rewardArr = array();
        if ($flag == 1) {
            $this->_userModel->hSet('newer_test', 1);
            $rewardArr = array(
                array('id' => 7901, 'num' => 300),
                array('id' => 7902, 'num' => 100),
                array('id' => 4002, 'num' => 5),
                array('id' => 3075, 'num' => 2),
            );
            foreach ($rewardArr as $item) {
                Bag::incrItem($item['id'], $item['num']);
            }
        } else {
            $this->_userModel->hSet('newer_test', 0);
        }
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 获取邀请好友奖励
     * @param int $order 邀请顺序(1-100)
     */
    public function getInviteReward($order) {
        if (!is_numeric($order) || $order < 0 || $order > 100 || intval($order) != $order) {
            $this->_data['msg'] = 'invalid num';
            return;
        }
        $inviteNum = $this->_userModel->hGet('invite_num');
        $inviteRewardNum = $this->_userModel->hGet('invite_reward_num');
        if ($inviteNum === false || $inviteRewardNum === false) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $inviteNum = min(100, $inviteNum); // 最大100
        if ($order > $inviteNum || $order <= $inviteRewardNum) {
            $this->_data['msg'] = 'bad request2';
            return;
        }
        $rewardConfigArr = Common::getInviteRewardConfig();
        $tempArr = array();
        for ($i = $inviteRewardNum + 1; $i <= $order; ++$i) {
            if (in_array($i, array(10, 30, 50, 80, 100))) {
                $key = $i;
            } else {
                $key = 1;
            }
            $itemArr = $rewardConfigArr[$key];
            foreach ($itemArr as $item) {
                $itemId = $item['id'];
                $num = $item['num'];
                if (isset($tempArr[$itemId])) {
                    $tempArr[$itemId] += $num;
                } else {
                    $tempArr[$itemId] = $num;
                }
            }
        }
        foreach ($tempArr as $itemId => $num) {
            Bag::incrItem($itemId, $num);
        }
        $this->_userModel->hSet('invite_reward_num', $order);
        $this->_ret = 0;
    }

    /**
     * 获取问卷调查的奖励
     * @param int $id 活动的编号
     */
    public function getActivityReward($id) {
        switch ($id) {
            case '1': // 问卷调查奖励
                // 奶酥包*5、甜甜圈*2、木板*5、1000金币
                $rewardArr = array(
                    array('id' => 4001, 'num' => 5),
                    array('id' => 4002, 'num' => 2),
                    array('id' => 3002, 'num' => 5),
                    array('id' => 7901, 'num' => 1000),
                );
                $feedbackSQLModel = new FeedbackSQLModel();
                $whereArr = array(
                    'user_id' => $this->_userId,
                );
                $count = $feedbackSQLModel->SH()->find($whereArr)->count();
                if ($count < 1) {
                    $this->_data['msg'] = 'already get';
                    return;
                }
                $feedbackSQLModel->SH()->find($whereArr)->delete();
                foreach ($rewardArr as $item) {
                    Bag::incrItem($item['id'], $item['num']);
                }
                $this->_data['reward'] = $rewardArr;
                break;
            default:
                $this->_data['msg'] = 'unknwon id';
                return;
        }
        $this->_ret = 0;
    }

    /**
     * 七夕活动奖励
     * @param int $id index
     */
    function getRoseReward($id) {
        $configArr = array(
            0 => array(
                'need' => array(7108 => 1),
                'reward' => array(7901 => 100, 4001 => 1, 3002 => 1),
            ),
            1 => array(
                'need' => array(7108 => 1, 7109 => 1),
                'reward' => array(7901 => 300, 4001 => 4, 3002 => 2),
            ),
            2 => array(
                'need' => array(7108 => 10, 7109 => 7),
                'reward' => array(7901 => 3000, 4002 => 2, 3075 => 5),
            ),
            3 => array(
                'need' => array(7108 => 34, 7109 => 17),
                'reward' => array(7901 => 10000, 4002 => 7, 3078 => 2, 3004 => 10),
            ),
            4 => array(
                'need' => array(7108 => 66, 7109 => 33),
                'reward' => array(7901 => 30000, 4002 => 15, 3064 => 2, 3075 => 10),
            ),
        );
        if (!isset($configArr[$id])) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $needArr = $configArr[$id]['need'];
        $rewardArr = $configArr[$id]['reward'];
        foreach ($needArr as $itemId => $num) {
            $curNum = Bag::getItemNum($itemId);
            if ($curNum === false || $curNum < $num) {
                $this->_data['msg'] = 'not enough item';
                return;
            }
        }
        foreach ($needArr as $itemId => $num) {
            Bag::decrItem($itemId, $num);
        }
        foreach ($rewardArr as $itemId => $num) {
            Bag::incrItem($itemId, $num);
        }
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 兑换疑问号
     */
    public function exchangeQuestionMark() {
        $itemId = 99999;
        $num = Bag::getItemNum($itemId);
        if ($num === false || $num <= 0) {
            $this->_data['msg'] = 'not enough item';
            return;
        }
        if (Bag::decrItem($itemId, 1) === false) {
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        $rewardArr = Common::getQuestionRewardConfig();
        if ($rewardArr === false) {
            $this->_data['msg'] = 'cannot get Question reward';
            return;
        }
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }

    /**
     * 人人网数据迁移奖励
     */
    public function getAccountMoveReward() {
        if ($this->_userModel->hGet('account_moved', false) !== false) {
            $this->_data['msg'] = 'account already moved';
            return;
        }
        $snsUid = $this->_userModel->hGet('sns_uid', false);
        if ($snsUid === false) {
            $this->_data['msg'] = 'use not exists';
            return;
        }
        $obj = new RenrenDataMove();
        $retArr = $obj->query($snsUid);
        if ($retArr['ret'] != 0) {
            $this->_data['msg'] = $retArr['msg'];
            $this->_ret = 0;
            return;
        }
        if (!$obj->checkSign($retArr['data'])) {
            $this->_data['msg'] = 'sign not match';
            return;
        }
        $grade = $retArr['data']['grade'];
        $accountMove = $retArr['data']['account_move'];
        if ($accountMove > 0) {
            $this->_data['msg'] = 'account already moved';
            return;
        }

        $retArr2 = $obj->update($snsUid);
        if ($retArr2['ret'] != 0) {
            $this->_data['msg'] = $retArr['msg'];
            return;
        }

        if (!$obj->checkSign($retArr2['data'])) {
            $this->_data['msg'] = 'sign not match';
            return;
        }
        if ($retArr2['data']['account_move'] != 1) {
            $this->_data['msg'] = 'account already moved';
            return;
        }
        if ($this->_userModel->hSetNx('account_moved', 1) === false) {
            $this->_data['msg'] = 'hSetNx fail';
            return;
        }
        $rewardArr = $obj->getReward($grade);
        $tempArr = array();
        foreach ($rewardArr as $itemId => $num) {
            Bag::incrItem($itemId, $num);
            $tempArr[] = array('id' => $itemId, 'num' => $num);
        }
        $this->_data['reward'] = $tempArr;
        $this->_ret = 0;
    }
}
?>
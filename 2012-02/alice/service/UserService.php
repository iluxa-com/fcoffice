<?php
/**
 * 用户服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class UserService extends Service {
    /**
     * 许愿道具最大数
     * @var int
     */
    const WISH_ITEM_MAX_NUM = 5;
    /**
     * 索求礼物最大次数
     * @var int
     */
    const REQUEST_GIFT_MAX_TIMES = 30;
    /**
     * 每天送礼最大次数
     * @var int
     */
    const SEND_GIFT_MAX_TIMES = 30;
    /**
     * 角色最大等级
     * @var int
     */
    const MAX_GRADE = 100;
    /**
     * 礼物日志最大有效时间(秒)
     * @var int
     */
    const GIFT_LOG_MAX_VALID_SECONDS = 86400;

    /**
     * 更新性别(只能更新一次)
     * @param int $gender 0=女孩,1=男孩
     */
    public function updateGender($gender = NULL) {
        if (!in_array($gender, array(0, 1))) {
            $this->_data['msg'] = 'bad gender';
            return;
        }
        $oldGender = $this->_userModel->hGet('gender');
        if ($oldGender == -1) { // -1表示未设置性别
            if ($gender == 0) { // 女孩
                $dressDataArr = array(
                    'head' => 1000, // 头饰
                    'body_up' => 1001, // 上衣
                    'hand' => 1002, // 手饰
                    'socks' => 1003, // 袜子
                    'body_down' => 1004, // 裙裤
                    'foot' => 1005, // 鞋子
                    'other' => 1006, // 其他
                );
            } else { // 男孩
                $dressDataArr = array(
                    'head' => 1007, // 头饰
                    'body_up' => 1008, // 上衣
                    'hand' => 1009, // 手饰
                    'socks' => 1010, // 袜子
                    'body_down' => 1011, // 裙裤
                    'foot' => 1012, // 鞋子
                    'other' => 1013, // 其他
                );
            }
            $dressModel = new DressModel();
            if ($dressModel->hMset($dressDataArr) === false) {
                $this->_data['msg'] = 'hMset error';
                return;
            }
            $this->_userModel->hSet('gender', $gender);
        }
        $this->_ret = 0;
    }

    /**
     * 更新称号
     * @param int $titleId 称号ID
     */
    public function updateTitle($titleId = NULL) {
        if (!is_numeric($titleId)) {
            $this->_data['msg'] = 'invalid title id';
            return;
        }
        $configArr = Common::getTitleConfig($titleId);
        if ($configArr === false) { // 未获取到
            $this->_data['msg'] = 'get title config error';
            return;
        }
        $creditModel = new CreditModel();
        $times = $creditModel->hGet($configArr['ref'], false);
        if ($times === false || $times < $configArr['times']) { // 荣誉不够
            $this->_data['msg'] = 'times is less need';
            return;
        }
        $this->_userModel->hSet('title', $titleId);
        $this->_ret = 0;
    }

    /**
     * 升级
     */
    public function gradeUp() {
        $lastExp = $this->_userModel->hGet('last_exp', false);
        if ($lastExp === false) { // 上一次升级后的经验获取失败
            $this->_data['msg'] = 'get last exp fail';
            return;
        }
        $lastGrade = Common::getGradeByExp($lastExp); // 上一次升级后的等级
        if ($lastGrade === false || $lastGrade >= self::MAX_GRADE) { // 已达到最高级
            $this->_data['msg'] = 'reach max level';
            return;
        }
        $thisGrade = $lastGrade + 1;
        $curExp = $this->_userModel->hGet('exp', false);
        if ($curExp === false) { // 经验获取失败
            $this->_data['msg'] = 'get exp fail';
            return;
        }
        $needExp = Common::getExpByGrade($thisGrade); // 升级所需的经验
        if ($needExp === false || $curExp < $needExp || $curExp <= $lastExp) { // 判断经验
            $this->_data['msg'] = 'not enough exp';
            return;
        }
        $dataArr = array(
            'last_exp' => $curExp,
            'energy_time' => 0, // 体力最后同步时间(设置为0表示满体力)
        );
        if ($this->_userModel->hMset($dataArr) === false) { // 更新失败
            $this->_data['msg'] = 'last exp update fail';
            return;
        }
        $rewardArr = Common::getGradeUpReward($thisGrade); // 获取升级奖励
        $reward2Arr = Common::getGradeUpReward($thisGrade + 1); // 获取升下一级的奖励
        if ($rewardArr === false || $reward2Arr === false) {
            $this->_data['msg'] = 'get grade up reward error';
            return;
        }
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_data = array(
            'reward' => $rewardArr,
            'next_reward' => $reward2Arr,
        );
        $this->_ret = 0;
    }

    /**
     * 重置闯关次数
     */
    public function resetLevelCount() {
        $itemId = 6999;
        if (Bag::decrItem($itemId, 1) === false) {
            $this->_data['msg'] = 'not enough item';
            return;
        }
        $dataArr = array(
            'level_success' => 0,
            'level_fail' => 0,
        );
        if ($this->_userModel->hMset($dataArr) === false) {
            Bag::incrItem($itemId, 1);
            $this->_data['msg'] = 'hMset fail';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 在线报告
     */
    public function onlineReport() {
        $onlineTime = $this->_userModel->hIncrBy('online_time', 1);
        $this->_data['online_time'] = $onlineTime;
        $this->_ret = 0;
    }

    /**
     * 发布许愿
     * @param array $itemIdArr 道具ID数组(0~5个)
     */
    public function pubWish($itemIdArr = NULL) {
        // 暂时屏蔽
        $this->_ret = 0;
        return;
        if (!is_array($itemIdArr)) { // 可以为空数组
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!empty($itemIdArr)) {
            // 移除重复的元素
            $itemIdArr = array_unique($itemIdArr);
            if (count($itemIdArr) > self::WISH_ITEM_MAX_NUM) {
                $this->_data['msg'] = 'too many item id';
                return;
            }
            foreach ($itemIdArr as $itemId) {
                if (!is_numeric($itemId)) {
                    $this->_data['msg'] = 'invalid item id';
                    return;
                }
            }
        }
        if ($this->_userModel->hSet('wish_items', implode(',', $itemIdArr)) === false) {
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 索求礼物(只能是免费礼物)
     * @param int $friendUserIdArr 好友ID数组
     * @param int $itemId 道具ID
     */
    public function requestGift($friendUserIdArr = NULL, $itemId = NULL) {
        if (!is_array($friendUserIdArr) || empty($friendUserIdArr)) {
            $this->_data['msg'] = 'no friend user id';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        $requestModel = new RequestModel();
        $requestNum = $requestModel->sCard();
        if ($requestNum === false || $requestNum + count($friendUserIdArr) > self::REQUEST_GIFT_MAX_TIMES) { // 判断已索求次数
            $this->_data['msg'] = 'too many friend user id';
            return;
        }
        $failNum = 0;
        foreach ($friendUserIdArr as $friendUserId) {
            if ($requestModel->sAdd($friendUserId) === false) { // 添加到集合失败,跳过
                ++$failNum;
                continue;
            }
            $dataArr = array(
                'from_user_id' => $this->_userId,
                'item_id' => $itemId,
            );
            User::log(User::LOG_TYPE_REQUEST_GIFT, $dataArr, $friendUserId); // 写好友日志(索求礼物)
        }
        $this->_data['fail'] = $failNum;
        $this->_ret = 0;
    }

    /**
     * 给礼物(只能是免费礼物)
     * @param int $uid 唯一ID
     */
    public function giveGift($uid = NULL) {
        if (!is_numeric($uid)) {
            $this->_data['msg'] = 'bad uid';
            return;
        }
        $requestSQLModel = new RequestGiftSQLModel();
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
        );
        $dataArr = $requestSQLModel->SH()->find($whereArr)->getOne();
        if ($dataArr === false) { // 没有取到?
            $this->_data['msg'] = 'not exists';
            return;
        }
        if ($dataArr['create_time'] + self::GIFT_LOG_MAX_VALID_SECONDS < CURRENT_TIME) { // 日志过期了
            $this->_data['msg'] = 'log expired';
            return;
        }
        $itemId = $dataArr['item_id'];
        if (Bag::incrItem($itemId, 1, $dataArr['from_user_id']) === false) { // 添加到好友背包
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $requestSQLModel->SH()->find($whereArr)->delete(); // 删除
        // 日志数据
        $contentArr = array(
            'from_user_id' => $this->_userId,
        );
        $logDataArr = array(
            'log_type' => 3, // 好友给了索求礼物
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $logDataArr, $dataArr['from_user_id']); // 写好友日志(给了好友索求的礼物)
        $this->_ret = 0;
    }

    /**
     * 赠送礼物
     * @param int $type 赠送类型(0=免费礼物,1=背包道具)
     * @param int $friendUserIdArr 好友用户ID数组
     * @param int $itemId 道具ID
     * @param int $num 数量(免费礼物数量固定为1)
     */
    public function sendGift($type = NULL, $friendUserIdArr = NULL, $itemId = NULL, $num = NULL) {
        if (!in_array($type, array(0, 1))) {
            $this->_data['msg'] = 'invalid type';
            return;
        }
        if (!is_array($friendUserIdArr) || empty($friendUserIdArr)) {
            $this->_data['msg'] = 'friend id too much or too less';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        if (in_array($this->_userId, $friendUserIdArr)) { // 不能包含自己
            $this->_data['msg'] = 'bad request';
            return;
        }
        if ($type == 0) {
            if (Common::isFreeGift($itemId)) {
                $this->__sendFreeGift($friendUserIdArr, $itemId);
            } else {
                $this->_data['msg'] = 'is not a free gift';
                return;
            }
        } else {
            if (!is_numeric($num)) {
                $this->_data['msg'] = 'invalid num';
                return;
            }
            if (count($friendUserIdArr) !== 1) { // 限定一次只能向一个好友赚送自己的东西
                $this->_data['msg'] = 'too many friend user id';
                return;
            }
            $this->__sendBagGift($friendUserIdArr[0], $itemId, $num);
        }
    }

    /**
     * 接受礼物(只能是免费礼物)
     * @param int $uid 唯一ID
     */
    public function acceptGift($uid = NULL) {
        if (!is_numeric($uid)) {
            $this->_data['msg'] = 'bad uid';
            return;
        }
        $sendGiftSQLModel = new SendGiftSQLModel();
        $whereArr = array(
            'uid' => $uid,
            'user_id' => $this->_userId,
        );
        $dataArr = $sendGiftSQLModel->SH()->find($whereArr)->getOne();
        if ($dataArr === false) { // 没取到?
            $this->_data['msg'] = 'not exists';
            return;
        }
        if ($dataArr['log_type'] !== '0') { // 不是免费礼物
            $this->_data['msg'] = 'log type not match';
            return;
        }
        if ($dataArr['create_time'] + self::GIFT_LOG_MAX_VALID_SECONDS < CURRENT_TIME) { // 日志过期了
            $this->_data['msg'] = 'log expired';
            return;
        }
        $itemId = $dataArr['item_id'];
        $num = $dataArr['num'];
        if (Bag::incrItem($itemId, $num) === false) { // 添加到背包
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $sendGiftSQLModel->SH()->find($whereArr)->delete();
        // 更新每日任务进度
        User::updateDayTask(10);
        $this->_ret = 0;
    }

    /**
     * 领取新手礼包
     * @param int $grade 等级
     */
    public function getNoviceGift($grade) {
        $curGrade = Common::getGradeByExp($this->_userModel->hGet('exp'));
        if ($grade > $curGrade) { // 等级不够
            $this->_data['msg'] = 'grade too lower';
            return;
        }
        $configArr = Common::getNoviceGiftReward($grade);
        if ($configArr === false) {
            $this->_data['msg'] = 'cannot get novice gift';
            return;
        }
        $noviceGiftModel = new NoviceGiftModel();
        $val = $noviceGiftModel->getBit($configArr['offset']);
        if ($val == 1) {
            $this->_data['msg'] = 'already open';
            return;
        }
        if ($noviceGiftModel->setBit($configArr['offset'], 1) !== false) {
            foreach ($configArr['items'] as $item) {
                Bag::incrItem($item['id'], $item['num']);
            }
        }
        $this->_ret = 0;
    }

    /**
     * 获取好友列表
     */
    public function refreshFriend() {
        $dataArr['friend_list'] = User::getFriends(false);
        $this->_data = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 签到
     */
    public function signIn() {
        // 今天00:00:00
        $today = strtotime('today', CURRENT_TIME);
        // 昨天00:00:00
        $yesterday = $today - 86400;
        // 最后签到的时间
        $lastSign = $this->_userModel->hGet('last_sign', 0);
        if ($lastSign >= $today) { // 今天已经签到
            $this->_data['msg'] = 'already sign';
            return;
        }
        // 更新最后签到时间
        if ($this->_userModel->hSet('last_sign', CURRENT_TIME) === false) {
            $this->_data['msg'] = 'update last sign fail';
            return;
        }
        if ($lastSign >= $yesterday) { // 昨天有签到,签到次数+1
            $signTimes = $this->_userModel->hIncrBy('sign_times', 1);
            if ($signTimes === false) {
                $this->_userModel->hSet('last_sign', $lastSign); // 最后签后时间回滚
                $this->_data['msg'] = 'hIncrBy fail';
                return;
            }
            if ($signTimes >= 12) { // 连续签到12次+,重置签到次数
                $signTimes = 12;
                if ($this->_userModel->hSet('sign_times', 1) === false) {
                    $this->_userModel->hIncrBy('sign_times', -1); // 签到次数回滚
                    $this->_userModel->hSet('last_sign', $lastSign); // 最后签后时间回滚
                    $this->_data['msg'] = 'hSet fail2';
                    return;
                }
            }
        } else { // 昨天没签到,重置签到次数
            $signTimes = 1;
            if ($this->_userModel->hSet('sign_times', 1) === false) {
                $this->_userModel->hSet('last_sign', $lastSign); // 最后签后时间回滚
                $this->_data['msg'] = 'hSet fail';
                return;
            }
        }
        // 奖励处理
        $rewardArr = Common::getSignReward($signTimes);
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_ret = 0;
    }

    /**
     * 赠送免费礼物
     * @param int $friendUserIdArr 好友用户ID数组
     * @param int $itemId 道具ID
     */
    private function __sendFreeGift($friendUserIdArr, $itemId) {
        // 今天00:00:00
        $today = strtotime('today', CURRENT_TIME);
        // 上一次登录时间
        $lastLogin = $this->_userModel->hGet('last_login');
        if ($lastLogin < $today) {
            $this->_data['msg'] = 'please relogin';
            return;
        }
        $sendModel = new SendModel();
        $sendNum = $sendModel->sCard();
        if ($sendNum === false || $sendNum + count($friendUserIdArr) > self::SEND_GIFT_MAX_TIMES) { // 判断已发送次数
            $this->_data['msg'] = 'too many friend user id';
            return;
        }
        $failNum = 0;
        foreach ($friendUserIdArr as $friendUserId) {
            if ($sendModel->sAdd($friendUserId) === false) { // 添加到集合失败,跳过
                ++$failNum;
                continue;
            }
            // 日志数据
            $dataArr = array(
                'from_user_id' => $this->_userId, // 此用户ID为礼物送出人的用户ID,
                'log_type' => 0, // 免费礼物
                'item_id' => $itemId,
                'num' => 1,
            );
            User::log(User::LOG_TYPE_SEND_GIFT, $dataArr, $friendUserId); // 写好友日志(发送免费礼物)
        }
        // 更新每日任务进度
        User::updateDayTask(1, count($friendUserIdArr) - $failNum);
        $this->_data['fail'] = $failNum;
        $this->_ret = 0;
    }

    /**
     * 赠送背包礼物
     * @param int $friendUserId 好友用户ID
     * @param int $itemId 道具ID
     * @param int $num 数量
     */
    private function __sendBagGift($friendUserId, $itemId, $num) {
        // 暂时屏蔽
        $this->_data['msg'] = 'api disabled';
        return;
        $curNum = Bag::getItemNum($itemId);
        if ($curNum === false || $curNum < $num) { // 数量不足
            $this->_data['msg'] = 'not enough num';
            return;
        }
        if (Bag::decrItem($itemId, $num) === false) { // 从自己的背包扣除失败
            $this->_data['msg'] = 'unknown error';
            return;
        }
        if (Bag::incrItem($itemId, $num, $friendUserId) === false) { // 往好友的背包添加物品失败
            Bag::incrItem($itemId, $num); // 回滚
            $this->_data['msg'] = 'unknown error2';
            return;
        }
        // 日志数据
        $dataArr = array(
            'from_user_id' => $this->_userId, // 此用户ID为礼物送出人的用户ID,
            'log_type' => 1, // 背包礼物
            'item_id' => $itemId,
            'num' => $num,
        );
        User::log(User::LOG_TYPE_SEND_GIFT, $dataArr, $friendUserId); // 写好友日志(发送背包道具)
        // 更新每日任务进度
        User::updateDayTask(1);
        $this->_ret = 0;
    }
}
?>
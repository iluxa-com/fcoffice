<?php
/**
 * 家服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class HomeService extends Service {
    /**
     * 执行升级
     * @param string $hashKey 装饰名称
     */
    public function doLevelUp($hashKey = NULL) {
        switch ($hashKey) {
            case 'house': // 房子
                $this->__commonLevelUp($hashKey, 10, 28800);
                if ($this->_ret == 0) {
                    $this->_data['effect'] = Effect::updateFixedEffect();
                }
                break;
            case 'board': // 告示板
                break;
            case 'postbox': // 邮箱
                break;
            case 'tree': // 魔法果树
                $this->__commonLevelUp($hashKey, 8, 28800);
                break;
            case 'horse': // 马车
                $this->__commonLevelUp($hashKey, 8, 28800);
                break;
            case 'honeybee': // 魔法蜂巢
                $this->__commonLevelUp($hashKey, 8, 28800);
                break;
            case 'workshop': // 精灵工坊
                $this->__commonLevelUp($hashKey, 10, 28800);
                break;
            case 'farm': // 磨坊农田
                $this->__commonLevelUp($hashKey, 8, 28800);
                break;
            case 'trade': // 摊位
                break;
            case 'spirit': // 精灵屋
                $this->__commonLevelUp($hashKey, 7, 28800);
                break;
            case 'totem': // 图腾柱
                break;
            case 'pet': // 宠物屋
                break;
            case 'hole': // 树洞
                break;
            case 'radio': // 收音机
                break;
            default :
                $this->_data['msg'] = 'invalid hash key';
                return;
                break;
        }
    }

    /**
     * 从魔法果树上摘果子
     */
    public function pickFruit() {
        $hashKey = 'tree';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if ($jsonArr['level'] < 1) { // 等级必须>=1
            $this->_data['msg'] = 'level must >= 1';
            return;
        }
        // 上一次摘取时间
        $lastPick = $jsonArr['last_pick'];
        // 果树等级
        $curLevel = $jsonArr['level'];
        $configArr = Common::getTreeConfig($curLevel);
        if ($configArr === false) { // 配置数据找不到
            $this->_data['msg'] = 'config data not found';
            return;
        }
        $cd = $configArr['cd'];
        $max = $configArr['max'];
        if (CURRENT_TIME - $lastPick < $cd) { // 还在CD
            $this->_data['msg'] = 'not cool down';
            return;
        } else if (CURRENT_TIME - $lastPick > $cd * $max) {
            $lastPick = CURRENT_TIME - $cd * $max;
        }
        $rewardArr = Common::getFruitRandReward($curLevel);
        if ($rewardArr === false) { // 配置数据错误
            $this->_data['msg'] = 'reward data error';
            return;
        }
        foreach ($rewardArr as $item) { // 给奖励
            Bag::incrItem($item['id'], $item['num']);
        }
        $lastPick += $cd;
        $jsonArr['last_pick'] = $lastPick; // 设置最后摘取时间
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) { // 更新失败
            foreach ($rewardArr as $item) { // 奖励回滚
                Bag::decrItem($item['id'], $item['num']);
            }
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $this->_data = array(
            'reward' => $rewardArr,
            'last_pick' => $lastPick,
        );
        $this->_ret = 0;
    }

    /**
     * 照料魔法果树
     * @param int $friendUserId 好友用户ID
     */
    public function tendTree($friendUserId) {
        if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        }
        if ($friendUserId == $this->_userId) { // 不能是自己
            $this->_data['msg'] = 'cannot self';
            return;
        }
        // 每天第一次访问好友奖励处理
        $num = $this->__addToVisitList($friendUserId);
        if ($num !== false) {
            if ($num <= 20) {
                Bag::incrItem(Common::ITEM_SILVER, 5);
                Bag::incrItem(Common::ITEM_EXP, 10);
                Bag::incrItem(Common::ITEM_ENERGY, 1);
            }
            if ($num == 1) {
                // 称号奖励处理
                $titleId = $this->_userModel->hGet('title');
                if (in_array($titleId, array(9, 10, 11, 12, 13, 14, 15, 16))) {
                    User::giveTitleReward($titleId);
                }
            }
            $homeModel = new HomeModel($friendUserId);
            $jsonStr = $homeModel->hGet('tree');
            if ($jsonStr !== false) {
                $jsonArr = json_decode($jsonStr, true);
                if ($jsonArr !== NULL) {
                    $jsonArr['last_pick'] -= 60;
                    $homeModel->hSet('tree', json_encode($jsonArr));
                }
            }
        }
        $this->_ret = 0;
    }

    /**
     * 摘取强力胶
     */
    public function pickSeccotine() {
        $hashKey = 'honeybee';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if ($jsonArr['level'] < 1) { // 等级必须>=1
            $this->_data['msg'] = 'level must >= 1';
            return;
        }
        $configArr = Common::getTreeConfig($jsonArr['level']);
        if ($configArr === false) { // 配置数据找不到
            $this->_data['msg'] = 'config data not found';
            return;
        }
        $cd = 10800;
        $max = $configArr['max'];
        if (CURRENT_TIME - $jsonArr['last_pick'] < $cd) { // 还在CD
            $this->_data['msg'] = 'already in cd';
            return;
        }
        if (Bag::incrItem(7205, $max) === false) {
            $this->_data['msg'] = 'incr item fail';
            return;
        }
        $jsonArr['last_pick'] = CURRENT_TIME; // 设置最后摘取时间
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) { // 更新失败
            Bag::decrItem(7205, $max);
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $this->_data = array(
            'last_pick' => $jsonArr['last_pick'],
        );
        $this->_ret = 0;
    }

    /**
     * 种植作物
     * @param int $cropId 作物编号
     */
    public function plantCrop($cropId) {
        $growTime = Common::getCropTimeConfig($cropId);
        if ($growTime === false) {
            $this->_data['msg'] = 'invalid crop id';
            return;
        }
        $hashKey = 'farm';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if ($jsonArr['level'] == 0) { // 1级才可以种植
            $this->_data['msg'] = 'level must >= 1';
            return;
        }
        if (!empty($jsonArr['land'])) { // 土地不为空
            $this->_data['msg'] = 'land not empty';
            return;
        }
        $jsonArr['land'] = array(
            'level' => $jsonArr['level'], // 作物种植时农田的等级
            'crop_id' => $cropId, // 作物编号
            'time' => time(), // 播种时间
        );
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) { // 更新失败
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $this->_data = $jsonArr['land'];
        $this->_ret = 0;
    }

    /**
     * 收获作物
     */
    public function harvestCrop() {
        $hashKey = 'farm';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (empty($jsonArr['land'])) { // 土地为空
            $this->_data['msg'] = 'land empty';
            return;
        }
        $level = $jsonArr['land']['level'];
        $cropId = $jsonArr['land']['crop_id'];
        $plantTime = $jsonArr['land']['time'];
        $growTime = Common::getCropTimeConfig($cropId);
        if ($growTime === false) { // 无效的作物ID?
            $this->_data['msg'] = 'invalid crop id';
            return;
        }
        if (CURRENT_TIME - $plantTime < $growTime) { // 作物尚未成熟
            $this->_data['msg'] = 'not ripe';
            return;
        }
        $configArr = Common::getFarmConfig($level);
        if ($configArr === false) { // 农田数据配置错误
            $this->_data['msg'] = 'farm config data error';
            return;
        }
        unset($jsonArr['land']); // 清空土地
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) { // 更新失败
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $item = $configArr['result'][$cropId];
        if (Bag::incrItem($item['id'], $item['num']) === false) { // 奖励进背包失败
            $homeModel->hSet($hashKey, $jsonStr); // 失败回滚
            $this->_data['msg'] = 'incr item fail';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 马车贸易
     * @param int $typeId 贸易类型
     * @param int $friendUserId 朋友id
     */
    public function trade($typeId, $friendUserId) {
        //检查贸易时间
        if (!in_array($typeId, array(1, 2, 3, 4, 5, 6))) {
            $this->_data['msg'] = 'invalid trade type';
            return;
        }
        //检查朋友id
        if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'invalid friend id';
            return;
        }
        if ($friendUserId == $this->_userId) { // 不能为自己
            $this->_data['msg'] = 'cannot trade with self';
            return;
        }
        //检查马房
        $homeModel = new HomeModel();
        $horseStr = $homeModel->hGet('horse', false);
        if (false === $horseStr) { //马房信息检查
            $this->_data['msg'] = 'get horse info error';
            return;
        }
        $horseArr = json_decode($horseStr, true);
        if (NULL === $horseArr) {
            $this->_data['msg'] = 'json decode error';
            return;
        }
        //0级不允许贸易
        if ($horseArr['level'] < 1) {
            $this->_data['msg'] = 'horse level too low';
            return;
        }
        if (isset($horseArr['trade_info'])) { //正在贸易
            $this->_data['msg'] = 'trade still go on';
            return;
        }
        $horseTradeModel = new HorseTradeModel();
        if (false === $horseTradeModel->sAdd($friendUserId)) {
            $this->_data['msg'] = 'trade friend add error or already traded with this friend today';
            return;
        }
        //取朋友等级
        $friendUserModel = new UserModel($friendUserId);
        $friendUserExp = $friendUserModel->hGet('exp', false);
        if (false === $friendUserExp) {
            $this->_data['msg'] = 'cannot get friend exp';
            $this->_ret = UserException::ERROR_USER_NOT_EXISTS;
            return;
        }
        $friendUserGrade = Common::getGradeByExp($friendUserExp);
        $rewardSilver = Common::getHorseTradeReward($horseArr['level'], $typeId, $friendUserGrade);
        if (false === $rewardSilver) {
            $this->_data['msg'] = 'calculate reward fail';
            return;
        }
        $horseArr['trade_info'] = array(
            'friend_user_id' => $friendUserId, //朋友id，贸易完成后记录日志使用
            'start_time' => time(), // 贸易开始时间
            'type' => $typeId, //贸易类型
            'reward' => $rewardSilver, //贸易奖励金币
        );
        if (false === $homeModel->hSet('horse', json_encode($horseArr))) {
            $horseTradeModel->sRemove($friendUserId);
            $this->_data['msg'] = 'trade info update error';
            return;
        }
        $this->_data['start_time'] = $horseArr['trade_info']['start_time'];
        $this->_ret = 0;
    }

    /**
     * 取得马车贸易收益
     */
    public function getHorseTradeReward() {
        $homeModel = new HomeModel();
        $horseStr = $homeModel->hGet('horse', false);
        if (false === $horseStr) { //马房信息检查
            $this->_data['msg'] = 'get horse info error';
            return;
        }
        $horseArr = json_decode($horseStr, true);
        if (NULL === $horseArr) {
            $this->_data['msg'] = 'json decode error';
            return;
        }
        if (!isset($horseArr['trade_info'])) {
            $this->_data['msg'] = 'trade not exists';
            return;
        }
        $time = Common::getHorseTradeTime($horseArr['trade_info']['type']);
        if ($horseArr['trade_info']['start_time'] + $time > CURRENT_TIME) { // 尚未到时间
            $this->_data['msg'] = 'trade still going on';
            return;
        }
        //加金币
        if (false === Bag::incrItem(Common::ITEM_SILVER, $horseArr['trade_info']['reward'])) {
            $this->_data['msg'] = 'incr silver failed';
            return;
        }
        $tradeInfoArr = $horseArr['trade_info'];
        unset($horseArr['trade_info']); // 删除贸易信息
        if (false === $homeModel->hSet('horse', json_encode($horseArr))) { //更新马房数据
            Bag::decrItem(Common::ITEM_SILVER, $tradeInfoArr['reward']); //回滚
            $this->_data['msg'] = 'trade info update failed';
            return;
        }
        // 写日志
        $contentArr = array(
            'from_user_id' => $tradeInfoArr['friend_user_id'],
            'start_time' => $tradeInfoArr['start_time'],
            'type' => $tradeInfoArr['type'],
            'reward' => $tradeInfoArr['reward'],
        );
        $logDataArr = array(
            'log_type' => 8, //领取贸易收益
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $logDataArr); // 领取贸易收益(自己)
        $contentArr = array(
            'from_user_id' => $this->_userId,
            'start_time' => $tradeInfoArr['start_time'],
            'type' => $tradeInfoArr['type'],
            'reward' => $tradeInfoArr['reward'],
        );
        $logDataArr = array(
            'log_type' => 9, //被贸易
            'content' => json_encode($contentArr),
        );
        User::log(User::LOG_TYPE_NEWS, $logDataArr, $tradeInfoArr['friend_user_id']); // 写入马车贸易日志 被贸易方
        $this->_data['reward'] = $tradeInfoArr['reward'];
        $this->_ret = 0;
    }

    /**
     * 添加到访问列表
     * @param int $friendUserId 好友的用户ID
     * @return int/false
     */
    private function __addToVisitList($friendUserId) {
        if ($friendUserId == $this->_userId) { // 如果是自己,返回false
            return false;
        }
        // 今天00:00:00
        $today = strtotime('today', CURRENT_TIME);
        // 上一次登录时间
        $lastLogin = $this->_userModel->hGet('last_login');
        if ($lastLogin < $today) { // 需要重新登录,抛异常
            throw new UserException(UserException::ERROR_NEED_RELOGIN, 'NEED_RELOGIN');
        }
        $visitModel = new VisitModel();
        if ($visitModel->sAdd($friendUserId) === false) {
            return false;
        }
        User::updateCredit(User::CREDIT_VISIST_FRIEND, 1);
        return $visitModel->sCard();
    }

    /**
     * 通用升级处理
     * @param string $hashKey 装饰名称(house/tree/honeybee/farm)
     * @param int $maxLevel 装饰最高等级
     * @param int $levelUpCD 升级冷却时间(NULL表示无冷却时间限制)
     */
    private function __commonLevelUp($hashKey, $maxLevel, $levelUpCD = NULL) {
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        $curLevel = $jsonArr['level'];
        if ($curLevel >= $maxLevel) { // 判断是否已达到最高级
            $this->_data['msg'] = 'already reach max level';
            return;
        }
        if ($levelUpCD !== NULL && isset($jsonArr['last_level_up']) && CURRENT_TIME - $jsonArr['last_level_up'] < $levelUpCD) { // 升级还在冷却中
            $this->_data['msg'] = 'not cool down';
            return;
        }
        switch ($hashKey) {
            case 'house':
                $configArr = Common::getHouseConfig($curLevel + 1);
                break;
            case 'tree':
                $configArr = Common::getTreeConfig($curLevel + 1);
                break;
            case 'horse':
                $configArr = Common::getHorseConfig($curLevel + 1);
                break;
            case 'honeybee':
                $configArr = Common::getHoneybeeConfig($curLevel + 1);
                break;
            case 'workshop':
                $configArr = Common::getWorkshopConfig($curLevel + 1);
                // 判断精灵屋等级
                // $spiritStr = $homeModel->hGet('spirit', false);
                // if ($spiritStr === false) { // 没取到
                //    $this->_data['msg'] = 'hGet fail';
                //    return;
                // }
                // $spiritArr = json_decode($spiritStr, true);
                // if ($spiritArr === NULL) { // 解码失败
                //    $this->_data['msg'] = 'json decode fail';
                //    return;
                //}
                //if ($spiritArr['level'] < $configArr['spirit']) { // 精灵屋等级不够
                //    $this->_data['msg'] = 'spirit grade too lower';
                //    return;
                //}
                break;
            case 'farm':
                $configArr = Common::getFarmConfig($curLevel + 1);
                break;
            case 'spirit':
                $configArr = Common::getSpiritConfig($curLevel + 1);
                break;
        }
        if ($configArr === false) { // 数据配置错误
            $this->_data['msg'] = 'config data error';
            return;
        }
        $curGrade = Common::getGradeByExp($this->_userModel->hGet('exp'));
        if ($curGrade < $configArr['grade']) { // 等级不够
            $this->_data['msg'] = 'grade too lower';
            return;
        }
        foreach ($configArr['need'] as $item) { // 判断需要的道具数量是否够
            $curNum = Bag::getItemNum($item['id']);
            if ($curNum < $item['num']) { // 道具数量不足
                $this->_data['msg'] = 'not enough item';
                return;
            }
        }
        foreach ($configArr['need'] as $item) { // 扣除需要的道具
            Bag::decrItem($item['id'], $item['num']);
        }
        if ($hashKey === 'workshop') { // 精灵工坊升级特殊处理(解锁炉子)
            $stoveModel = new StoveModel();
            $stoveIdArr = array();
            for ($stoveId = 1; $stoveId <= $configArr['stoveId']; ++$stoveId) {
                if (!$stoveModel->hExists($stoveId)) { // 炉子未解锁
                    if ($stoveModel->hSetNx($stoveId, '{"id":' . $stoveId . '}') === FALSE) { // 解锁失败
                        foreach ($configArr['need'] as $item) { // 扣除道具回滚
                            Bag::incrItem($item['id'], $item['num']);
                        }
                        $this->_data['msg'] = 'unlock stove fail';
                        return;
                    }
                    $stoveIdArr[] = $stoveId;
                }
            }
        }
        ++$jsonArr['level']; // ++level
        if ($levelUpCD !== NULL) {
            $jsonArr['last_level_up'] = time(); // 设置最后升级时间
        }
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) { // 更新失败
            if ($hashKey === 'workshop') { // 精灵工坊失败时的特殊处理
                foreach ($stoveIdArr as $stoveId) { // 对上面解锁的炉子回滚
                    $stoveModel->hDel($stoveId);
                }
            }
            foreach ($configArr['need'] as $item) { // 扣除道具回滚
                Bag::incrItem($item['id'], $item['num']);
            }
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        if ($levelUpCD !== NULL) { // 返回最后升级时间
            $this->_data['last_level_up'] = $jsonArr['last_level_up'];
        }
        $this->_ret = 0;
    }

    /**
     * 刷新精灵
     * @param int $type 邀请方法(0=免费刷新,1=金币刷新)
     */
    public function refreshSpirit($type = NULL) {
        if (!in_array($type, array(0, 1))) {
            $this->_data['msg'] = "invalid type";
            return;
        }
        $hashKey = 'spirit';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if ($jsonArr['level'] < 1) { // 精灵屋等级太低
            $this->_data['msg'] = 'spirit level too low';
            return;
        }
        $configArr = Common::getSpiritConfig($jsonArr['level']);
        if ($configArr === false) {
            $this->_data['msg'] = 'fail to get spirit Config';
            return;
        }
        if ($type == 0) {
            if (isset($jsonArr['last_refresh']) && $jsonArr['last_refresh'] + 14400 > CURRENT_TIME) { // 刷新冷却中
                $this->_data['msg'] = 'not cool down';
                return;
            }
        } else {
            if (Bag::decrItem(Common::ITEM_SILVER, $configArr['silver']) === false) { // 扣自己的金币失败
                $this->_data['msg'] = 'decr silver fail';
                return;
            }
        }
        $spiritArr = Common::randSpirit($jsonArr['level']);
        $jsonArr['last_refresh'] = time(); // 设置最后刷新的时间
        $jsonArr['spirit'] = $spiritArr; // 精灵
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) {
            if ($type == 1) { // 钱回滚
                Bag::incrItem(Common::ITEM_SILVER, $configArr['silver']);
            }
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $this->_data = array(
            'last_refresh' => $jsonArr['last_refresh'],
            'spirit' => $spiritArr,
        );
        $this->_ret = 0;
    }

    /**
     * 邀请精灵
     * @param int $index 索引
     */
    public function inviteSpirit($index = NULL) {
        if (!in_array($index, array(0, 1, 2))) {
            $this->_data['msg'] = 'invalid index';
            return;
        }
        $hashKey = 'spirit';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            $this->_data['msg'] = 'hGet fail';
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (empty($jsonArr['spirit'])) { // 精灵数据为空
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (isset($jsonArr['spirit'][$index]['invited'])) { // 已经邀请过
            $this->_data['msg'] = 'already invited';
            return;
        }
        $jsonArr['active'] = $jsonArr['spirit'][$index]; // 当前已邀请的精灵
        $jsonArr['spirit'][$index]['invited'] = 1; // 设置邀请标志
        $jsonArr['last_invite'] = time(); // 设置最后邀请时间
        if ($homeModel->hSet($hashKey, json_encode($jsonArr)) === false) {
            $this->_data['msg'] = 'hSet fail';
            return;
        }
        $this->_data['last_invite'] = $jsonArr['last_invite'];
        $this->_ret = 0;
    }
}
?>
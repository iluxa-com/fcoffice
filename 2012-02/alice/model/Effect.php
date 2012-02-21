<?php
/**
 * Effect类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Effect {
    /**
     * 固定效果数组
     * @var array
     */
    private static $_fixedEffectArr = array();
    /**
     * 默认效果数组
     * @var array
     */
    private static $_defaultEffectArr = array(
        'e1' => 0, 'e2' => 0, 'e3' => 0, 'e4' => 0, 'e5' => 0,
        'e6' => 0, 'e7' => 0, 'e8' => 0, 'e9' => 0, 'e10' => 0,
        'e11' => 0, 'e12' => 0, 'e13' => 0, 'e14' => 0, 'e15' => 0,
    );

    /**
     * 更新固定效果(服饰,家装饰,道具加成)
     * @param int $userId 用户ID
     * @return array
     */
    public static function updateFixedEffect($userId = NULL) {
        if ($userId === NULL) {
            $userId = App::get('user_id');
        }
        // 初始效果数组
        $effectArr = self::$_defaultEffectArr;
        // 计算服饰的道具效果
//        $dressModel = new DressModel($userId);
//        $dressDataArr = $dressModel->hGetAll();
//        foreach ($dressDataArr as $hashKey => $itemId) {
//            self::__calcItemEffect($itemId, $effectArr);
//        }
        // 计算家装饰的道具效果
        $homeModel = new HomeModel($userId);
        $homeDataArr = $homeModel->hMget(array('house'));
        foreach ($homeDataArr as $hashKey => $jsonStr) {
            $jsonArr = json_decode($jsonStr, true);
            if ($jsonArr === NULL) {
                continue;
            }
            switch ($hashKey) {
                case 'house': // 体力上限增加（点）
                    if (isset($jsonArr['level'])) {
                        $effectArr['e1'] += Common::getHouseEnergy($jsonArr['level']);
                    }
                    break;
            }
        }
        // 计算道具加成的固定效果
        $fixedEffectModel = new FixedEffectModel($userId);
        $fixedEffectDataArr = $fixedEffectModel->hGetAll();
        foreach ($fixedEffectDataArr as $effectId => $val) {
            $effectArr[$effectId] += $val;
        }
        // 保存效果数据
        $userModel = App::getInst('UserModel', $userId, false, $userId);
        $ret = $userModel->hSet('effect', json_encode($effectArr));
        if ($ret != false) { // 设置成功
            self::$_fixedEffectArr[$userId] = $effectArr;
        }
        return $effectArr;
    }

    /**
     * 获取指定用户的固定效果
     * @param int $userId 用户ID
     * @return array
     */
    public static function getFixedEffect($userId = NULL) {
        if ($userId === NULL) {
            $userId = App::get('user_id');
        }
        if (!isset(self::$_fixedEffectArr[$userId])) {
            $userModel = App::getInst('UserModel', $userId, false, $userId);
            $jsonStr = $userModel->hGet('effect', false);
            if ($jsonStr === false) { // 没取到
                self::$_fixedEffectArr[$userId] = self::$_defaultEffectArr;
            } else {
                $jsonArr = json_decode($jsonStr, true);
                if ($jsonArr === NULL) { // 解码失败
                    self::$_fixedEffectArr[$userId] = self::$_defaultEffectArr;
                } else {
                    self::$_fixedEffectArr[$userId] = json_decode($jsonStr, true);
                }
            }
        }
        return self::$_fixedEffectArr[$userId];
    }

    /**
     * 计算单个道具的效果
     * @param int $itemId 道具ID
     * @param &array $effectArr 效果数组
     */
    private static function __calcItemEffect($itemId, &$effectArr) {
        $itemDataModel = new ItemDataModel($itemId);
        $jsonStr = $itemDataModel->hGet('effect', false);
        if ($jsonStr === false) { // 没取到
            return;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            return;
        }
        foreach ($jsonArr as $key => $val) {
            $effectArr[$key] += $val;
        }
    }

    /**
     * 获取效果名称
     * @return array
     */
    public static function getEffectName() {
        $configArr = array(
            'e1' => '体力上限增加（点）',
            'e2' => '体力上限增加（%）',
            'e3' => '体力恢复时间减少（秒）',
            'e4' => '体力恢复时间减少（%）',
            'e5' => '道具出现概率增加（%）',
            'e6' => '增加金币（个）',
            'e7' => '增加金币（%）',
            'e8' => '增加经验（点）',
            'e9' => '增加经验（%）',
            'e10' => '增加魅力值（点）',
            'e11' => '增加魅力值（%）',
            'e12' => '增加爱心值（点）',
            'e13' => '增加爱心值（%）',
            'e14' => '减少闯关体力值消耗（点）',
            'e15' => '减少闯关体力值消耗（%）',
        );
    }

    /**
     * 计算精灵技能加成
     * @param array $flagArr 技能标志数组
     * @param &array $rewardArr 奖励数组
     * @return bool
     */
    public static function calcSpiritSkillEffect($flagArr, &$rewardArr) {
        $hashKey = 'spirit';
        $homeModel = new HomeModel();
        $jsonStr = $homeModel->hGet($hashKey, false);
        if ($jsonStr === false) { // 没取到
            return false;
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            return false;
        }
        if (!isset($jsonArr['active']) || !isset($jsonArr['last_invite'])) {
            return false;
        }
        $skillArr = $jsonArr['active'];
        if ($jsonArr['last_invite'] + $skillArr['time'] < CURRENT_TIME) { // 技能有效时间已过
            return false;
        }
        if ($skillArr['skill'] != 5) { // 5特殊
            $skillConfigArr = Common::getSpiritSkillConfig($skillArr['skill']);
            $rate1Min = $skillConfigArr['rate1']['min'];
            $rate1Max = $skillConfigArr['rate1']['max'];
            if (rand($rate1Min, $rate1Max) > $skillArr['rate1']) { // 第一个随机数没命中
                return false;
            }
        }
        if (!isset($flagArr[$skillArr['skill']])) { // 标志未设置
            return false;
        }
        $tempArr = array(
            Common::ITEM_EXP => 0,
            Common::ITEM_SILVER => 0,
            Common::ITEM_CHARM => 0,
            Common::ITEM_ENERGY => 0,
        );
        foreach ($rewardArr['items'] as $item) {
            if (isset($tempArr[$item['id']])) {
                $tempArr[$item['id']] = $item['num'];
            }
        }
        $rate1 = $skillArr['rate1'];
        $rate2 = $skillArr['rate2'];
        switch ($skillArr['skill']) {
            case 0:
                $tempArr[Common::ITEM_EXP] += $rate2;
                break;
            case 1:
                $tempArr[Common::ITEM_EXP] *= 1 + $rate2 / 100;
                break;
            case 2:
                $tempArr[Common::ITEM_EXP] *= 1 + $rate2 / 100;
                break;
            case 3:
                $tempArr[Common::ITEM_EXP] += $rate2;
                break;
            case 4:
                $tempArr[Common::ITEM_EXP] += $rate2;
                break;
            case 5:
                $num = $tempArr[Common::ITEM_EXP];
                $tempArr[Common::ITEM_EXP] = $num * (1 + $rate1 / 100) + $rate2;
                break;
            case 7:
                $tempArr[Common::ITEM_SILVER] *= 1 + $rate2 / 100;
                break;
            case 6:
            case 8:
            case 9:
            case 10:
                $tempArr[Common::ITEM_SILVER] += $rate2;
                break;
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
                $tempArr[Common::ITEM_CHARM] += $rate2;
                break;
            case 16:
            case 17:
            case 18:
            case 19:
            case 20:
                $tempArr[Common::ITEM_ENERGY] += $rate2;
                break;
        }
        foreach ($rewardArr['items'] as &$item) {
            if (isset($tempArr[$item['id']])) {
                $item['num'] = intval($tempArr[$item['id']]);
                unset($tempArr[$item['id']]);
            }
        }
        foreach ($tempArr as $itemId => $num) {
            if ($num > 0) {
                $rewardArr['items'][] = array(
                    'id' => $itemId,
                    'num' => $num,
                );
            }
        }
        return true;
    }
}
?>
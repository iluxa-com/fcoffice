<?php
/**
 * 背包类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Bag {
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
     * 获取指定用户的背包模型
     * @param int $userId 用户ID
     * @return BagModel
     */
    private static function __bagModel($userId) {
        if ($userId === NULL) { // 如果用户ID为NULL,取当前登录用户的用户ID
            $userId = App::get('user_id');
        }
        return App::getInst('BagModel', $userId, false, $userId);
    }

    /**
     * 增减道具
     * @param int $itemId 道具ID
     * @param int $num 数量
     * @param int $userId 用户ID
     * @return int/false 成功时返回增加/减少成功后该道具的总数,失败时返回false
     */
    private static function __hIncrBy($itemId, $num, $userId) {
        if ($itemId == Common::ITEM_SILVER) { // 金币
            $ret = self::__userModel($userId)->hIncrBy('silver', $num);
        } else if ($itemId == Common::ITEM_EXP) { // 经验
            $ret = self::__userModel($userId)->hIncrBy('exp', $num);
        } else if ($itemId == Common::ITEM_ENERGY) { // 体力
            $ret = User::updateEnergy($num, $userId);
        } else if ($itemId == Common::ITEM_GOLD) { // FH币
            $ret = self::__userModel($userId)->hIncrBy('gold', $num);
        } else if ($itemId == Common::ITEM_CHARM) { // 魅力
            $ret = self::__userModel($userId)->hIncrBy('charm', $num);
        } else if ($itemId == Common::ITEM_HEART) { // 爱心
            $ret = self::__userModel($userId)->hIncrBy('heart', $num);
        } else { // 道具
            $ret = self::__bagModel($userId)->hIncrBy($itemId, $num);
        }
        if ($ret === false) {
            if ($itemId == Common::ITEM_SILVER) { // 金币
                $ret = self::__userModel($userId)->hIncrBy('silver', $num);
            } else if ($itemId == Common::ITEM_EXP) { // 经验
                $ret = self::__userModel($userId)->hIncrBy('exp', $num);
            } else if ($itemId == Common::ITEM_ENERGY) { // 体力
                $ret = User::updateEnergy($num, $userId);
            } else if ($itemId == Common::ITEM_GOLD) { // FH币
                $ret = self::__userModel($userId)->hIncrBy('gold', $num);
            } else if ($itemId == Common::ITEM_CHARM) { // 魅力
                $ret = self::__userModel($userId)->hIncrBy('charm', $num);
            } else if ($itemId == Common::ITEM_HEART) { // 爱心
                $ret = self::__userModel($userId)->hIncrBy('heart', $num);
            } else { // 道具
                $ret = self::__bagModel($userId)->hIncrBy($itemId, $num);
            }
        }
        if ($itemId == Common::ITEM_SILVER && $num > 0 && $ret !== false) {
            $delta = $ret - User::getMaxSilver(self::__userModel($userId)->hGet('exp'));
            if ($delta > 0) { // 超过上限
                self::__userModel($userId)->hIncrBy('silver', -$delta);
            }
        }
        return $ret;
    }

    /**
     * 增加道具
     * @param int $itemId 道具ID
     * @param int $num 数量(必须为正整数)
     * @param int $userId 用户ID
     * @return int/bool 成功时返回增加成功后该道具的总数,失败时返回false
     */
    public static function incrItem($itemId, $num, $userId = NULL) {
        if (!is_numeric($itemId) || !is_numeric($num) || $num < 1 || intval($num) != $num) {
            return false;
        }
        return self::__hIncrBy($itemId, $num, $userId);
    }

    /**
     * 减少道具(当减少成功后道具数量为0时,自动删除道具)
     * @param int $itemId 道具ID
     * @param int $num 数量(必须为正整数)
     * @param int $userId 用户ID
     * @return int/false 成功时返回该道具剩余的数量(可能为0),道具不存在或失败时返回false
     */
    public static function decrItem($itemId, $num, $userId = NULL) {
        if (!is_numeric($itemId) || !is_numeric($num) || $num < 1 || intval($num) != $num) {
            return false;
        }
        $curNum = self::getItemNum($itemId, $userId);
        if ($curNum === false || $curNum < $num) { // 不存在此道具或者当前数量比要减少的数量还少
            return false;
        }
        $ret = self::__hIncrBy($itemId, -1 * $num, $userId);
        if ($ret == 0 && !in_array($itemId, array(Common::ITEM_SILVER, Common::ITEM_EXP, Common::ITEM_ENERGY, Common::ITEM_GOLD, Common::ITEM_CHARM, Common::ITEM_HEART))) { // 如果减少后数量为0,自动删除道具
            self::delItem($itemId, $userId);
        }
        return $ret;
    }

    /**
     * 删除道具
     * @param int $itemId 道具ID
     * @param int $userId 用户ID
     * @return int 删除成功返回1,其他返回0
     */
    public static function delItem($itemId, $userId = NULL) {
        return self::__bagModel($userId)->hDel($itemId);
    }

    /**
     * 获取道具的数量
     * @param int $itemId 道具ID
     * @param int $userId 用户ID
     * @return int/false 成功时返回道具的数量,道具不存在或失败时返回false
     */
    public static function getItemNum($itemId, $userId = NULL) {
        if ($itemId == Common::ITEM_SILVER) { // 金币
            return self::__userModel($userId)->hGet('silver', false);
        } else if ($itemId == Common::ITEM_EXP) { // 经验
            return self::__userModel($userId)->hGet('exp', false);
        } else if ($itemId == Common::ITEM_ENERGY) { // 体力
            return User::getEnergy(NULL, $userId);
        } else if ($itemId == Common::ITEM_GOLD) { // FH币
            return self::__userModel($userId)->hGet('gold', false);
        } else if ($itemId == Common::ITEM_CHARM) { // 魅力
            return self::__userModel($userId)->hGet('charm', false);
        } else if ($itemId == Common::ITEM_HEART) { // 爱心
            return self::__userModel($userId)->hGet('heart', false);
        } else { // 道具
            return self::__bagModel($userId)->hGet($itemId, false);
        }
    }

    /**
     * 获取所有道具
     * @param int $userId 用户ID
     * @return array
     */
    public static function getAllItem($userId = NULL) {
        return self::__bagModel($userId)->hGetAll();
    }
}
?>
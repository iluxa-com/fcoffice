<?php
/**
 * Pet类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Pet {
    /**
     * 添加宠物
     * @param int $itemId 道具ID
     * @param int $userId 用户ID
     * @return int/false 成功时返回道具ID(宠物ID),否则返回false
     */
    public static function addPet($itemId, $flag = false, $userId = NULL) {
        // 宠物初始信息
        $dataArr = array(
            'item_id' => $itemId,
            'exp' => 0, // 经验值
            'happy' => $flag ? 50 : 100, // 开心值
            'last_feed' => CURRENT_TIME, // 上次喂食时间
        );
        $petModel = new PetModel($userId, $itemId);
        if ($petModel->exists()) {
            return false;
        }
        $multiKeyModel = new MultiKeyModel($userId, MultiKeyModel::PET);
        $multiKeyModel->sAdd($itemId);
        if ($petModel->hMset($dataArr) !== false) {
            return $itemId;
        } else {
            return false;
        }
    }

    /**
     * 删除宠物
     * @param int $itemId 道具ID(宠物ID)
     * @return bool
     */
    public static function delPet($itemId) {
        $petModel = new PetModel(NULL, $itemId);
        $ret = $petModel->delete();
        if ($ret === false) {
            return false;
        }
        $multiKeyModel = new MultiKeyModel(NULL, MultiKeyModel::PET);
        $multiKeyModel->sRemove($itemId);
        return $ret;
    }

    /**
     * 获取宠物
     * @param int $itemId 道具ID(宠物ID)
     * @return array
     */
    public static function getPet($itemId) {
        $petModel = new PetModel(NULL, $itemId);
        return $petModel->hGetAll();
    }

    /**
     * 获取全部宠物
     * @param int $userId 用户ID
     * @return array
     */
    public static function getAllPet($userId = NULL) {
        if ($userId === NULL) {
            $userId = App::get('user_id');
        }
        $multiKeyModel = new MultiKeyModel($userId, MultiKeyModel::PET);
        $keyArr = $multiKeyModel->sMembers();
        if (empty($keyArr)) {
            return array();
        }
        $multiKeyModel->RH()->multi(Redis::PIPELINE);
        foreach ($keyArr as $key) {
            $petModel = new PetModel($userId, $key);
            $petModel->hGetAll();
        }
        return $multiKeyModel->RH()->exec();
    }
}
?>
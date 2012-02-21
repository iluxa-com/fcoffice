<?php
/**
 * 宠物服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class PetService extends Service {
    /**
     * 开心值减少时间间隔(秒)
     * @var int
     */
    const HAPPY_DECR_INTERVAL = 1800;
    /**
     * 携带宠物最大数
     * @var int
     */
    const CARRY_PET_MAX_NUM = 5;

    /**
     * 更新携带宠物
     * @param array $petIdArr 宠物ID数组
     */
    public function updateCarryPet($petIdArr = NULL) {
        if (!is_array($petIdArr)) { // 可以为空数组
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!empty($petIdArr)) {
            // 移除重复的元素
            $petIdArr = array_unique($petIdArr);
            if (count($petIdArr) > self::CARRY_PET_MAX_NUM) {
                $this->_data['msg'] = 'too many pet id';
                return;
            }
            foreach ($petIdArr as $petId) {
                if (!is_numeric($petId)) {
                    $this->_data['msg'] = 'invalid pet id';
                    return;
                }
            }
        }
        if ($this->_userModel->hSet('carry_pets', implode(',', $petIdArr)) === false) {
            $this->_data['msg'] = 'update carry_pets error';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 给自己/好友的宠物喂食
     * @param int $petId 宠物ID
     * @param int $type 喂食类型(0=5%,1=10%,2=25%,3=50%)
     * @param int $friendUserId 好友用户ID(仅当给好友的宠物喂食时才需要此参数,否则传-1)
     */
    public function feedPet($petId = NULL, $type = NULL, $friendUserId = NULL) {
        if (!is_numeric($petId)) {
            $this->_data['msg'] = 'bad pet id';
            return;
        }
        if (!in_array($type, array(0, 1, 2, 3))) {
            $this->_data['msg'] = 'bad type';
            return;
        }
        if ($friendUserId == -1) { // 自己的宠物
            $petModel = new PetModel(NULL, $petId);
            $incrExp = 4; // 给自己的宠物喂食,宠物经验增加4点
        } else if (!is_numeric($friendUserId)) {
            $this->_data['msg'] = 'invalid friend user id';
            return;
        } else { // 好友的宠物
            $petModel = new PetModel($friendUserId, $petId);
            $incrExp = 1; // 给好友的宠物喂食,宠物经验增加1点
        }
        $dataArr = $petModel->hGetAll();
        if (empty($dataArr)) { // 宠物不存在或数据没取到
            $this->_data['msg'] = 'pet not exists';
            return;
        }
        // 开心值增加定义
        $happyIncrArr = array(5, 10, 25, 50);
        // 金币扣减定义
        $silverDecrArr = array(50, 100, 200, 400);
        $curSilver = $this->_userModel->hGet('silver');
        if ($curSilver === false || $curSilver < $silverDecrArr[$type]) {
            $this->_data['msg'] = 'not enough silver';
            return;
        }
        // 计算开心值应减少的数值
        $decrHappy = intval((CURRENT_TIME - $dataArr['last_feed']) / self::HAPPY_DECR_INTERVAL);
        // 当前实际剩余的开心值(当前开心值-最后喂食时间与当前时间间隔内消耗的开心值)
        $realHappy = $dataArr['happy'] - $decrHappy;
        if ($realHappy <= 0) { // 太长时间没喂,小于等于0
            $newDataArr = array(
                'happy' => $happyIncrArr[$type],
                'last_feed' => CURRENT_TIME,
            );
        } else if ($realHappy + $happyIncrArr[$type] > 100) { // 喂得太饱,超过100
            $newDataArr = array(
                'happy' => 100,
                'last_feed' => CURRENT_TIME,
            );
        } else { // 喂得刚好,不多也不少
            $newDataArr = array(
                'happy' => $realHappy + $happyIncrArr[$type],
                'last_feed' => $dataArr['last_feed'] + $decrHappy * self::HAPPY_DECR_INTERVAL,
            );
        }
        $newDataArr['exp'] = $dataArr['exp'] + $incrExp; // 给宠物加经验
        if ($petModel->hMset($newDataArr) === false) {
            $this->_data['msg'] = 'unknown error2';
            return;
        }
        Bag::decrItem(Common::ITEM_SILVER, $silverDecrArr[$type]);
        if ($friendUserId != -1) {
            // 日志数据
            $contentArr = array(
                'from_user_id' => $this->_userId,
                'item_id' => $petId,
            );
            $logDataArr = array(
                'log_type' => 1, // 好友帮我喂宠物
                'content' => json_encode($contentArr),
            );
            User::log(User::LOG_TYPE_NEWS, $logDataArr, $friendUserId); // 写好友日志(给好友喂宠物)
        }
        $this->_data = $newDataArr;
        $this->_ret = 0;
    }
}
?>
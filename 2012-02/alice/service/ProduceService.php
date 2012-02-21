<?php
/**
 * 生产服务类
 *
 * @author jj.comback@gmail.com
 * @package Alice
 */
class ProduceService extends Service {
    /**
     * 获取并返回炉子状态
     */
    public function getStatus() {
        // 取炉子状态
        $stoveModel = new StoveModel();
        $stoveArr = $stoveModel->hGetAll();
        // 获取精灵工坊当前状态信息
        $workshopData = $this->__getWrokShopData();
        // 取合成公式熟练度信息
        $skillModel = new SkillModel();
        $skillArr = $skillModel->hGetAll();
        $this->_data = array(
            'workshop' => $workshopData,
            'stove' => $stoveArr,
            'skill' => $skillArr,
        );
        $this->_ret = 0;
    }

    /**
     * 合成到时间了
     * @param  $stoveId 传入炉子ID
     */
    public function getItem($stoveId = NULL) {
        if (!is_numeric($stoveId)) {
            $this->_data['msg'] = 'invaild id';
            return;
        }
        //获取炉子信息
        $stoveModel = new StoveModel();
        $stoveStr = $stoveModel->hGet($stoveId, FALSE);
        if ($stoveStr === FALSE) { // 取炉子信息失败
            $this->_data['msg'] = 'no stove';
            return;
        }
        $stoveArr = json_decode($stoveStr, TRUE);
        if ($stoveArr === NULL) { //解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (!isset($stoveArr['mergeId'])) { // 炉子没有合成信息
            $this->_data['msg'] = 'stove has no merge item';
            return;
        }
        if (isset($stoveArr['rs'])) { // 合成结果已经存在
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (($stoveArr['stime'] + $stoveArr['cd']) > time()) { // 合成还没有完成
            $this->_data['msg'] = 'merge error';
            return;
        }
        $workshop = $this->__getWrokShopData(); // 获取精灵工坊信息
        if ($workshop['level'] < 1) {
            $this->_data['msg'] = 'data error!';
            return;
        }
        $workShopArr = Common::getWorkshopConfig($workshop['level']);
        if ($workShopArr === false) {
            $this->_data['msg'] = 'ret data error';
            return;
        }
        // 获取角色合成技能等级
        $skillModel = new SkillModel();
        $getSkillExp = $skillModel->hGet($stoveArr['mergeId'], 0);
        if ($getSkillExp === NULL) {
            $this->_data['msg'] = 'get skill error!';
            return;
        }
        // 根据技能经验取等级
        $getSkillGrade = Common::getSkillGradeByExp($getSkillExp);
        if ($getSkillGrade === false) {
            $this->_data['msg'] = 'data error';
            return;
        }
        $skillConfig = Common::getSkillConfig($getSkillGrade);
        if ($skillConfig === FALSE) {
            $this->_data['msg'] = 'get skill config error';
            return;
        }
        $luckyRate = 0; // 定义幸运卡增加的成功率，初始值0，暂时没有数据
        $luckyCardArr = array(7500 => 5, 7501 => 20, 7502 => 40, 7503 => 95,);
        if (isset($stoveArr['luckyId'])) {
            if (isset($luckyCardArr[$stoveArr['luckyId']])) {  // 有幸运卡时
                $luckyRate = $luckyCardArr[$stoveArr['luckyId']];
            }
        }
        $success = rand(1, 100);
        $dataArr = Common::getMergeItemConfig($stoveArr['mergeId']);
        $mergeRate = ($dataArr['rate'] + $skillConfig['rate'] + $luckyRate);
        if ($success <= $mergeRate || $success >= 100) { // 合成成功 返回炉子中成功信息
            $stoveArr = array_merge($stoveArr, array('rs' => 0));
        } else { // 合成失败
            $stoveArr = array_merge($stoveArr, array('rs' => 1));
        }
        // 合成成功或失败都保存信息
        $stoveModel->hSet($stoveId, json_encode($stoveArr));
        $this->_data = $stoveArr;
        $this->_ret = 0;
    }

    /**
     * 合成完成，炉子里面的物品放入背包服务
     * @param int $stoveId
     */
    public function saveMergeItem($stoveId = NULL) {
        if (!is_numeric($stoveId)) {
            $this->_data['msg'] = 'invalid id';
            return;
        }
        //获取炉子信息
        $stoveModel = new StoveModel();
        $stoveStr = $stoveModel->hGet($stoveId);
        if ($stoveStr === FALSE) { // 取炉子信息失败
            $this->_data['msg'] = 'no stove';
            return;
        }
        $stoveArr = json_decode($stoveStr, TRUE);
        if ($stoveArr === NULL) { //解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (!isset($stoveArr['rs'])) {
            $this->_data['msg'] = 'not finished';
            return;
        }
        $workshop = $this->__getWrokShopData(); // 获取精灵工坊信息
        if ($workshop['level'] < 1) {
            $this->_data['msg'] = 'data error!';
            return;
        }
        $workShopArr = Common::getWorkshopConfig($workshop['level']);
        if ($workShopArr === false) {
            $this->_data['msg'] = 'ret data error';
            return;
        }
        if ($stoveArr['rs'] == 0) { // 合成成功
            $dataArr = Common::getMergeItemConfig($stoveArr['mergeId']);
            Bag::incrItem($dataArr['final'], 1);
            $skillExp = $workShopArr['successExp'];
        }
        if ($stoveArr['rs'] == 1) { // 合成失败
            $skillExp = $workShopArr['failExp'];
        }
        // 取技能熟练度模型
        $skillModel = new SkillModel();
        //取当前限制最高技能熟练度
        $maxSkillExp = Common::getSkillExpByGrade(($workShopArr['maxSkill'] - 1));
        // 增加技能熟练度后，再取当前熟练度
        $curSkillExp = $skillModel->hGet($stoveArr['mergeId'], 0);
        $skillAddExp = 0;
        if ($maxSkillExp >= $curSkillExp && $maxSkillExp <= ($curSkillExp + $skillExp)) { // 最大经验大于当前经验并小于当前和增加值时，取差值
            $skillAddExp = ($maxSkillExp - $curSkillExp);
        }
        if ($maxSkillExp > ($curSkillExp + $skillExp)) { // 最大经验比当前经验和增加值的和还大时取增加值
            $skillAddExp = $skillExp;
        }
        if ($skillModel->hIncrBy($stoveArr['mergeId'], $skillAddExp) === false) { // 增加技能熟练度
            $this->_data['msg'] = 'set data error';
            return;
        }
        $reloadStove = array('id' => $stoveId);
        $stoveModel->hSet($stoveId, json_encode($reloadStove));
        $this->_data = $stoveArr;
        $this->_ret = 0;
    }

    /**
     * 合成物品
     * @param int $id 合成公式编号[也是合成得到物品的ID]
     * @param int $stoveId 合成炉炉子ID
     * @param int $luckyCardId 幸运卡ID
     */
    public function mergeItem($id = NULL, $stoveId = NULL, $luckyCardId = 0) {
        if (!is_numeric($id)) {
            $this->_data['msg'] = 'invalid id';
            return;
        }
        if (!is_numeric($stoveId)) {
            $this->_data['msg'] = 'invalid id';
            return;
        }
        // 增加验证，精灵工坊等级限制合成公式
        $workshopData = $this->__getWrokShopData();
        if ($workshopData['level'] < 1) {
            $this->_data['msg'] = 'data errror';
            return;
        }
        $workShopConfig = Common::getWorkshopConfig($workshopData['level']);
        if (!in_array($id, $workShopConfig['mergeId'])) {
            $this->_data['msg'] = 'mergeId unlock!';
            return;
        }
        $stoveModel = new StoveModel();
        $stoveStr = $stoveModel->hGet($stoveId);
        if ($stoveStr === FALSE) { // 取炉子信息失败
            $this->_data['msg'] = 'no stove';
            return;
        }
        $stoveArr = json_decode($stoveStr, TRUE);
        if ($stoveArr === NULL) { //解码失败
            $this->_data['msg'] = 'json decode fail';
            return;
        }
        if (isset($stoveArr['mergeId'])) { //炉子正在工作中
            $this->_data['msg'] = 'stove has working';
            return;
        }
        $dataArr = Common::getMergeItemConfig($id);
        if ($dataArr === false) {
            $this->_data['msg'] = 'invalid id2';
            return;
        }
        foreach ($dataArr['need'] as $itemId => $num) {
            $curNum = Bag::getItemNum($itemId);   //获取背包里合成材料的数量
            if ($curNum === false || $curNum < $num) {
                $this->_data['msg'] = $itemId . ' has not enough';
                return;
            }
        }
        $silverNum = 50;    //正常合成的手续费
        $silver = Bag::getItemNum(Common::ITEM_SILVER);    //获取背包里金币数量
        //背包里金币是否够50金币手续费
        if ($silver < $silverNum) {
            $this->_data['msg'] = 'silver has not enough';
            return;
        }
        //定义幸运卡增加的成功率，暂时没有数据
        $luckyCardArr = array(
            7500 => 5, 7501 => 20, 7502 => 40, 7503 => 95,
        );
        if ($luckyCardId != 0) {
            if (!isset($luckyCardArr[$luckyCardId])) {
                $this->_data['msg'] = 'invalid lucky card id';
                return;
            }
            // 判断背包里面是否有幸运道具
            $curNum = Bag::getItemNum($luckyCardId);
            if ($curNum === false || $curNum < 1) {
                $this->_data['msg'] = 'not enough lucky item';
                return;
            }
        }
        if (Bag::decrItem(Common::ITEM_SILVER, $silverNum) === false) { // 扣钱失败
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        if ($luckyCardId != 0) {
            if (Bag::decrItem($luckyCardId, 1) === false) {
                Bag::incrItem(Common::ITEM_SILVER, $silverNum); // 回滚金币
                $this->_data['msg'] = 'decr lucky item fail';
                return;
            }
        }
        // 合成扣掉道具
        $cacheItemArr = array(); //创建一个缓存数组。做道具回滚用
        foreach ($dataArr['need'] as $itemId => $num) {
            if (Bag::decrItem($itemId, $num) === FALSE) {
                if ($luckyCardId != 0) {
                    Bag::incrItem($luckyCardId, 1); // 回滚幸运卡
                }
                Bag::incrItem(Common::ITEM_SILVER, $silverNum); // 回滚金币
                if (!empty($cacheItemArr)) { // 如果部分道具被扣除，则回滚扣掉的道具
                    foreach ($cacheItemArr as $cItemId => $cNum) {
                        Bag::incrItem($cItemId, $cNum);
                    }
                }
                $this->_data['msg'] = 'decr lucky item fail';
                return;
            } else {
                $cacheItemArr[$itemId] = $num;
            }
        }
        // 获取角色合成技能等级
        $skillModel = new SkillModel();
        $getSkillExp = $skillModel->hGet($id, 0);
        if ($getSkillExp === NULL) {
            $this->_data['msg'] = 'get skill error!';
            return;
        }
        // 根据技能经验取等级
        $getSkillGrade = Common::getSkillGradeByExp($getSkillExp);
        if ($getSkillGrade === false) {
            $this->_data['msg'] = 'data error';
            return;
        }
        $skillConfig = Common::getSkillConfig($getSkillGrade);
        if ($skillConfig === FALSE) {
            $this->_data['msg'] = 'get skill config error';
            return;
        }
        // 保存合成信息
        $saveStoveArr = array(
            'id' => $stoveId,
            'stime' => time(), // 合成开始时间
            'cd' => $skillConfig['cd'],
            'mergeId' => $id,
            'luckyId' => $luckyCardId,
        );
        if ($stoveModel->hSet($stoveId, json_encode($saveStoveArr)) === FALSE) {
            Bag::incrItem(Common::ITEM_SILVER, $silverNum); // 回滚金币
            if ($luckyCardId != 0) {
                Bag::incrItem($luckyCardId, 1); // 回滚幸运卡
            }
            foreach ($dataArr['need'] as $itemId => $num) { // 回滚道具
                Bag::incrItem($itemId, $num);
            }
            $this->_data['msg'] = 'set working info fail';
            return;
        }
        $this->_data = $saveStoveArr;
        $this->_ret = 0;
    }

    /**
     * 返回精灵工坊数据
     * @return Array
     */
    private function __getWrokShopData() {
        $homeModel = new HomeModel();
        $workshopStr = $homeModel->hGet('workshop');
        if ($workshopStr === FALSE) {
            $this->_data['msg'] = 'get workshop data error';
            return;
        }
        $workshopArr = json_decode($workshopStr, TRUE);
        if ($workshopArr === FALSE) { // 解码失败
            $this->_data['msg'] = 'json decode error';
            return;
        }
        return $workshopArr;
    }

    /**
     * FH解锁炉子
     * @param  $stoveId 炉子Id
     */
    public function unlockStove($stoveId = NULL) {
        if (!is_numeric($stoveId)) {
            $this->_data['msg'] = 'invaild id';
            return;
        }
        $stoveModel = new StoveModel();
        $stoveStr = $stoveModel->hGet($stoveId, false);
        if ($stoveStr !== false) { // 炉子已解锁
            $this->_data['msg'] = 'stove locked';
            return;
        }
        $dataArr = Common::getStoveConfig($stoveId);
        if ($dataArr === FALSE) {
            $this->_data['msg'] = 'data error';
            return;
        }
        $gold = Bag::getItemNum(Common::ITEM_GOLD);
        if ($gold < $dataArr['gold']) { // FH币不够
            $this->_data['msg'] = 'gold not enough';
            return;
        }
        if (Bag::decrItem(Common::ITEM_GOLD, $dataArr['gold']) === false) { // 扣钱失败
            $this->_data['msg'] = 'decr item fail';
            return;
        }
        if ($stoveModel->hSet($stoveId, '{"id":' . $stoveId . '}') === false) { // 解锁失败
            Bag::incrItem(Common::ITEM_GOLD, $dataArr['gold']); // 回滚FH币
            $this->_data['msg'] = 'set working info fail';
            return;
        }
        $this->_ret = 0;
    }
}
?>
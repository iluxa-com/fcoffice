<?php
/**
 * 关卡数据服务类(AS编辑器使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class LevelDataService extends AuthService {
    /**
     * 获取区域及地点数据
     */
    public function getZoneAndPlace() {
        $zoneDataSQLModel = new ZoneDataSQLModel();
        $zoneDataArr = $zoneDataSQLModel->SH()->fields('zone_id, name')->getAll();
        $palceDataSQLModel = new PlaceDataSQLModel();
        $placeDataArr = $palceDataSQLModel->SH()->find(array('type' => 1))->fields('zone_id, place_id, name')->getAll();
        $areaDataSQLModel = new AreaDataSQLModel();
        $areaDataArr = $areaDataSQLModel->SH()->fields('place_id, area_id, name')->getAll();
        $this->_data = array(
            'zone' => $zoneDataArr,
            'place' => $placeDataArr,
            'area' => $areaDataArr,
        );
        $this->_ret = 0;
    }

    /**
     * 设置关卡数据
     * @param int $zoneId 区域ID
     * @param int $placeId 地点ID
     * @param int $areaId 地区ID
     * @param int $skillLevel 熟练度(0,1,2,3,4) 节点编号[0,50] [100,101]
     * @param int $difficulty 关卡难度(0=简单,1=普通,2=困难) 模式(0=普通,1=挑战,2=隐藏)
     * @param string $dataStr 关卡数据
     * @param int $levelId 关卡编号(仅当修改的时候才需要此参数)
     */
    public function setLevelData($zoneId = NULL, $placeId = NULL, $areaId = NULL, $skillLevel = NULL, $difficulty = NULL, $dataStr = NULL, $levelId = NULL) {
        if (!is_numeric($zoneId)) {
            $this->_data['msg'] = 'invalid zone id';
            return;
        }
        if (!is_numeric($placeId)) {
            $this->_data['msg'] = 'invalid place id';
            return;
        }
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        if (!$this->__isValidSkillLevel($skillLevel)) {
            $this->_data['msg'] = 'invalid skill(node id)';
            return;
        }
        if (!in_array($difficulty, array(0, 1, 2))) {
            $this->_data['msg'] = 'invalid difficulty(mode)';
            return;
        }
        if (strlen($dataStr) > 30000) { // 数据太长了(500*60)
            $this->_data['msg'] = 'data too long(max=30KB)';
            return;
        }
        if (json_decode($dataStr, true) === NULL) { // 数据不能解码
            $this->_data['msg'] = 'data decode error';
            return;
        }
        $levelDataModel = new LevelDataModel();
        $add = false;
        if (!is_numeric($levelId)) { // 没提供关卡编码,则新增
            $add = true;
            $levelId = $levelDataModel->genNextLevelId(); // 生成关卡ID
            if ($levelDataModel->hExists($levelId)) { // 如果是新增,并且已存在,则直接返回
                $this->_data['msg'] = 'level id exists';
                return;
            }
        }
        if ($levelDataModel->hSet($levelId, $dataStr) === false) {
            $this->_data['msg'] = 'hSet error';
            return;
        }
        if ($add) {
            $levelSetModel = new LevelSetModel($areaId, $skillLevel, $difficulty);
            if ($levelSetModel->sAdd($levelId) === false) {
                $this->_data['msg'] = 'sAdd error';
                return;
            }
        }
        $this->_data = array(
            'zone_id' => $zoneId,
            'place_id' => $placeId,
            'area_id' => $areaId,
            'skill' => $skillLevel,
            'difficulty' => $difficulty,
            'level_id' => $levelId,
        );
        $this->_ret = 0;
    }

    /**
     * 设置关卡集合
     * @param int $zoneId 区域ID
     * @param int $placeId 地点ID
     * @param int $areaId 地区ID
     * @param int $skillLevel 熟练度(0,1,2,3,4)
     * @param int $difficulty 关卡难度(0=简单,1=普通,2=困难)
     */
    public function getLevelSet($zoneId = NULL, $placeId = NULL, $areaId = NULL, $skillLevel = NULL, $difficulty = NULL) {
        if (!is_numeric($zoneId)) {
            $this->_data['msg'] = 'invalid zone id';
            return;
        }
        if (!is_numeric($placeId)) {
            $this->_data['msg'] = 'invalid place id';
            return;
        }
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        if (!$this->__isValidSkillLevel($skillLevel)) {
            $this->_data['msg'] = 'invalid skill(node id)';
            return;
        }
        if (!in_array($difficulty, array(0, 1, 2))) {
            $this->_data['msg'] = 'invalid difficulty(mode)';
            return;
        }
        $levelSetModel = new LevelSetModel($areaId, $skillLevel, $difficulty);
        $this->_data = array(
            'zone_id' => $zoneId,
            'place_id' => $placeId,
            'area_id' => $areaId,
            'skill' => $skillLevel,
            'difficulty' => $difficulty,
            'set' => $levelSetModel->sMembers(),
        );
        $this->_ret = 0;
    }

    /**
     * 获取关卡数据
     * @param string/int $hashKey
     */
    public function getLevelData($hashKey = NULL) {
        if ($hashKey === NULL || $hashKey === '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $levelDataModel = new LevelDataModel();
        $dataStr = $levelDataModel->hGet($hashKey, false);
        if ($dataStr === false) {
            $this->_data['msg'] = 'get fail';
            return;
        }
        $this->_data['data'] = json_decode($dataStr);
        $this->_ret = 0;
    }

    /**
     * 删除指定的关卡
     * @param int $zoneId 区域ID
     * @param int $placeId 地点ID
     * @param int $areaId 地区ID
     * @param int $skillLevel 熟练度(0,1,2,3,4)
     * @param int $difficulty 关卡难度(0=简单,1=普通,2=困难)
     * @param int $levelId 关卡编号(仅当修改的时候才需要此参数)
     */
    public function deleteLevelData($zoneId = NULL, $placeId = NULL, $areaId = NULL, $skillLevel = NULL, $difficulty = NULL, $levelId = NULL) {
        $levelSetModel = new LevelSetModel($areaId, $skillLevel, $difficulty);
        if ($levelSetModel->sRemove($levelId) !== false) {
//            $levelDataModel = new LevelDataModel();
//            $levelDataModel->hDel($levelId);
        }
        $this->_data = array(
            'zone_id' => $zoneId,
            'place_id' => $placeId,
            'area_id' => $areaId,
            'skill' => $skillLevel,
            'difficulty' => $difficulty,
            'level_id' => $levelId,
        );
        $this->_ret = 0;
    }

    /**
     * 判断熟练度(节点编号)是否有效
     * @param int $skillLevel
     * @return bool
     */
    private function __isValidSkillLevel($skillLevel) {
        if (!is_numeric($skillLevel)) {
            return false;
        }
        return (($skillLevel >= 0 && $skillLevel <= 50) || $skillLevel == 100 || $skillLevel == 101);
    }
}
?>
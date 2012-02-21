<?php
/**
 * 地图数据服务类(AS编辑器使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class MapDataService extends AuthService {
    /**
     * 设置地图数据
     * @param string/int $hashKey
     * @param string $dataStr
     */
    public function setMapData($hashKey = NULL, $dataStr = NULL) {
        if ($hashKey === NULL || $hashKey === '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $mapDataModel = new MapDataModel();
        if ($mapDataModel->hSet($hashKey, $dataStr) === false) {
            $this->_data['msg'] = 'set fail';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 获取地图数据
     * @param string/int $hashKey
     */
    public function getMapData($hashKey = NULL) {
        if ($hashKey === NULL || $hashKey === '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $mapDataModel = new MapDataModel();
        $dataStr = $mapDataModel->hGet($hashKey, false);
        if ($dataStr === false) {
            $this->_data['msg'] = 'get fail';
            return;
        }
        $this->_data['data'] = $dataStr;
        $this->_ret = 0;
    }

    /**
     * 获取所有的地图名称
     */
    public function getAllMapName() {
        $mapDataModel = new MapDataModel();
        $keyArr = $mapDataModel->hKeys();
        if ($keyArr === false) {
            $this->_data['msg'] = 'get all fail';
            return;
        }
        $this->_data['data'] = $keyArr;
        $this->_ret = 0;
    }
}
?>
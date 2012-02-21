<?php
/**
 * 资源数据服务类(AS编辑器使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ResourceDataService extends AuthService {
    /**
     * 设置资源数据
     * @param string/int $hashKey
     * @param string $dataStr
     */
    public function setResourceData($hashKey = NULL, $dataStr = NULL) {
        if ($hashKey === NULL || $hashKey === '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $resourceDataModel = new ResourceDataModel();
        if ($resourceDataModel->hSet($hashKey, $dataStr) === false) {
            $this->_data['msg'] = 'set fail';
            return;
        }
        $this->_ret = 0;
    }

    /**
     * 获取资源数据
     * @param string/int $hashKey
     */
    public function getResourceData($hashKey = NULL) {
        if ($hashKey === NULL || $hashKey === '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $resourceDataModel = new ResourceDataModel();
        $dataStr = $resourceDataModel->hGet($hashKey, false);
        if ($dataStr === false) {
            $this->_data['msg'] = 'get fail';
            return;
        }
        $this->_data['data'] = $dataStr;
        $this->_ret = 0;
    }

    /**
     * 获取所有资源数据
     */
    public function getAllResourceData() {
        $resourceDataModel = new ResourceDataModel();
        $dataArr = $resourceDataModel->hGetAll();
        if ($dataArr === false) {
            $this->_data['msg'] = 'get all fail';
            return;
        }
        $this->_data['data'] = $dataArr;
        $this->_ret = 0;
    }
}
?>
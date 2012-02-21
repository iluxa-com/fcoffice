<?php
/**
 * 服装数据服务类(AS编辑器使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class DressDataService extends AuthService {
    /**
     * 设置服装数据
     * @param string $dataStr
     */
    public function setDressData($dataStr = NULL) {
//        if ($hashKey === NULL || $hashKey === '') {
//            $this->_data['msg'] = 'bad request';
//            return;
//        }
        $dressDataModel = new DressDataModel();
        $dataArr = json_decode($dataStr, true);
        if (count($dataArr) !== 6) {
            $this->_data['msg'] = 'count not equal 6';
            return;
        }
        $hashKeyArr = array();
        foreach ($dataArr as $val) {
            $hashKey = $dressDataModel->genNextDressId();
            if ($hashKey > 1999) {
                $this->_data['msg'] = 'hash key > 1999';
                return;
            }
            if ($dressDataModel->hSet($hashKey, json_encode($val)) === false) {
                $this->_data['msg'] = 'set fail';
                return;
            }
            $hashKeyArr[] = $hashKey;
        }
        $suitDataModel = new SuitDataModel();
        $hashKey = $suitDataModel->genNextSuitId();
        $suitDataModel->hSet($hashKey, implode(',', $hashKeyArr));
        $this->_ret = 0;
    }

    /**
     * 获取服装数据
     * @param string/int $hashKey
     */
    public function getDressData($hashKey = NULL) {
        if ($hashKey === NULL || $hashKey === '') {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $dressDataModel = new DressDataModel();
        $dataStr = $dressDataModel->hGet($hashKey, false);
        if ($dataStr === false) {
            $this->_data['msg'] = 'get fail';
            return;
        }
        $this->_data['data'] = $dataStr;
        $this->_ret = 0;
    }

    /**
     * 获取所有的服装数据
     */
    public function getAllDressData() {
        $dressDataModel = new DressDataModel();
        $dressDataArr = $dressDataModel->hGetAll();
        if ($dressDataArr === false) {
            $this->_data['msg'] = 'get dress data fail';
            return;
        }
        $suitDataModel = new SuitDataModel();
        $suitDataArr = $suitDataModel->hGetAll();
        if ($suitDataArr === false) {
            $this->_data['msg'] = 'get suit data fail';
            return;
        }
        $this->_data['data'] = array(
            'dress' => $dressDataArr,
            'suit' => $suitDataArr,
        );
        $this->_ret = 0;
    }
}
?>
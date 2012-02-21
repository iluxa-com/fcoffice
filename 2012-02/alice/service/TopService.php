<?php
/**
 * 排名服务类
 *
 * @author xianlinli@gmail.com
 */
class TopService extends Service {
    /**
     * 获取排名数据
     * @param string $type 类型(exp/silver/heart)
     */
    public function getTopData($type = NULL) {
        $this->_data['list'] = $this->__getTopData($type);
        $this->_ret = 0;
    }

    /**
     * 获取金币排名用户
     * @param string $type 类型(exp/silver/gold)
     * @return array
     */
    private function __getTopData($type) {
        switch ($type) {
            case 'exp':
                $hashKey = 'USER_EXP';
                break;
            case 'silver':
                $hashKey = 'USER_SILVER';
                break;
            case 'heart':
                $hashKey = 'USER_HEART';
                break;
            default:
                return array();
                break;
        }
        // 尝试读取缓存数据(假如存在的话)
        $circleCacheModel = new CircleCacheModel(NULL, $hashKey);
        $jsonStr = $circleCacheModel->get();
        if ($jsonStr !== false) { // 取到数据
            $jsonArr = json_decode($jsonStr, true);
            if ($jsonArr !== NULL) { // 解码成功
                return $jsonArr;
            }
        }
        $topDataModel = new TopDataModel($hashKey);
        $jsonStr = $topDataModel->get();
        if ($jsonStr === false) { // 没获取到
            return array();
        }
        // 数据缓存处理
        if ($circleCacheModel->setnx($jsonStr) === false) {
            $jsonStr = $circleCacheModel->get();
        } else {
            // 缓存到明天零点(延时5分钟,预留给计划任务)
            //$expireTime = strtotime('today', CURRENT_TIME) + 86400 + 300;
            $expireTime = CURRENT_TIME + App::get('TopDataUpdateInterval', 86400);
            if ($circleCacheModel->expireAt($expireTime) === false) {
                $circleCacheModel->delete();
            }
        }
        $jsonArr = json_decode($jsonStr, true);
        if ($jsonArr === NULL) { // 解码失败
            return array();
        }
        return $jsonArr;
    }
}
?>
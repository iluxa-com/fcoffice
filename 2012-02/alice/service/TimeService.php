<?php
/**
 * 时间服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class TimeService extends Service {
    /**
     * 重载父类的构造函数(目的是阻止调用父类的构造函数)
     * @param array $configArr array($service, $action, $index, $lastLogin)
     */
    public function __construct($configArr) {
        list($service, $action, $index, $lastLogin) = $configArr;
        $this->_index = $index;
    }

    /**
     * 同步时间
     */
    public function syncTime() {
        $this->_data['server_time'] = time();
        $this->_ret = 0;
    }

    /**
     * 校对时间
     * @param int $clientTime 客户端Unux时间戳
     * @param int $clientEnergy 客户端体力值
     */
    public function proofTime($clientTime = NULL, $clientEnergy = NULL) {
        if (!is_numeric($clientTime)) {
            $clientTime = 0;
        }
        if (!is_numeric($clientEnergy)) {
            $clientEnergy = 0;
        }
        $currentUser = App::getCurrentUser();
        App::set('user_id', $currentUser['user_id']);
        $serverTime = time();
        $serverEnegy = User::getEnergy();
        $dataArr = array(
            'user_id' => $currentUser['user_id'],
            'server_time' => $serverTime,
            'client_time' => $clientTime,
            'differ_time' => $serverTime - $clientTime,
            'server_energy' => $serverEnegy,
            'client_energy' => $clientEnergy,
            'differ_energy' => $serverEnegy - $clientEnergy,
        );
        $filename = '/tmp/alice_proof_time.csv';
        if (file_exists($filename)) {
            file_put_contents($filename, implode(',', $dataArr) . "\r\n", FILE_APPEND);
        }
        $this->_data = $dataArr;
        $this->_ret = 0;
    }
}
?>
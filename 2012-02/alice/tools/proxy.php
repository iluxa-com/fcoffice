<?php
/**
 * 代理类
 *
 * @author xianlinli@gmail.com
 */
class Proxy {
    /**
     * 服务网关地址
     * @var string
     */
    private $_gatewayUrl;

    /**
     * 构造函数
     * @param string $platform 平台(devel/renren/renren2)
     */
    public function __construct($platform) {
        switch ($platform) {
            case 'renren': // 人人网(旧版)
                $this->_gatewayUrl = 'http://alrr.fanhougame.com/gateway.php';
                break;
            case 'renren2': // 人人网(新版)
                $this->_gatewayUrl = 'http://newalrr.fanhougame.com/gateway.php';
                break;
            case '4399': // 4399
                $this->_gatewayUrl = 'http://alice4399.fanhouapp.com/gateway.php';
                break;
            case 'pengyou': // TX-朋友
                $this->_gatewayUrl = 'http://app27790.qzoneapp.com/gateway.php';
                break;
            case 'qzone': // TX-Qzone
                $this->_gatewayUrl = 'http://app27790.qzone.qzoneapp.com/gateway.php';
                break;
            case 'devel': // 本地开发服务器
                $this->_gatewayUrl = 'http://alice-dev.fanhougame.com/alice-new-devel-php/gateway.php';
                break;
            default:
                throw new Exception("UNDEFINED_PLATFORM({$platform})");
                break;
        }
    }

    /**
     * 执行请求
     * @param array $dataArr 数据数组
     * @return array
     */
    private function __doRequest($dataArr) {
        $ch = curl_init();
        $optionArr = array(
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->_gatewayUrl,
            CURLOPT_POSTFIELDS => http_build_query($dataArr, '', '&'),
        );
        curl_setopt_array($ch, $optionArr);
        $responseStr = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);
        curl_close($ch);
        if ($httpCode !== 200 || $errorNo !== 0) { // CURL错误
            throw new Exception("CURL_ERROR({$httpCode},{$errorNo})");
        }
        $responseArr = json_decode($responseStr, true);
        if ($responseArr === NULL) { // 解码失败
            throw new Exception('RETURN_DECODE_FAIL');
        }
        if ($responseArr['ret'] != 0) {
            if (isset($responseArr['msg'])) {
                throw new Exception("API_FAIL({$responseArr['msg']}");
            } else {
                throw new Exception("API_FAIL(NULL)");
            }
        }
        return $responseArr;
    }

    /**
     * 计算签名
     * @param string $str 待签名数据
     * @return string
     */
    private function __calcSign($str) {
        return md5($str . 'DW7SWxABRcFGKnrkr4vhgIWK6imsfhQB');
    }

    /**
     * 添加签名
     * @param &array $dataArr 数据数组
     */
    private function __addSign(&$dataArr) {
        ksort($dataArr);
        $str = '';
        foreach ($dataArr as $key => $val) {
            $str .= $key . '=' . $val;
        }
        $dataArr['sign'] = $this->__calcSign($str);
    }

    /**
     * 重载__call方法
     * @param type $method
     * @param type $paramArr
     */
    public function __call($method, $paramArr) {
        $dataArr = array(
            'service' => 'DataService',
            'action' => $method,
            'params' => json_encode($paramArr),
            'rand' => microtime(true),
        );
        $this->__addSign($dataArr);
        return $this->__doRequest($dataArr);
    }
}

//$proxy = new Proxy('renren2');
//$date = date('Y-m-d', strtotime('-1 day'));
//echo '<pre>';
//print_r($proxy->getDayData($date));
?>
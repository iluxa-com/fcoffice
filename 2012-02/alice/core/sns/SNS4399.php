<?php
/**
 * 4399开放API接口类
 *
 * @link http://app.my.4399.com/wiki/API.html
 * @author xianlinli@gmail.com
 * @package Alice
 */
class SNS4399 {
    /**
     * API URL
     * @var string
     */
    private $_apiUrl = 'http://app.my.4399.com/response.php';
    /**
     * 安全密钥
     * @var string
     */
    private $_secretKey = 'c22015d4d060cc7a21e32541c8fb2441';
    /**
     * 全局参数数组
     * @var array
     */
    private $_globalParamArr = array(
        'api_key' => '4a7aa5709e8f77d3fc4fede52fae2350',
        'format' => 'JSON',
        'v' => '0.1',
    );
    /**
     * sns uid
     * @var string/int
     */
    private $_snsUid;
    /**
     * session key
     * @var string
     */
    private $_sessionKey;
    /**
     * 参数数组
     * @var array
     */
    private $_paramArr = array();

    /**
     * 构造函数
     */
    public function __construct() {
        // 调用方式为iframe时使用GET方式传参,为MYML时使用POST方式传参,为了兼顾两种方式,这里使用$_REQUEST取参数
        if (isset($_REQUEST['my_sig_uId']) && isset($_REQUEST['my_sig_sessionId'])) { // 尚未登录,取REQUEST参数
            $this->_snsUid = $_REQUEST['my_sig_uId'];
            $this->_sessionKey = $_REQUEST['my_sig_sessionId'];
        } else { // 已登录,取SESSION中的数据
            $currentUser = App::getCurrentUser();
            $this->_snsUid = $currentUser['sns_uid'];
            $this->_sessionKey = $currentUser['sns_session_key'];
        }
        if ($this->_snsUid === '' || $this->_sessionKey === '') {
            throw new UserException(UserException::ERROR_SNS_INVALID_PARAM, 'SNS_INVALID_PARAM', $this->_snsUid, $this->_sessionKey);
        }
        $this->_globalParamArr['session_key'] = $this->_sessionKey;
    }

    /**
     * 获取sns uid
     * @return string/int
     */
    public function getSNSUid() {
        return $this->_snsUid;
    }

    /**
     * 获取session key
     * @return string
     */
    public function getSessionKey() {
        return $this->_sessionKey;
    }

    /**
     * 获取PostFields
     * @return string
     */
    private function __getPostFields() {
        $tempArr = $this->_globalParamArr;
        ksort($tempArr);
        $str = '';
        foreach ($tempArr as $key => $val) {
            $str .= $key . '=' . $val . '&';
        }
        ksort($this->_paramArr);
        foreach ($this->_paramArr as $key => $val) {
            $tempArr['args'][$key] = $val;
            $str .= 'args[' . $key . ']' . '=' . $val . '&';
        }
        $tempArr['sig'] = md5($str . $this->_secretKey);
        return http_build_query($tempArr, '', '&');
    }

    /**
     * 执行请求
     * @return array
     */
    private function __doRequest($hasPostFile = false) {
        $ch = curl_init();
        $optionArr = array(
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->_apiUrl,
            CURLOPT_POSTFIELDS => $this->__getPostFields(),
        );
        curl_setopt_array($ch, $optionArr);
        $responseStr = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);
        curl_close($ch);
        if ($httpCode !== 200 || $errorNo !== 0) { // CURL错误
            throw new UserException(UserException::ERROR_CURL, 'CURL_ERROR', $httpCode, $errorNo);
        }
        $responseArr = json_decode($responseStr, true);
        if ($responseArr === NULL) { // 解码失败
            throw new UserException(UserException::ERROR_SNS_API_BAD_RETURN, 'SNS_API_BAD_RETURN', $responseStr);
        }
        if (isset($responseArr['errCode']) && $responseArr['errCode'] != 0) {
            throw new UserException(UserException::ERROR_SNS_API_FAIL, 'SNS_API_FAIL', $responseStr);
        }
        return $responseArr['result'];
    }

    /**
     * 获取指定用户的信息
     * @param string $snsUid sns uid
     * @return array
     */
    public function getUserInfo($snsUid = NULL) {
        if ($snsUid === NULL) {
            $snsUid = $this->_snsUid;
        }
        $this->_globalParamArr['method'] = 'User.getInfo';
        $this->_paramArr = array(
            'uids' => $snsUid,
            'fields' => 'name,pic_thumb',
        );
        $responseArr = $this->__doRequest();
        $returnArr = array(
            'username' => $responseArr[0]['name'],
            'head_img' => $responseArr[0]['pic_thumb'],
        );
        return $returnArr;
    }

    /**
     * 获取好友列表
     * @return array
     */
    public function getFriends() {
        $this->_globalParamArr['method'] = 'Friend.getAppUsers';
        $this->_paramArr = array();
        $responseArr = $this->__doRequest();
        if (empty($responseArr)) {
            return array();
        }
        $returnArr = array();
        foreach ($responseArr as $snsUid) {
            $tempArr = $this->getUserInfo($snsUid);
            $tempArr['sns_uid'] = $snsUid;
            $returnArr[] = $tempArr;
        }
        return $returnArr;
    }

    /**
     * 判断指定的用户是否是我的好友
     * @param string/int $friendSnsUid 好友的sns uid
     * @return true/false
     */
    public function isMyFriend($friendSnsUid) {
        $this->_globalParamArr['method'] = 'Friend.areFriends';
        $this->_paramArr = array(
            'uids1' => $this->_snsUid,
            'uids2' => $friendSnsUid,
        );
        $responseArr = $this->__doRequest();
        return ($responseArr == 1 || $responseArr == true);
    }

    /**
     * 检查文本中是否存在敏感词
     * @param string $content 内容
     * @return bool
     */
    public function isContentValid($content) {
        return true;
    }

    /**
     * 上传图片
     * @param string $filename 图片文件路径
     */
    public function uploadPhoto($filename) {
        throw new UserException(UserException::ERROR_SYSTEM, 'CALL_UNSUPPORTED_METHOD', __FUNCTION__);
    }

    /**
     * 预存入用户在应用中消费产生的订单数据，消费金额等信息，返回保证一个用户某次在一个应用中支付人人豆安全性的Token
     * @param int $orderId 用户消费校内豆订单号，参数必须保证唯一，每一次不能传递相同的参数。
     * @param int $amount 校内豆消费数额, 取值范围为[0,100]
     * @param string $desc 用户使用校内豆购买的虚拟物品的名称
     * @param int $type 0代表WEB支付订单，1代表WAP支付订单，默认值为0
     * @return string
     */
    public function payRegOrder($orderId, $amount, $desc, $type = 0) {
        throw new UserException(UserException::ERROR_SYSTEM, 'CALL_UNSUPPORTED_METHOD', __FUNCTION__);
    }

    /**
     * 查询指定的订单消费是否完成
     * @param int $orderId 订单号
     * @return int
     */
    public function payIsCompleted($orderId) {
        throw new UserException(UserException::ERROR_SYSTEM, 'CALL_UNSUPPORTED_METHOD', __FUNCTION__);
    }

    /**
     * 发布Feed
     * @param <type> $paramsArr
     * @link http://app.my.4399.com/wiki/API/feed/Feed.publishTemplatizedAction.html
     */
    public function publishFeed($paramsArr){
        $this->_globalParamArr['method'] = 'Feed.publishTemplatizedAction';
        $this->_paramArr = $paramsArr;
        $responseArr = $this->__doRequest();
        return $responseArr['result'];
    }
}
?>
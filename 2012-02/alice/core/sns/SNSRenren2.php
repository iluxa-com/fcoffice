<?php
/**
 * 人人网开放API接口类
 *
 * @link http://wiki.dev.renren.com/wiki/API
 * @author xianlinli@gmail.com
 * @package Alice
 */
class SNSRenren2 {
    /**
     * API URL
     * @var string
     */
    private $_apiUrl = 'http://api.renren.com/restserver.do';
    /**
     * 安全密钥
     * @var string
     */
    private $_secretKey = '6efce65adcfe46118d8fcfb839b7158d';
    /**
     * 全局参数数组
     * @var array
     */
    private $_globalParamArr = array(
        'api_key' => '5c0dc37fc13d4f239ce10d6ed2def64e',
        'format' => 'JSON',
        'v' => '1.0',
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
        if (isset($_GET['xn_sig_user']) && isset($_GET['xn_sig_session_key'])) { // 尚未登录,取GET参数
            $this->_snsUid = $_GET['xn_sig_user'];
            $this->_sessionKey = $_GET['xn_sig_session_key'];
        } else { // 已登录,取SESSION中的数据
            $currentUser = App::getCurrentUser();
            $this->_snsUid = $currentUser['sns_uid'];
            $this->_sessionKey = $currentUser['sns_session_key'];
        }
        if ($this->_snsUid === '' || $this->_sessionKey === '') {
            throw new UserException(UserException::ERROR_SNS_INVALID_PARAM, 'SNS_INVALID_PARAM', $this->_snsUid, $this->_sessionKey);
        }
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
     * @param bool $hasPostFile 是否有上传文件
     * @return string
     */
    private function __getPostFields($hasPostFile) {
        $tempArr = array_merge($this->_globalParamArr, $this->_paramArr);
        $tempArr['call_id'] = microtime(true);
        ksort($tempArr);
        $str = '';
        foreach ($tempArr as $key => $val) {
            if ($hasPostFile && $key === 'upload') {
                continue;
            }
            $str .= $key . '=' . $val;
        }
        $tempArr['sig'] = md5($str . $this->_secretKey);
        if ($hasPostFile) {
            return $tempArr;
        } else {
            return http_build_query($tempArr, '', '&');
        }
    }

    /**
     * 执行请求
     * @param bool $hasPostFile 是否有上传文件
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
            CURLOPT_POSTFIELDS => $this->__getPostFields($hasPostFile),
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
        if (isset($responseArr['error_code'])) {
            switch ($responseArr['error_code']) {
                case 450: // 当前用户的sessionKey过期
                //case 451: // Session key specified cannot be used to call this method
                case 452: // Session key 无效. 可能传入的sessionKey格式出现错误
                    throw new UserException(UserException::ERROR_SNS_SESSION_EXPIRED, 'SNS_SESSION_EXPIRED', $responseStr);
                    break;
            }
            throw new UserException(UserException::ERROR_SNS_API_FAIL, 'SNS_API_FAIL', $responseStr);
        }
        return $responseArr;
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
        $this->_paramArr = array(
            'method' => 'users.getInfo',
            'session_key' => $this->_sessionKey,
            'uids' => $snsUid,
            'fields' => 'name,headurl,zidou,vip',
        );
        $responseArr = $this->__doRequest();
        $returnArr = array(
            'username' => $responseArr[0]['name'],
            'head_img' => $responseArr[0]['headurl'],
            'is_vip' => isset($responseArr[0]['zidou']) ? $responseArr[0]['zidou'] : 0,
            'vip_level' => isset($responseArr[0]['vip']) ? $responseArr[0]['vip'] : 0,
        );
        return $returnArr;
    }

    /**
     * 获取好友列表
     * @return array
     */
    public function getFriends() {
        $this->_paramArr = array(
            'method' => 'friends.getAppFriends',
            'session_key' => $this->_sessionKey,
            'fields' => 'uid,name,headurl,zidou,vip',
        );
        $responseArr = $this->__doRequest();
        $returnArr = array();
        foreach ($responseArr as $tempArr) {
            $returnArr[] = array(
                'sns_uid' => $tempArr['uid'],
                'username' => $tempArr['name'],
                'head_img' => $tempArr['headurl'],
                'is_vip' => isset($tempArr['zidou']) ? $tempArr['zidou'] : 0,
                'vip_level' => isset($tempArr['vip']) ? $tempArr['vip'] : 0,
            );
        }
        return $returnArr;
    }

    /**
     * 判断指定的用户是否是我的好友
     * @param string/int $friendSnsUid 好友的sns uid
     * @return true/false
     */
    public function isMyFriend($friendSnsUid) {
        $this->_paramArr = array(
            'method' => 'friends.areFriends',
            'session_key' => $this->_sessionKey,
            'uids1' => $this->_snsUid,
            'uids2' => $friendSnsUid,
        );
        $responseArr = $this->__doRequest();
        return ($responseArr[0]['are_friends'] == 1);
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
        $this->_paramArr = array(
            'method' => 'photos.upload',
            'session_key' => $this->_sessionKey,
            'upload' => '@' . $filename,
            'aid' => 0,
        );
        $responseArr = $this->__doRequest(true);
        return $responseArr;
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
        $this->_paramArr = array(
            'method' => 'pay4Test.regOrder',
            'session_key' => $this->_sessionKey,
            'order_id' => $orderId,
            'amount' => $amount,
            'desc' => $desc,
            'type' => $type,
        );
        $responseArr = $this->__doRequest();
        return $responseArr['token'];
    }

    /**
     * 查询指定的订单消费是否完成
     * @param int $orderId 订单号
     * @return int
     */
    public function payIsCompleted($orderId) {
        $this->_paramArr = array(
            'method' => 'pay4Test.isCompleted',
            'session_key' => $this->_sessionKey,
            'order_id' => $orderId,
        );
        $responseArr = $this->__doRequest();
        return $responseArr['result'];
    }
}
?>
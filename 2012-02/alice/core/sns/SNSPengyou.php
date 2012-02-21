<?php
/**
 * TX-朋友开放API接口类
 *
 * @link http://wiki.opensns.qq.com/wiki/API%E6%96%87%E6%A1%A3
 * @author xianlinli@gmail.com
 * @package Alice
 */
class SNSPengyou {
    /**
     * API URL
     * @var string
     */
    private $_apiUrl = 'http://113.108.86.20';
    /**
     * 全局参数数组
     * @var array
     */
    private $_globalParamArr = array(
        'appid' => 27790, // 应用的ID
        'appkey' => '0bb699ce7e7f4d01bcd01267622a0f26', // 应用的密钥
        'appname' => 'app27790', // 应用的英文名
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
        if (isset($_GET['openid']) && isset($_GET['openkey'])) { // 尚未登录,取GET参数
            $this->_snsUid = $_GET['openid'];
            $this->_sessionKey = $_GET['openkey'];
        } else { // 已登录,取SESSION中的数据
            $currentUser = App::getCurrentUser();
            $this->_snsUid = $currentUser['sns_uid'];
            $this->_sessionKey = $currentUser['sns_session_key'];
        }
        if (!preg_match('/^[0-9a-f]{32}$/i', $this->_snsUid) || !preg_match('/^[0-9a-f]+$/i', $this->_sessionKey)) {
            throw new UserException(UserException::ERROR_SNS_INVALID_PARAM, 'SNS_INVALID_PARAM', $this->_snsUid, $this->_sessionKey);
        }
        $this->_globalParamArr['openid'] = $this->_snsUid;
        $this->_globalParamArr['openkey'] = $this->_sessionKey;
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
        $tempArr = array_merge($this->_globalParamArr, $this->_paramArr);
        return http_build_query($tempArr, '', '&');
    }

    /**
     * 执行请求
     * @param string $queryPath 查询路径
     * @return array
     */
    private function __doRequest($queryPath) {
        $ch = curl_init();
        $optionArr = array(
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->_apiUrl . $queryPath,
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
        if ($responseArr['ret'] != 0) {
            switch ($responseArr['ret']) {
                case 1002: // 用户没有登录态，没有权限或者appid错误
                    throw new UserException(UserException::ERROR_SNS_SESSION_EXPIRED, 'SNS_SESSION_EXPIRED', $responseStr);
                    break;
            }
            throw new UserException(UserException::ERROR_SNS_API_FAIL, 'SNS_API_FAIL', $responseStr);
        }
        return $responseArr;
    }

    /**
     * 获取好友详细信息
     * @param string $fopenids 需要获取数据的openid列表，中间以_隔开，每次最多100个
     * @return array
     */
    private function __getMultiInfo($fopenids) {
        $this->_paramArr = array(
            'fopenids' => $fopenids,
        );
        $responseArr = $this->__doRequest('/user/multi_info');
        $extInfoArr = array();
        foreach ($responseArr['items'] as $arr) {
            $extInfoArr[$arr['openid']] = array(
                'sns_uid' => $arr['openid'],
                'username' => $arr['nickname'],
                'head_img' => $arr['figureurl'],
                'is_vip' => $arr['is_vip'],
                'is_year_vip' => isset($arr['is_year_vip']) ? $arr['is_year_vip'] : false,
                'vip_level' => isset($arr['vip_level']) ? $arr['vip_level'] : 0,
            );
        }
        return $extInfoArr;
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
        $this->_paramArr = array();
        $responseArr = $this->__doRequest('/user/info');
        $returnArr = array(
            'username' => $responseArr['nickname'],
            'head_img' => $responseArr['figureurl'],
            'is_vip' => $responseArr['is_vip'],
            'is_year_vip' => $responseArr['is_year_vip'],
            'vip_level' => isset($responseArr['vip_level']) ? $responseArr['vip_level'] : 0,
        );
        return $returnArr;
    }

    /**
     * 获取好友列表
     * @return array
     */
    public function getFriends() {
        $this->_paramArr = array(
            'infoed' => 0,
            'apped' => 1, // 对此应用的安装情况(-1:没有安装;1:安装了的;0:所有好友)
            'page' => 0,
        );
        $responseArr = $this->__doRequest('/relation/friends');
        $allOpenidArr = array();
        foreach ($responseArr['items'] as $arr) {
            $allOpenidArr[] = $arr['openid'];
        }
        $extInfoArr = array();
        while (count($allOpenidArr) > 0) {
            $openidArr = array_splice($allOpenidArr, 0, 100);
            if (empty($openidArr)) {
                break;
            }
            $extInfoArr = array_merge($extInfoArr, $this->__getMultiInfo(implode('_', $openidArr)));
        }
        $returnArr = array();
        foreach ($extInfoArr as $arr) {
            $returnArr[] = $arr;
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
            'fopenid' => $friendSnsUid,
        );
        $responseArr = $this->__doRequest('/relation/is_friend');
        return ($responseArr['isFriend'] >= 1);
    }

    /**
     * 检查文本中是否存在敏感词
     * @param string $content 内容
     * @return bool
     */
    public function isContentValid($content) {
        return true;
        $this->_paramArr = array(
            'content' => $content,
            'domain' => 2, // 1：Qzone； 2：腾讯朋友；3：腾讯微博；4：Q+平台；5：财付通开放平台；10：QQGame.
        );
        $responseArr = $this->__doRequest('/csec/word_filter');
        return ($responseArr['ret'] == 0);
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
     * @return string/false
     */
    public function payRegOrder($orderId, $amount, $desc, $type = 0) {
        throw new UserException(UserException::ERROR_SYSTEM, 'CALL_UNSUPPORTED_METHOD', __FUNCTION__);
    }

    /**
     * 查询指定的订单消费是否完成
     * @param int $orderId 订单号
     * @return int/false
     */
    public function payIsCompleted($orderId) {
        throw new UserException(UserException::ERROR_SYSTEM, 'CALL_UNSUPPORTED_METHOD', __FUNCTION__);
    }

    /**
     * 校验邀请Key
     * @link http://wiki.opensns.qq.com/wiki/fusion.dialog.inviteFriend
     * @return string/false 检验成功返回邀请人的openid，失败返回false
     */
    public function checkInviteKey() {
        // invkey = md5(myopenid_iopenid_appid_appkey_itime);
        $myOpenId = isset($_GET['myopenid']) ? $_GET['myopenid'] : '';
        $iOpenId = isset($_GET['iopenid']) ? $_GET['iopenid'] : '';
        $iTime = isset($_GET['itime']) ? $_GET['itime'] : '';
        $invKey = isset($_GET['invkey']) ? $_GET['invkey'] : '';
        if (md5($myOpenId . '_' . $iOpenId . '_' . $this->_globalParamArr['appid'] . '_' . $this->_globalParamArr['appkey'] . '_' . $iTime) == $invKey) {
            return $iOpenId;
        } else {
            return false;
        }
    }
}
?>
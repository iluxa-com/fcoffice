<?php
/**
 * SNSTest类(开发测试用,正式环境不需要)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class SNSTest {
    /**
     * 访问控制键
     * @var string
     */
    const ACL_KEY = 'SNS_ACL';
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
     * 构造函数
     */
    public function __construct() {
        if (isset($_GET['sns_uid']) && isset($_GET['sns_session_key'])) { // 尚未登录,取GET参数
            $this->_snsUid = $_GET['sns_uid'];
            $this->_sessionKey = $_GET['sns_session_key'];
        } else { // 已登录,取SESSION中的数据
            $currentUser = App::getCurrentUser();
            $this->_snsUid = $currentUser['sns_uid'];
            $this->_sessionKey = $currentUser['sns_session_key'];
        }
        if (!preg_match('/^[0-9a-f]{32}$/i', $this->_snsUid) || $this->_sessionKey === '') {
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
     * 获取指定用户的信息
     * @param string $snsUid sns uid
     * @return array/false
     */
    public function getUserInfo($snsUid = NULL) {
        if ($snsUid === NULL) {
            $snsUid = $this->_snsUid;
        }
        $snsUserModel = new SNSUserModel($snsUid);
        $snsUserDataArr = $snsUserModel->hGetAll();
        if (empty($snsUserDataArr)) {
            return false;
        }
        $returnArr = array(
            'username' => $snsUserDataArr['nickname'],
            'head_img' => $snsUserDataArr['figureurl'],
            'is_vip' => $snsUserDataArr['is_vip'],
            'vip_level' => $snsUserDataArr['vip_level'],
            'is_year_vip' => $snsUserDataArr['is_year_vip'],
            'idcard' => isset($snsUserDataArr['idcard']) ? $snsUserDataArr['idcard'] : '',
        );
        return $returnArr;
    }

    /**
     * 获取好友
     * @return array
     */
    public function getFriends() {
        // 获取默认好友
        $defaultFriendInfoArr = App::get('DefaultFriend', false);
        $defaultSnsUid = NULL;
        if ($defaultFriendInfoArr !== false) {
            $defaultSnsUid = $defaultFriendInfoArr['sns_uid'];
        }
        $relationModel = new RelationModel('*');
        $keyArr = $relationModel->RH()->getKeys($relationModel->getStoreKey());
        $relationModel->RH()->multi(Redis::PIPELINE);
        foreach ($keyArr as $key) {
            $snsUid = substr($key, 3);
            if ($snsUid == $defaultSnsUid) { // 跳过默认好友
                continue;
            }
            $snsUserModel = new SNSUserModel($snsUid);
            $snsUserModel->hMget(array('sns_uid', 'nickname', 'figureurl', 'is_vip', 'vip_level', 'is_year_vip'));
        }
        $tempArr = $relationModel->RH()->exec();
        $returnArr = array();
        foreach ($tempArr as $snsUserDataArr) {
            $returnArr[] = array(
                'sns_uid' => $snsUserDataArr['sns_uid'],
                'username' => $snsUserDataArr['nickname'],
                'head_img' => $snsUserDataArr['figureurl'],
                'is_vip' => $snsUserDataArr['is_vip'],
                'vip_level' => $snsUserDataArr['vip_level'],
                'is_year_vip' => $snsUserDataArr['is_year_vip'],
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
        return true;
    }

    /**
     * 检查文本中是否存在敏感词
     * @param string $content 内容
     * @return bool
     */
    public function isContentValid($content) {
        $dataStr = file_get_contents(SNS_DIR . DIRECTORY_SEPARATOR . 'filter_words.lst');
        $dataArr = preg_split('/[\r\n]+/', $dataStr, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($dataArr as $val) {
            if (strpos($content, $val) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * 上传图片
     * @param string $filename 图片文件路径
     */
    public function uploadPhoto($filename) {
        throw new UserException(UserException::ERROR_SYSTEM, 'CALL_UNSUPPORTED_METHOD', __FUNCTION__);
    }
}
?>
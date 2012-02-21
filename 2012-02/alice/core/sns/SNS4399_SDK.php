<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "sdk/My4399.php";
class SNS4399 {
    /**
     * 全局参数数组
     * @var array
     */
    private $_globalParamArr = array(
        'api_key' => '4a7aa5709e8f77d3fc4fede52fae2350',
        'secret_key' => 'c22015d4d060cc7a21e32541c8fb2441',
        'version' => '1.0',
    );
    /**
     * Api对象
     */
    private $_globalObj = '';
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
     * 构造函数，初始化4399平台API
     */
    public function __construct() {
        //判断是否登录
        if (isset($_GET['my_sig_uId']) && isset($_GET['my_sig_sessionId'])) {
            $this->_snsUid = $_GET['my_sig_uId'];
            $this->_sessionKey = $_GET['my_sig_sessionId'];
        } else {
            $currentUser = App::getCurrentUser();
            $this->_snsUid = $currentUser['sns_uid'];
            $this->_sessionKey = $currentUser['sns_session_key'];
        }

        if ($this->_snsUid === '' || $this->_sessionKey === '') {
            throw new UserException(UserException::ERROR_SNS_INVALID_PARAM, 'SNS_INVALID_PARAM', $this->_snsUid, $this->_sessionKey);
        } else {
            $this->_globalObj = new Manyou($this->_globalParamArr['api_key'], $this->_globalParamArr['secret_key']);    //new 一个平台API对象
        }
    }

    /**
     * 获取Sns Uid
     * @return <type>
     */
    public function getSNSuid() {
        return $this->_snsUid;
    }

    /**
     * 获取Sns sessionID
     * @return <type>
     */
    public function getSessionKey() {
        return $this->_sessionKey;
    }

    /**
     * 获取指定用户信息
     */
    public function getUserInfo($snsUid = NULL) {
        if ($snsUid === NULL) {
            $snsUid = $this->_snsUid;
        }
        $users = $this->_globalObj->api_client->user_getInfo($snsUid, array('uid', 'name', 'pic_thumb'));
        return $users;
    }

    /**
     * 判断当前用户安装了应用没有
     * @return <Boolean> 0---没有安装 1----安装了
     */
    public function isAppAdded() {
        $retStatus = $this->_globalObj->api_client->user_isAppAdded();
        if ($retStatus == 1 || $retStatus == true) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 返回当前用户权限级别
     * @return <Strin> USER（普通用户）、MANAGER（站点管理员）或FOUNDER（站点创始人）
     */
    public function getLoggedInUserLevel() {
        $group = $this->_globalObj->api_client->user_getLoggedInUserLevel();
        return $group;
    }

    /**
     * 获取当前登录用户的好友ID
     * @return <type>
     */
    public function getFriendsID() {
        $friendsId = $this->_globalObj->api_client->friend_get();
        return $friendsId;
    }

    /**
     * 获取指定好友的好友信息
     * @staticvar array $friendsInfo   Array like array(1=>array('uid'=>1,'name'=>'fanhou','pic_thumb'=>'xxx'))
     * @param $type Int 0 or 1 默认0，返回当前用户所有好友信息，1返回当前用户添加了应用的好友信息
     * @return <Array>
     */
    public function getFriendsInfo($type = 0) {
        static $friendsInfo = array();
        //当为默认0的时候，返回当前用户所有好友ID
        if ($type == 0) {
            $friendArr = $this->getFriendsID();
        }
        //为1的时候，返回当前用户所有好友中安装了应用的好友ID
        if ($type == 1) {
            $friendArr = $this->getAppUsersIDFromFriends();
        }
        if (empty($friendArr)) {
            return;
        }
        foreach ($friendArr as $friendID) {
            $friendsInfo[$friendID] = $this->getUserInfo($friendID);
        }
        return $friendsInfo;
    }

    /**
     * 判断是不是当前用户的好友
     * @param <type> $friendId  Int 好友Id
     * @param <type> $snsUid Int 当前用户Id
     * @return <Int> 0---不是，1---是
     */
    public function isFriend($friendId = NULL, $snsUid = NULL) {
        if ($snsUid === NULL) {
            $snsUid = $this->_snsUid;
        }
        if ($friendId === NULL) {
            return;
        }
        $retStatus = $this->_globalObj->api_client->friend_areFriends($friendId, $snsUid);
        if ($retStatus == 1 || $retStatus == TRUE) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 返回当前用户好友中，安装了当前应用的好友ID
     * @return <array>
     */
    public function getAppUsersIDFromFriends() {
        $friendsID = $this->_globalObj->api_client->friend_getAppUsers();
        return $friendsID;
    }
}
?>

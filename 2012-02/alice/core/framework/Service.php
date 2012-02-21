<?php
/**
 * 服务基类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
abstract class Service {
    /**
     * 互斥锁互斥时间(秒)
     * @var float
     */
    protected $_mutexTime = 2.5;
    /**
     * 是否开启重复登录检测
     * @var bool
     */
    protected $_multiLoginCheck = true;
    /**
     * 互斥锁模型实例
     * @var MutexModel
     */
    private $_mutexModel;
    /**
     * 当前用户信息
     * @var array
     */
    protected $_currentUser;
    /**
     * 当前登录用户的用户ID
     * @var int
     */
    protected $_userId;
    /**
     * 用户模型实例
     * @var UserModel
     */
    protected $_userModel;
    /**
     * 数据数组(必须为数组,默认为空数组)
     * @var array
     */
    protected $_data = array();
    /**
     * 返回代码(默认为-1)
     * @var int
     */
    protected $_ret = -1;
    /**
     * 服务调用序号
     * @var int
     */
    protected $_index = -1;

    /**
     * 构造函数
     * @param array $configArr array($service, $action, $index, $lastLogin)
     */
    public function __construct($configArr) {
        list($service, $action, $index, $lastLogin) = $configArr;
        $this->_index = $index;
        // 获取当前用户信息
        $this->_currentUser = App::getCurrentUser();
        // 用户ID多处用到,单独保存下来,以方便获取(位置不要随便改动)
        $this->_userId = $this->_currentUser['user_id'];
        // 供非服务类调用(使用App::get('user_id')获取)
        App::set('user_id', $this->_userId);
        // 并发互斥处理
        if ($this->_mutexTime > 0) { // 开启互斥
            $this->_mutexModel = new MutexModel($this->_userId, $service, $action);
            if ($this->_mutexModel->addMutex($this->_mutexTime) === false) {
                throw new UserException(UserException::ERROR_SERVER_BUSY, 'SERVER_IS_BUSY');
            }
        }
        // 获取当前用户模型实例
        $this->_userModel = App::getInst('UserModel', $this->_userId, false, $this->_userId);
        // 检查用户是否已被禁用
//        if ($this->_userModel->hGet('status') === '0') {
//            $this->_mutexModel && $this->_mutexModel->removeMutex();
//            throw new UserException(UserException::ERROR_ACCOUNT_BLOCKED, 'ERROR_ACCOUNT_BLOCKED');
//        }
        // 检查帐号是否重复登录
        if ($this->_multiLoginCheck && ($lastLogin !== NULL && $service !== 'GameInitService' && $this->_userModel->hGet('last_login') !== $lastLogin)) {
            $this->_mutexModel && $this->_mutexModel->removeMutex();
            throw new UserException(UserException::ERROR_ACCOUNT_MULTI_LOGIN, 'ERROR_ACCOUNT_MULTI_LOGIN');
        }
    }

    /**
     * 输出服务结果
     */
    final public function outputResult() {
        if (!isset($this->_data['filename'])) { // 无视图,输出json格式数据
            if ($this->_ret != 0 && defined('LOG_FAIL_SERVICE') && constant('LOG_FAIL_SERVICE')) { // 记录失败的服务
                $post = $_POST;
                $get = $_GET;
                if (defined('GPC_SLASHES_ADDED')) {
                    !empty($post) && App::stripSlashes($post);
                    !empty($get) && App::stripSlashes($get);
                }
                Debug::log(array('post' => $post, 'get' => $get, 'ret' => $this->_ret, 'data' => $this->_data), Debug::TYPE_SERVICE_FAIL + $this->_ret);
            }
            echo json_encode(array_merge(array('ret' => $this->_ret, 'index' => $this->_index), $this->_data));
        } else { // 有视图,加载视图文件
            $D = $this->_data;
            require_once BASE_DIR . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $D['filename'];
        }
    }

    /**
     * 析构函数(注意:试图在析构函数中抛出一个异常会导致致命错误)
     */
    public function __destruct() {
        $this->_mutexModel && $this->_mutexModel->removeMutex();
    }
}
?>
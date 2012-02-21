<?php
/**
 * 管理服务类(后台使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class AdminService extends AuthService {
    /**
     * 查看环境
     */
    public function showEnv() {
        $this->_data = array(
            'filename' => 'admin_show_env.php',
            'is_test_env' => isset($_COOKIE['ALICE_TEST_ENV']),
        );
        $this->_ret = 0;
    }

    /**
     * 切换环境
     */
    public function doSwitchEnv() {
        if (!in_array(App::get('Platform'), array('4399'))) {
            $this->_data['msg'] = 'This platform is not supported!';
            return;
        }
        $key = 'ALICE_TEST_ENV';
        $isTestEnv = isset($_COOKIE[$key]);
        if ($isTestEnv) { // 有设置,清掉
            setcookie($key, '', 0);
        } else { // 没设置,设置
            setcookie($key, 1, time() + 86400);
        }
        $paramArr = array(
            'is_test_env' => !$isTestEnv,
        );
        $this->_data = array(
            'callback' => 'ajaxCallback',
            'params' => json_encode($paramArr),
        );
        $this->_ret = 0;
    }

    /**
     * 获取jsonMaker模板数据
     */
    public function getJsonMakerTemplate() {
        $this->_data['filename'] = 'admin_json_maker.php';
    }

    /**
     * 显示快捷方式
     */
    public function showShortcut() {
        $this->_data['filename'] = 'admin_show_shortcut.php';
    }

    /**
     * 显示资源发布页面
     */
    public function showResourcePublish() {
        $circleCacheModel = new CircleCacheModel(0, 'SETTING');
        $this->_data['filename'] = 'admin_show_resource_publish.php';
        $this->_data['setting'] = $circleCacheModel->hMget(array('sign_rc_preloading', 'sign_rc_config', 'sign_ga_preloading', 'sign_ga_config'));
    }

    /**
     * 执行资源发布
     */
    public function doResourcePublish() {
        $key = isset($_GET['key']) ? $_GET['key'] : '';
        $val = isset($_GET['val']) ? $_GET['val'] : '';
        if ($key === '' || $val === '') {
            $this->_data['msg'] = 'invalid param';
            return;
        }
        $circleCacheModel = new CircleCacheModel(0, 'SETTING');
        $serverGroup = $circleCacheModel->getServerGroup();
        $serverConfigArr = App::get('RedisServer');
        foreach ($serverConfigArr[$serverGroup] as $nodeId => $config) {
            $circleId = $nodeId - 1;
            $circleCacheModel = new CircleCacheModel($circleId, 'SETTING');
            if ($circleCacheModel->hSet($key, $val) === false) {
                $this->_data['msg'] = 'hSet fail';
                return;
            }
        }
        $this->_data['msg'] = 'update ok';
        $this->_ret = 0;
    }

    /**
     * 显示调试日志
     */
    public function showDebugLog() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'create_time';
        $orderMethod = isset($_GET['orderMethod']) ? $_GET['orderMethod'] : 'DESC';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : array();
        $defaultFilter = array(
            'log_type' => '',
            'user_id' => '',
            'date' => '',
        );
        $filter = array_merge($defaultFilter, $filter);
        $whereArr = array();
        foreach ($filter as $key => $val) {
            switch ($key) {
                case 'log_type':
                case 'user_id':
                    if ($val !== '') {
                        $whereArr[$key] = $val;
                    }
                    break;
                case 'date':
                    if ($val !== '') {
                        $start = strtotime($val, CURRENT_TIME);
                        $end = $start + 86400;
                        $whereArr['>='] = array(
                            'create_time' => $start,
                        );
                        $whereArr['<'] = array(
                            'create_time' => $end,
                        );
                    }
                    break;
            }
        }
        $whereArr2 = $whereArr;
        unset($whereArr2['log_type']);
        $pageSize = 10;
        $debugLogSQLModel = new DebugLogSQLModel();
        $count = $debugLogSQLModel->SH()->find($whereArr)->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $this->_data = array(
            'filename' => 'debug_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'filter' => $filter,
            'data1' => $debugLogSQLModel->SH()->find($whereArr2)->groupBy(array('log_type' => 'ASC'))->fields('log_type, count(*) as total')->getAll(),
            'data2' => $debugLogSQLModel->SH()->find($whereArr)->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll(),
        );
    }

    /**
     * 执行帐号调试
     */
    public function doAccountDebug() {
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
        if (empty($userId) || !is_numeric($userId)) {
            exit('No user id or bad user id!');
        }
        $userModel = new UserModel($userId);
        if (!$userModel->exists()) {
            exit('User not exists!');
        }
        $currentUser = App::getCurrentUser(false);
        if (empty($currentUser)) {
            exit('Please login your game account first!');
        }
        $currentUser['user_id_old'] = $currentUser['user_id'];
        $currentUser['user_id'] = $userId;
        App::setCurrentUser($currentUser);
        header('Location: index.php?ref=account_debug');
        exit;
    }
}
?>
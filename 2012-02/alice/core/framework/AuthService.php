<?php
/**
 * 认证服务类
 *
 * @author xianlinli@gmail.com
 */
abstract class AuthService extends Service {
    /**
     * 构造函数
     * @param array $configArr array($service, $action, $index, $lastLogin)
     */
    public function __construct($configArr) {
        list($service, $action, $index, $lastLogin) = $configArr;
        $accessArr = array(
            'AdminUserService->doAdd' => array('superadmin'),
            'AdminUserService->doUpdate' => array('superadmin'),
            'AdminUserService->doDelete' => array('superadmin'),
            'AdminUserService->doPasswordUpdate' => array('superadmin'),
            'AdminUserService->showList' => array('superadmin'),
            'ItemDataService->doAdd' => array('admin'),
            'ItemDataService->doUpdate' => array('admin'),
            'ItemDataService->doSync' => array('sync'),
            'TaskDataService->doAdd' => array('admin'),
            'TaskDataService->doUpdate' => array('admin'),
            'TaskDataService->doSync' => array('sync'),
            'UserDataService->doReset' => array('reset'),
        );
        // 在这里进行认证(预留)
        if (!in_array(App::get('Platform'), array('Devel', 'Local'))) {
            $aclArr = isset($_SESSION['ALICE_ADMIN_ACL']) ? $_SESSION['ALICE_ADMIN_ACL'] : array();
            if (empty($aclArr)) {
                exit('<script type="text/javascript">top.location.replace("admin/login.php");</script>');
            }
            $key = $service . '->' . $action;
            $userRoleArr = explode(',', $aclArr['roles']);
            if (array_key_exists($key, $accessArr)) { // 需要特定的权限
                $needRoleArr = $accessArr[$key];
            } else { // 需要user权限
                $needRoleArr = array('user');
            }
            $interArr = array_intersect($needRoleArr, $userRoleArr);
            if (empty($interArr)) {
                exit('This operation need roles: ' . implode(',', $needRoleArr));
            }
        }
        //parent::__construct($configArr);
    }
}
?>
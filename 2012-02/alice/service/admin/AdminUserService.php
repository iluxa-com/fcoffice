<?php
/**
 * 后台用户管理服务类(后台使用)
 * 
 * @author seasonxin@yahoo.cn
 * @package Alice
 */
class AdminUserService extends AuthService {
    /**
     * 显示添加页面
     */
    public function showAdd() {
        $this->_data['filename'] = 'admin_user_show_add.php';
    }

    /**
     * 执行添加
     */
    public function doAdd() {
        $dataArr = $_POST;
        if (!preg_match('/^[0-9a-z_\-\.]+@[0-9a-z\.]+$/i', $dataArr['email'])) {
            $this->_data['msg'] = 'invalid email';
            return;
        }
        $dataArr['email'] = strtolower($dataArr['email']);
        if (!isset($dataArr['password']) || strlen($dataArr['password']) < 5) {
            $this->_data['msg'] = 'password too short';
            return;
        }
        $whereArr = array(
            'email' => $dataArr['email'],
        );
        $adminUserSQLModel = new AdminUserSQLModel();
        $count = $adminUserSQLModel->SH()->find($whereArr)->count();
        if ($count > 0) {
            $this->_data['msg'] = 'user already exists';
            return;
        }
        $dataArr['password'] = md5($dataArr['password']);
        $dataArr['last_login'] = 0;
        $dataArr['last_ip'] = '';
        $dataArr['login_times'] = 0;
        $dataArr['create_time'] = CURRENT_TIME;
        $adminUserSQLModel->SH()->insert($dataArr);
        $this->_data = array(
            'msg' => '添加成功',
        );
        $this->_ret = 0;
    }

    /**
     * 显示列表
     */
    public function showlist() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'email';
        $orderMethod = isset($_GET['orderColumn']) ? $_GET['orderMethod'] : 'ASC';
        $pageSize = 10;
        $adminUserSQLModel = new AdminUserSQLModel();
        $count = $adminUserSQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageoffset = ($page - 1) * $pageSize;
        $this->_data = array(
            'filename' => 'admin_user_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'data' => $adminUserSQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageoffset, $pageSize)->getAll(),
        );
        $this->_ret = 0;
    }

    /*
     * 显示修改页面
     */

    public function showUpdate() {
        if (!isset($_GET['email'])) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $email = $_GET['email'];
        $adminUserSQLModel = new AdminUserSQLModel();
        $this->_data = array(
            'filename' => 'admin_user_show_update.php',
            'data' => $adminUserSQLModel->SH()->find(array('email' => $email))->getOne(),
        );
        $this->_ret = 0;
    }

    /**
     * 执行修改动作
     */
    public function doUpdate() {
        if (!isset($_GET['email']) || empty($_POST)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $email = strtolower($_GET['email']);
        $dataArr = $_POST;
        unset($dataArr['email'], $dataArr['password']);
        $adminUserSQLModel = new AdminUserSQLModel();
        $adminUserSQLModel->SH()->find(array('email' => $email))->update($dataArr);
        $this->_data = array(
            'msg' => '修改成功',
            'back' => true,
        );
        $this->_ret = 0;
    }

    /**
     * 显示密码修改页面
     */
    public function showPasswordUpdate() {
        $email = $_GET['email'];
        $this->_data = array(
            'filename' => 'admin_user_show_update2.php',
            'email' => $email,
        );
    }

    /**
     * 执行修改密码
     */
    public function doPasswordUpdate() {
        if (!isset($_GET['email']) || empty($_POST)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $email = $_GET['email'];
        $dataArr = $_POST;
        $password1 = $dataArr['password1'];
        $password2 = $dataArr['password2'];
        if ($password1 !== $password2) {
            $this->_data['msg'] = 'two password not match';
            return;
        }
        $adminUserSQLModel = new AdminUserSQLModel();
        $whereArr = array(
            'email' => $email,
        );
        $count = $adminUserSQLModel->SH()->find($whereArr)->count();
        if ($count <= 0) {
            $this->_data['msg'] = 'user not exists';
            return;
        }
        $dataArr = array(
            'password' => md5($password1),
        );
        $adminUserSQLModel->SH()->find($whereArr)->update($dataArr);
        $this->_data = array(
            'msg' => '修改成功',
        );
        $this->_ret = 0;
    }

    /**
     * 执行删除动作
     */
    public function doDelete() {
        if (!isset($_GET['email'])) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $email = strtolower($_GET['email']);
        $adminUserSQLModel = new AdminUserSQLModel();
        $adminUserSQLModel->SH()->find(array('email' => $email))->delete();
        $this->_data = array(
            'msg' => '删除成功',
            'refresh' => true,
        );
        $this->_ret = 0;
    }
}
?>

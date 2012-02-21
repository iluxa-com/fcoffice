<?php
/**
 * 地点数据服务类(后台使用)
 * 
 * @author wangr@gmail.com
 * @package Alice
 */

class AreaDataService extends AuthService {
    /**
     * 显示添加的页面
     */
    public function showAdd() {
        $this->_data['filename'] = 'area_show_add.php';
    }

    /**
     * 执行地点增加动作
     * @param $dataStr json编码字符串
     */
    public function doAdd($dataStr = NULL) {
        if (isset($dataStr)) {
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_POST)) {
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $areaDataSQLModel = new AreaDataSQLModel();
        if (isset($dataArr['area_id']) && $areaDataSQLModel->SH()->find(array('area_id' => $dataArr['area_id']))->count() > 0) {
            $this->_data['msg'] = 'place already exists';
            return;
        }
        $areaDataSQLModel->SH()->insert($dataArr);
        $this->_data = array(
            'msg' => '添加成功',
        );
        $this->_ret = 0;
    }

    /**
     * 执行地点列表动作
     */
    public function showList() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'area_id';
        $orderMethod = isset($_GET['orderMethod']) ? $_GET['orderMethod'] : 'ASC';
        $pageSize = 10;
        $areaDataSQLModel = new AreaDataSQLModel();
        $count = $areaDataSQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $this->_data = array(
            'filename' => 'area_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'data' => $areaDataSQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll(),
        );
    }

    /**
     * 显示修改页面
     */
    public function showUpdate() {
        if (isset($_GET['id'])) {
            $areaId = $_GET['id'];
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        $areaDataSQLModel = new AreaDataSQLModel();
        $this->_data = array(
            'filename' => 'area_show_update.php',
            'data' => $areaDataSQLModel->SH()->find(array('area_id' => $areaId))->getOne(),
        );
    }

    /**
     * 执行页面的修改动作
     * @param $areaId 区域地点ID
     */
    public function doUpdate($areaId = NULL, $dataStr = NULL) {
        if (isset($areaId) && isset($dataStr)) {
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_GET['id']) && isset($_POST)) {
            $areaId = $_GET['id'];
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($areaId)) {
            $this->_data['msg'] = 'invalid area id';
            return;
        }
        $areaDataSQLModel = new AreaDataSQLModel();
        $areaDataSQLModel->SH()->find(array('area_id' => $areaId))->update($dataArr);
        $this->_data = array(
            'msg' => '修改成功',
            'back' => true,
        );
        $this->_ret = 0;
    }
}
?>
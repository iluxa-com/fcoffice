<?php
/**
 * 区域数据服务类(后台使用)
 *
 * @author wangr@gmail.com
 * @package Alice
 */
class ZoneDataService extends AuthService {
    /**
     *  显示添加页面
     */
    public function showAdd() {
        $this->_data['filename'] = 'zone_show_add.php';
    }

    /**
     * 执行区域增加的动作
     * @param string $dataStr 
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
        if ($dataArr['zone_id'] == '') {
            unset($dataArr['zone_id']);
        }
        $zoneDataSQLModel = new ZoneDataSQLModel();
        if ($dataArr['zone_id'] && $zoneDataSQLModel->SH()->find(array('zone_id' => $dataArr['zone_id']))->count() > 0) {
            $this->_data['msg'] = 'zone already exists';
            return;
        }
        $zoneDataSQLModel->SH()->insert($dataArr);
        $this->_data = array(
            'msg' => '添加成功',
        );
        $this->_ret = 0;
    }

    /**
     * 显示列表页面
     */
    public function showList() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'zone_id';
        $orderMethod = isset($_GET['orderMethod']) ? $_GET['orderMethod'] : 'ASC';
        $pageSize = 10;
        $zoneDataSQLModel = new ZoneDataSQLModel();
        $count = $zoneDataSQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $zoneDataSQLModel = new ZoneDataSQLModel();
        $tempArr = $zoneDataSQLModel->SH()->fields('zone_id,name')->getAll();
        $nameArr = array();
        foreach ($tempArr as $temp) {
            $nameArr[$temp['zone_id']] = $temp['name'];
        }
        $this->_data = array(
            'filename' => 'zone_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'data' => $zoneDataSQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll(),
            'name' => $nameArr,
        );
    }

    /**
     * 显示修改页面
     */
    public function showUpdate() {
        if (isset($_GET['id'])) {
            $zoneId = $_GET['id'];
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($zoneId)) {
            $this->_data['msg'] = 'invalid zone id';
            return;
        }
        $zoneDataSQLModel = new ZoneDataSQLModel();
        $this->_data = array(
            'filename' => 'zone_show_update.php',
            'data' => $zoneDataSQLModel->SH()->find(array('zone_id' => $zoneId))->getOne(),
        );
    }

    /**
     * 执行修改页面动作
     * @param $zoneId 区域ID
     * @param $dataStr json编码得到的字符串
     */
    public function doUpdate($zoneId = NULL, $dataStr = NULL) {
        if (isset($zoneId) && isset($dataStr)) {
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_GET['id']) && isset($_POST)) {
            $zoneId = $_GET['id'];
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($zoneId)) {
            $this->_data['msg'] = 'invalid zone id';
            return;
        }
        $zoneDataSQLModel = new ZoneDataSQLModel();
        $zoneDataSQLModel->SH()->find(array('zone_id' => $zoneId))->update($dataArr);
        $this->_data = array(
            'msg' => '修改成功',
            'back' => true,
        );
        $this->_ret = 0;
    }
}
?>
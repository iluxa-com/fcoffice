<?php
/**
 * 地点数据服务类(后台使用)
 * 
 * @author wangr@gmail.com
 * @package Alice
 */

class PlaceDataService extends AuthService {
    /**
     * 显示添加的页面
     */
    public function showAdd() {
        $this->_data['filename'] = 'place_show_add.php';
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
        $placeDataSQLModel = new PlaceDataSQLModel();
        if (isset($dataArr['place_id']) && $placeDataSQLModel->SH()->find(array('place_id' => $dataArr['place_id']))->count() > 0) {
            $this->_data['msg'] = 'place already exists';
            return;
        }
        $placeDataSQLModel->SH()->insert($dataArr);
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
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'place_id';
        $orderMethod = isset($_GET['orderMethod']) ? $_GET['orderMethod'] : 'ASC';
        $pageSize = 10;
        $placeDataSQLModel = new PlaceDataSQLModel();
        $count = $placeDataSQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $placeDataSQLModel = new PlaceDataSQLModel();
        $tempArr = $placeDataSQLModel->SH()->getAll();
        $nameArr = array();
        foreach ($tempArr as $temp) {
            $nameArr[$temp['place_id']] = $temp['name'];
        }
        $this->_data = array(
            'filename' => 'place_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'data' => $placeDataSQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll(),
            'name' => $nameArr,
        );
    }

    /**
     * 显示修改页面
     */
    public function showUpdate() {
        if (isset($_GET['id'])) {
            $placeId = $_GET['id'];
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($placeId)) {
            $this->_data['msg'] = 'invalid place id';
            return;
        }
        $placeDataSQLModel = new PlaceDataSQLModel();
        $this->_data = array(
            'filename' => 'place_show_update.php',
            'data' => $placeDataSQLModel->SH()->find(array('place_id' => $placeId))->getOne(),
        );
    }

    /**
     * 执行页面的修改动作
     * @param $placeId 区域地点ID
     */
    public function doUpdate($placeId = NULL, $dataStr = NULL) {
        if (isset($placeId) && isset($dataStr)) {
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_GET['id']) && isset($_POST)) {
            $placeId = $_GET['id'];
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($placeId)) {
            $this->_data['msg'] = 'invalid place id';
            return;
        }
        $placeDataSQLModel = new PlaceDataSQLModel();
        $placeDataSQLModel->SH()->find(array('place_id' => $placeId))->update($dataArr);
        $this->_data = array(
            'msg' => '修改成功',
            'back' => true,
        );
        $this->_ret = 0;
    }
}
?>
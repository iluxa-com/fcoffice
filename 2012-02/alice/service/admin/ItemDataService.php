<?php
/**
 * 道具数据服务类(后台使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ItemDataService extends AuthService {
    /**
     * 显示添加页面
     */
    public function showAdd() {
        $this->_data['filename'] = 'item_show_add.php';
    }

    /**
     * 执行添加
     * @param string $dataStr json编码得到的字符串
     */
    public function doAdd($dataStr = NULL) {
        if (isset($dataStr)) { // json编码的字符串
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_POST)) {
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if ($dataArr['item_id'] === '') {
            unset($dataArr['item_id']);
        }
        $itemDataSQLModel = new ItemDataSQLModel();
        if (isset($dataArr['item_id']) && $itemDataSQLModel->SH()->find(array('item_id' => $dataArr['item_id']))->count() > 0) {
            $this->_data['msg'] = 'item already exists';
            return;
        }
        $itemDataSQLModel->SH()->insert($dataArr);
        $this->_data = array(
            'msg' => '添加成功',
        );
        $this->_ret = 0;
    }

    /**
     * 显示修改页面
     */
    public function showUpdate() {
        if (isset($_GET['id'])) {
            $itemId = $_GET['id'];
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        $itemDataSQLModel = new ItemDataSQLModel();
        $this->_data = array(
            'filename' => 'item_show_update.php',
            'data' => $itemDataSQLModel->SH()->find(array('item_id' => $itemId))->getOne(),
        );
    }

    /**
     * 执行修改
     * @param int $itemId 道具ID
     * @param string $dataStr json编码的字符串
     */
    public function doUpdate($itemId =NULL, $dataStr = NULL) {
        if (isset($dataStr)) { // json编码的字符串
            //$itemId = $itemId;
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_GET['id']) && isset($_POST)) {
            $itemId = $_GET['id'];
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($itemId)) {
            $this->_data['msg'] = 'invalid item id';
            return;
        }
        unset($dataArr['item_id']);
        $itemDataSQLModel = new ItemDataSQLModel();
        $itemDataSQLModel->SH()->find(array('item_id' => $itemId))->update($dataArr);
        $this->_data = array(
            'msg' => '修改成功',
            'back' => true,
        );
        $this->_ret = 0;
    }

    /**
     * 显示列表页面
     */
    public function showList() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'item_id';
        $orderMethod = isset($_GET['orderMethod']) ? $_GET['orderMethod'] : 'ASC';
        $key = isset($_GET['key']) ? $_GET['key'] : '';
        $val = isset($_GET['val']) ? $_GET['val'] : '';
        if (!empty($key) && !empty($val)) {
            $whereArr = array(
                'LIKE' => array(
                    $key => '%' . $val . '%',
                ),
            );
        } else {
            $whereArr = array();
        }
        if (empty($_POST)) {
            $pageSize = 10;
        } else {
            $pageSize = 10000;
        }
        $itemDataSQLModel = new ItemDataSQLModel();
        $count = $itemDataSQLModel->SH()->find($whereArr)->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $this->_data = array(
            'filename' => 'item_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'key' => $key,
            'val' => $val,
            'data' => $itemDataSQLModel->SH()->find($whereArr)->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll(),
        );
    }

    /**
     * 显示同步页面
     */
    public function showSync() {
        $this->_data['filename'] = 'item_show_sync.php';
    }

    /**
     * 执行同步
     */
    public function doSync() {
        $itemDataSQLModel = new ItemDataSQLModel();
        $dataArr = $itemDataSQLModel->SH()->orderBy(array('item_id' => 'ASC'))->getAll();
        if (empty($dataArr)) {
            $this->_data['msg'] = 'no data';
            return;
        }
        $success = 0;
        $itemDataModel2 = new ItemDataModel('*');
        $keyArr = $itemDataModel2->RH()->getKeys($itemDataModel2->getStoreKey());
        if (!empty($keyArr)) {
            $itemDataModel2->RH()->delete($keyArr);
        }
        foreach ($dataArr as $row) {
            $itemId = $row['item_id'];
            $extraInfo = json_decode($row['extra_info'], true);
            $row['expire'] = $extraInfo['expire'];
            $row['tradeable'] = $extraInfo['tradeable'];
            $row['effect'] = json_encode($extraInfo['effect']);
            $itemDataModel = new ItemDataModel($itemId);
            if ($itemDataModel->hMset($row)) {
                ++$success;
            }
        }
        $total = count($dataArr);
        $fail = $total - $success;
        $this->_data = array(
            'msg' => sprintf('道具总数：%d　同步成功：%d　同步失败：%d', $total, $success, $fail),
        );
        $this->_ret = 0;
    }

    /**
     * 显示道具查询页面
     */
    public function showSearch() {
        $this->_data = array(
            'filename' => 'item_show_search.php',
        );
    }

    /**
     * 执行查询
     */
    public function doSearch() {
        $inputStr = $_POST['input'];
        if ($inputStr === '') {
            $this->_data['msg'] = 'no input data';
            return;
        }
        $nameArr = preg_split('/[\s，,、,；;]+/u', $inputStr, -1, PREG_SPLIT_NO_EMPTY);
        $itemDataSQLModel = new ItemDataSQLModel();
        $itemArr = array();
        foreach ($nameArr as $name) {
            $row = $itemDataSQLModel->SH()->find(array('item_name' => $name))->fields('item_id')->getOne();
            if ($row) {
                $itemArr[$name] = $row['item_id'];
            } else {
                $itemArr[$name] = $name; // 不存在,原样返回
            }
        }
        $tempArr = array();
        $i = 0;
        foreach ($itemArr as $name => $itemId) {
            $tempArr[strlen($name) * count($itemArr) + $i++] = $name;
        }
        krsort($tempArr, SORT_NUMERIC);
        foreach ($tempArr as $name) {
            $inputStr = str_replace($name, $itemArr[$name], $inputStr);
        }
        $paramArr = array(
            'output1' => var_export($itemArr, true),
            'output2' => $inputStr,
            'output3' => implode(',', array_values($itemArr)),
        );
        $this->_data = array(
            'callback' => 'ajaxCallback',
            'params' => json_encode($paramArr),
        );
        $this->_ret = 0;
    }
}
?>
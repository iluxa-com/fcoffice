<?php
/**
 * 任务数据服务类(后台使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class TaskDataService extends AuthService {
    /**
     * 显示添加页面
     */
    public function showAdd() {
        $this->_data['filename'] = 'task_show_add.php';
    }

    /**
     * 执行添加
     * @param string $dataStr json编码得到的字符串
     */
    public function addTaskData($dataStr = NULL) {
        if (isset($dataStr)) { // json编码的字符串
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_POST)) {
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (isset($dataArr['task_id']) && $dataArr['task_id'] === '') {
            unset($dataArr['task_id']);
        }
        if (!$this->__checkJsonData('need', $dataArr) || !$this->__checkJsonData('reward', $dataArr)) {
            return;
        }
        $taskDataSQLModel = new TaskDataSQLModel();
        if (isset($dataArr['task_id']) && $taskDataSQLModel->SH()->find(array('task_id' => $dataArr['task_id']))->count() > 0) {
            $this->_data['msg'] = 'task already exists';
            return;
        }
        $taskDataSQLModel->SH()->insert($dataArr);
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
            $taskId = $_GET['id'];
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $taskDataSQLModel = new TaskDataSQLModel();
        $this->_data = array(
            'filename' => 'task_show_update.php',
            'data' => $taskDataSQLModel->SH()->find(array('task_id' => $taskId))->getOne(),
        );
    }

    /**
     * 执行修改
     * @param int $taskId 任务ID
     * @param string $dataStr json编码得到的字符串
     */
    public function updateTaskData($taskId =NULL, $dataStr = NULL) {
        if (isset($taskId) && isset($dataStr)) {
            //$taskId = $taskId;
            $dataArr = json_decode($dataStr, true);
        } else if (isset($_GET['id']) && isset($_POST)) {
            $taskId = $_GET['id'];
            $dataArr = $_POST;
        } else {
            $this->_data['msg'] = 'bad request';
            return;
        }
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        if (!$this->__checkJsonData('need', $dataArr) || !$this->__checkJsonData('reward', $dataArr)) {
            return;
        }
        unset($dataArr['task_id']);
        $taskDataSQLModel = new TaskDataSQLModel();
        $taskDataSQLModel->SH()->find(array('task_id' => $taskId))->update($dataArr);
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
        $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'task_id';
        $orderMethod = isset($_GET['orderMethod']) ? $_GET['orderMethod'] : 'ASC';
        if (empty($_POST)) {
            $pageSize = 10;
        } else {
            $pageSize = 10000;
        }
        $taskDataSQLModel = new TaskDataSQLModel();
        $count = $taskDataSQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $this->_ret = 0;
        $this->_data = array(
            'filename' => 'task_show_list.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'orderColumn' => $orderColumn,
            'orderMethod' => $orderMethod,
            'data' => $taskDataSQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll(),
        );
    }

    /**
     * 显示同步页面
     */
    public function showSync() {
        $this->_data['filename'] = 'task_show_sync.php';
    }

    /**
     * 执行同步
     */
    public function doSync() {
        $taskDataSQLModel = new TaskDataSQLModel();
        $dataArr = $taskDataSQLModel->SH()->orderBy(array('task_id' => 'ASC'))->getAll();
        if (empty($dataArr)) {
            $this->_data['msg'] = 'no data';
            return;
        }
        $success = 0;
        $taskDataModel2 = new TaskDataModel('*');
        $keyArr = $taskDataModel2->RH()->getKeys($taskDataModel2->getStoreKey());
        if (!empty($keyArr)) {
            $taskDataModel2->RH()->delete($keyArr); // 删除所有的任务数据
        }
        $npcTaskSetModel = new NpcTaskSetModel('*');
        $keyArr = $npcTaskSetModel->RH()->getKeys($npcTaskSetModel->getStoreKey());
        if (!empty($keyArr)) {
            $npcTaskSetModel->RH()->delete($keyArr); // 删除NPC任务集合
        }
        $slotTaskSetModel = new SlotTaskSetModel('*');
        $keyArr = $slotTaskSetModel->RH()->getKeys($slotTaskSetModel->getStoreKey());
        if (!empty($keyArr)) {
            $slotTaskSetModel->RH()->delete($keyArr); // 删除发布任务集合
        }
        foreach ($dataArr as $row) {
            $taskId = $row['task_id'];
            $zoneId = $row['zone_id'];
            $grade = $row['grade'];
            $taskDataModel = new TaskDataModel($taskId);
            if ($taskDataModel->hMset($row)) {
                ++$success;
            }
            switch ($row['type']) {
                case '0': // 主线任务(废弃)
                    break;
                case '1': // 系统任务(废弃)
                    break;
                case '2': // NPC任务(支线任务)
                    $npcTaskSetModel = new NpcTaskSetModel($grade);
                    $npcTaskSetModel->sAdd($taskId);
                    break;
                case '3': // 发布任务
                    $slotTaskSetModel = new SlotTaskSetModel($grade);
                    $slotTaskSetModel->sAdd($taskId);
                    break;
                default:
                    throw new UserException(UserException::ERROR_SYSTEM, 'UNKNOWN_TASK_TYPE');
                    break;
            }
        }
        $total = count($dataArr);
        $fail = $total - $success;
        $this->_data = array(
            'msg' => sprintf('任务总数：%d　同步成功：%d　同步失败：%d', $total, $success, $fail),
        );
        $this->_ret = 0;
    }

    /**
     * 显示导出页面
     */
    public function showExport() {
        $this->_data['filename'] = 'task_show_export.php';
    }

    /**
     * 执行导出
     */
    public function doExport() {
        $type = isset($_POST['type']) ? $_POST['type'] : 'data';
        $format = isset($_POST['format']) ? $_POST['format'] : 'json';
        $compress = isset($_POST['compress']) ? $_POST['compress'] : '0';
        $taskDataSQLModel = new TaskDataSQLModel();
        if ($type === 'data') {
            $filename = 'all';
            $dataArr = $taskDataSQLModel->SH()->orderBy(array('task_id' => 'ASC'))->getAll();
            foreach ($dataArr as $task) {
                $file = '/data/www/alicedev/task/' . $task['task_id'] . '.json';
                file_put_contents($file, json_encode($task));
            }
        } else {
            $filename = 'index';
            $dataArr = $taskDataSQLModel->SH()->fields('task_id,npc_id')->orderBy(array('task_id' => 'ASC'))->getAll();
            $file = '/data/www/alicedev/task/index.json';
            file_put_contents($file, json_encode($dataArr));
        }
        $this->_data = array(
            'msg' => $type . '导出成功',
        );
        $this->_ret = 0;
        return;
        switch ($format) {
            case 'csv':
                $csv = new CSV();
                $dataStr = $csv->makeData($dataArr, true);
                break;
            case 'xml':
                $tempArr = array();
                $tempArr[] = '<?xml version="1.0" encoding="utf-8"?>';
                $tempArr[] = '<tasks>';
                foreach ($dataArr as $row) {
                    $tempArr[] = '<task>';
                    foreach ($row as $key => $val) {
                        //$dataStr .='<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
                        $tempArr[] = '<' . $key . '>' . $val . '</' . $key . '>';
                    }
                    $tempArr[] = '</task>';
                }
                $tempArr[] = '</tasks>';
                $dataStr = implode("\n", $tempArr);
                break;
            case 'json':
                $dataStr = json_encode($dataArr);
                break;
            case 'php':
                $dataStr = "<?php\n\$taskDataArr = " . var_export($dataArr, true) . ";\n?>";
                break;
            default:
                $this->_data['msg'] = 'unsupported export type';
                return;
                break;
        }
        $filename .= '.' . $format;
        if ($compress) {
            $filename .= '.gz';
            $dataStr = gzencode($dataStr, 9);
            $format = 'application/x-gzip';
        } else {
            $format = 'application/octet-stream';
        }
        header('Content-Type: ' . $format);
        header('Content-Disposition: attachment; filename=' . $filename);
        exit($dataStr);
    }

    /**
     * 获取任务数据
     * @param int $taskId 任务ID
     */
    public function getTaskData($taskId = NULL) {
        if (!is_numeric($taskId)) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        $taskDataSQLModel = new TaskDataSQLModel();
        $this->_data = array(
            'data' => $taskDataSQLModel->SH()->find(array('task_id' => $taskId))->getOne(),
        );
        $this->_ret = 0;
    }

    /**
     * 检查json数据有效性
     * @param string $field 字段名
     * @param string $dataArr 数据数组
     * @return bool
     */
    private function __checkJsonData($field, $dataArr) {
        if (!array_key_exists($field, $dataArr) || $dataArr[$field] === '') { // 不存在或为空串,直接返回true
            return true;
        }
        $jsonArr = json_decode($dataArr[$field], true); // 解码
        if ($jsonArr === NULL) { // 解码失败
            $this->_data['msg'] = "{$field} json_decode fail";
            return false;
        }
        foreach ($jsonArr as $key => $val) {
            switch ($key) {
                case 'items': // 道具(数组)
                    foreach ($val as $item) {
                        $itemId = $item['id'];
                        $num = $item['num'];
                        if (!is_numeric($itemId) || !is_numeric($num) || $num < 0 || intval($num) != $num) {
                            $this->_data['msg'] = "invalid {$key} @ {$field}";
                            return false;
                        }
                    }
                    break;
                case 'silver': // 金币
                case 'energy': // 能量
                case 'exp': // 经验
                    if (!is_numeric($val) || $val < 0 || intval($val) != $val) {
                        $this->_data['msg'] = "invalid {$key} @ {$field}";
                        return false;
                    }
                    break;
                case 'part': // 部位(服装道具定义时才需要)
                    if (!in_array($val, array('head', 'body_up', 'hand', 'body_down', 'hand', 'socks', 'foot'))) {
                        $this->_data['msg'] = "invalid {$key} @ {$field}";
                        return false;
                    }
                    break;
                case 'friend': // 好友奖励(好友任务)
                    if (!$this->__checkJsonData($field . '.' . $key, $val)) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }
}
?>
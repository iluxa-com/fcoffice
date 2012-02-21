<?php
/**
 * 统计报告服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class ReportService extends Service {
    /**
     * 互斥锁互斥时间(秒)
     * @var float
     */
    protected $_mutexTime = 0;
    /**
     * 是否开启重复登录检测
     * @var bool
     */
    protected $_multiLoginCheck = false;

    /**
     * 发送报告
     * @param string $dataArr 数据数组({"user_id":9527,"type":1,"val1":1,"val2":1,"val3":1,"time":$time})
     */
    public function sendReport($dataArr) {
        if (!is_array($dataArr)) {
            return;
        }
        $dataStr = json_encode($dataArr) . "\n";
        $platform = strtolower(App::get('Platform'));
        if ($platform === 'pengyou') { // 朋友平台,跟其他平台不同
//            $tempArr = array(
//                'user_id' => $dataArr['user_id'],
//                'type' => $dataArr['type'],
//                'val1' => isset($dataArr['val1']) ? $dataArr['val1'] : '',
//                'val2' => isset($dataArr['val2']) ? $dataArr['val2'] : '',
//                'val3' => isset($dataArr['val3']) ? $dataArr['val3'] : '',
//                'time' => $dataArr['time'],
//            );
//            $actionRecordSQLModel = new ActionRecordSQLModel();
//            $actionRecordSQLModel->SH()->insert($tempArr);
            $subKey = intval(CURRENT_TIME / 1800);
            $actionModel = new ActionModel($subKey);
            $actionModel->append($dataStr);
        } else {
            $filename = "/tmp/{$platform}_log/" . date('YmdH', CURRENT_TIME) . '.log';
            if (file_exists(dirname($filename))) { // 路径存在才写,以免报错
                file_put_contents($filename, $dataStr, FILE_APPEND);
            }
        }
        $this->_ret = 0;
    }

    /**
     * 更新新手任务状态
     * @param int $taskId 任务编号
     * @param int $num 数量
     */
    public function updateInitTask($taskId = NULL, $num = NULL) {
        if (!is_numeric($taskId) || $taskId <1 || $taskId > 60) {
            $this->_data['msg'] = 'invalid task id';
            return;
        }
        if ($num === NULL || $num <= 0) {
            $num = 1;
        }
        $initTaskModel = new InitTaskModel();
        if ($initTaskModel->hIncrBy($taskId, $num) === false) {
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_ret = 0;
    }
}
?>
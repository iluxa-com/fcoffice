<?php
/**
 * 统计服务类(后台使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class StatService extends AuthService {
    /**
     * 显示用户统计图
     */
    public function showUserStat() {
        $dataArr = array(
            'type' => isset($_GET['type']) ? $_GET['type'] : 'hour_active',
            'date1' => isset($_GET['date1']) ? $_GET['date1'] : date('Y-m-d', CURRENT_TIME),
            'compare' => isset($_GET['compare']) ? $_GET['compare'] : '1',
            'date2' => isset($_GET['date2']) ? $_GET['date2'] : date('Y-m-d', strtotime('-1 day', CURRENT_TIME)),
        );
        $dataArr['data_file_url'] = GATEWAY_URL . 'gateway.php?service=StatService&action=getUserStatData&' . http_build_query($dataArr, '', '&');
        $dataArr['filename'] = 'stat_show_user.php';
        $this->_data = $dataArr;
    }

    /**
     * 获取用户统计线条数据
     * @param string $model 模型类名
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @param string $fields 字段
     * @param string $labelsArr X坐标
     * @param string $limit 限制个数
     * @return array
     */
    private function __getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit) {
        $obj = App::getInst($model);
        $whereArr = array(
            '>=' => array(
                'date' => $startDate,
            ),
            '<=' => array(
                'date' => $endDate,
            ),
        );
        $orderByArr = array(
            'date' => 'ASC',
        );
        $rowArr = $obj->SH()->find($whereArr)->fields($fields)->orderBy($orderByArr)->getAll();
        $dataArr = array();
        foreach ($rowArr as $row) {
            $dataArr[$row['key2']] = $row['val2'];
        }
        $valArr = array();
        foreach ($labelsArr as $label) {
            $key = substr($label, 0, 2);
            if (intval($key) > $limit) {
                break;
            } else if (isset($dataArr[$key])) {
                $valArr[] = intval($dataArr[$key]);
            } else {
                $valArr[] = 0;
            }
        }
        return $valArr;
    }

    /**
     * 获取用户统计数据
     */
    public function getUserStatData() {
        $type = $_GET['type'];
        $date1 = $_GET['date1'];
        $compare = $_GET['compare'];
        $date2 = $_GET['date2'];
        list($year9, $month9, $day9, $hour9) = explode('-', date('Y-m-d-H', CURRENT_TIME));
        $flashChart = new FlashChart();
        switch ($type) {
            case 'hour_active':
            case 'hour_total';
                $labelsArr = array(
                    '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
                    '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00',
                );
                $model = 'StatUserHourSQLModel';
                if ($type === 'hour_active') {
                    $fields = "DATE_FORMAT(date,'%H') as key2,active as val2";
                } else {
                    $fields = "DATE_FORMAT(date,'%H') as key2,total as val2";
                }
                list($year, $month, $day) = explode('-', date('Y-m-d', strtotime($date1)));
                $limit = ($year9 == $year && $month9 == $month && $day9 == $day) ? $hour9 - 1 : 23;
                $startDate = sprintf('%s-%s-%s 00:00:00', $year, $month, $day);
                $endDate = sprintf('%s-%s-%s 23:00:00', $year, $month, $day);
                $valArr = $this->__getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit);
                $flashChart->addLine($valArr, sprintf('%s年%s月%s日', $year, $month, $day), '#val#', '#ae00ff');
                if ($compare) {
                    list($year, $month, $day) = explode('-', date('Y-m-d', strtotime($date2)));
                    $limit = ($year9 == $year && $month9 == $month && $day9 == $day) ? $hour9 - 1 : 23;
                    $startDate = sprintf('%s-%s-%s 00:00:00', $year, $month, $day);
                    $endDate = sprintf('%s-%s-%s 23:00:00', $year, $month, $day);
                    $valArr = $this->__getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit);
                    $flashChart->addLine($valArr, sprintf('%s年%s月%s日', $year, $month, $day), '#val#');
                }
                break;
            case 'day_active':
            case 'day_total':
                $labelsArr = array(
                    '01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
                    '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
                    '21', '22', '23', '24', '25', '26', '27', '28',
                );
                $t1 = date('t', strtotime($date1));
                $t2 = date('t', strtotime($date2));
                for ($i = 29; $i <= max($t1, $t2); ++$i) {
                    $labelsArr[] = '' . $i;
                }
                $model = 'StatUserDaySQLModel';
                if ($type === 'day_active') {
                    $fields = "DATE_FORMAT(date,'%d') as key2,active as val2";
                } else {
                    $fields = "DATE_FORMAT(date,'%d') as key2,total as val2";
                }
                list($year, $month) = explode('-', date('Y-m', strtotime($date1)));
                $limit = ($year9 == $year && $month9 == $month) ? $day9 - 1 : $t1;
                $startDate = sprintf('%s-%s-01', $year, $month);
                $endDate = sprintf('%s-%s-%s', $year, $month, $t1);
                $valArr = $this->__getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit);
                $flashChart->addLine($valArr, sprintf('%s年%s月', $year, $month), '#val#', '#ae00ff');
                if ($compare) {
                    list($year, $month) = explode('-', date('Y-m', strtotime($date2)));
                    $limit = ($year9 == $year && $month9 == $month) ? $day9 - 1 : $t2;
                    $startDate = sprintf('%s-%s-01', $year, $month);
                    $endDate = sprintf('%s-%s-%s', $year, $month, $t2);
                    $valArr = $this->__getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit);
                    $flashChart->addLine($valArr, sprintf('%s年%s月', $year, $month), '#val#');
                }
                break;
            case 'month_active':
            case 'month_total':
                $labelsArr = array(
                    '01', '02', '03', '04', '05', '06',
                    '07', '08', '09', '10', '11', '12',
                );
                $model = 'StatUserMonthSQLModel';
                if ($type === 'month_active') {
                    $fields = "DATE_FORMAT(date,'%m') as key2,active as val2";
                } else {
                    $fields = "DATE_FORMAT(date,'%m') as key2,total as val2";
                }
                list($year, $month) = explode('-', date('Y-m', strtotime($date1)));
                $limit = ($year9 == $year) ? $month9 - 1 : 12;
                $startDate = sprintf('%s-01-01', $year);
                $endDate = sprintf('%s-12-01', $year);
                $valArr = $this->__getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit);
                $flashChart->addLine($valArr, sprintf('%s年', $year), '#val#', '#ae00ff');
                if ($compare) {
                    list($year, $month) = explode('-', date('Y-m', strtotime($date2)));
                    $limit = ($year9 == $year) ? $month9 - 1 : 12;
                    $startDate = sprintf('%s-01-01', $year);
                    $endDate = sprintf('%s-12-01', $year);
                    $valArr = $this->__getUserStatLineData($model, $startDate, $endDate, $fields, $labelsArr, $limit);
                    $flashChart->addLine($valArr, sprintf('%s年', $year), '#val#');
                }
                break;
            default:
                $labelsArr = array();
                break;
        }
        $flashChart->setXAxis($labelsArr);
        $this->_data = $flashChart->getData();
        $this->_ret = 0;
    }

    /**
     * 显示详细统计数据页面
     */
    public function showDetailStat() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = 'date';
        $orderMethod = 'DESC';
        $pageSize = 15;
        $statUserDaySQLModel = new StatUserDaySQLModel();
        $count = $statUserDaySQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $rowArr = $statUserDaySQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize + 1)->getAll();
        foreach ($rowArr as $index => &$row) {
            if ($index == count($rowArr) - 1) { // 最后一行,需要特殊处理
                $row['new'] = '-';
                $row['old'] = '-';
                $row['active_rate'] = ($row['total'] == 0) ? '-' : sprintf('%.2f', ($row['active'] / $row['total']) * 100) . '%';
                $row['old_rate'] = '-';
            } else {
                $row['new'] = $row['total'] - $rowArr[$index + 1]['total'];
                $row['old'] = $row['active'] - $row['new'];
                $row['active_rate'] = ($row['total'] == 0) ? '-' : sprintf('%.2f', ($row['active'] / $row['total']) * 100) . '%';
                $row['old_rate'] = ($rowArr[$index + 1]['active'] == 0) ? '-' : sprintf('%.2f', ($row['old'] / $rowArr[$index + 1]['active']) * 100) . '%';
            }
        }
        if (count($rowArr) > $pageSize) {
            unset($rowArr[count($rowArr) - 1]);
        }
        $this->_data = array(
            'filename' => 'stat_show_detail.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'data' => $rowArr,
        );
        $this->_ret = 0;
    }

    /**
     * 获取等级统计数据
     */
    public function showGradeStat() {
        $start = isset($_GET['start']) ? $_GET['start'] : date("Y-m-d", strtotime('-10 day', CURRENT_TIME));
        $end = isset($_GET['end']) ? $_GET['end'] : date("Y-m-d", strtotime('-1 day', CURRENT_TIME));
        $crontabDataSQLModel = new CrontabDataSQLModel();
        $whereArr = array(
            'type' => 1, // 等级分布
            '>=' => array(
                'date' => $start,
            ),
            '<=' => array(
                'date' => $end,
            ),
        );
        $orderByArr = array(
            'date' => 'ASC',
        );
        $rowArr = $crontabDataSQLModel->SH()->find($whereArr)->orderBy($orderByArr)->getAll();
        $tempArr = array();
        $columnArr = array();
        foreach ($rowArr as $row) {
            $stepArr = json_decode($row['content'], true);
            $columnArr[] = $row['date'];
            foreach ($stepArr as $key => $num) {
                $tempArr[$key][$row['date']] = $num;
            }
        }
        $this->_data = array(
            'filename' => 'stat_show_grade.php',
            'data' => $tempArr,
            'column' => $columnArr,
            'start' => $start,
            'end' => $end,
        );
        $this->_ret = 0;
    }

    /**
     * 显示用户步骤统计数据页面
     */
    public function showStepStat() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $orderColumn = 'date';
        $orderMethod = 'DESC';
        $pageSize = 15;
        $stepDataSQLModel = new StepDataSQLModel();
        $count = $stepDataSQLModel->SH()->count();
        $totalPage = ceil($count / $pageSize);
        $page = max(1, min($page, $totalPage));
        $pageOffset = ($page - 1) * $pageSize;
        $rowArr = $stepDataSQLModel->SH()->orderBy(array($orderColumn => $orderMethod))->limit($pageOffset, $pageSize)->getAll();
        $tempArr = array();
        for ($i = 1; $i <= 25; ++$i) {
            $tempArr[] = "sum(s{$i}) as s{$i}";
        }
        $fields = implode(',', $tempArr);
        $row = $stepDataSQLModel->SH()->fields($fields)->getOne();
        $row['date'] = '累积数据';
        array_unshift($rowArr, $row);
        $this->_data = array(
            'filename' => 'stat_show_step.php',
            'page' => $page,
            'count' => $count,
            'pageSize' => $pageSize,
            'totalPage' => $totalPage,
            'data' => $rowArr,
        );
        $this->_ret = 0;
    }

    /**
     * 显示闯关统计
     */
    public function showLevelStat() {
        $start = isset($_GET['start']) ? $_GET['start'] : date("Y-m-d", strtotime('-10 day', CURRENT_TIME));
        $end = isset($_GET['end']) ? $_GET['end'] : date("Y-m-d", strtotime('-1 day', CURRENT_TIME));
        $crontabDataSQLModel = new CrontabDataSQLModel();
        $whereArr = array(
            'type' => 3, // 闯关计数
            '>=' => array(
                'date' => $start,
            ),
            '<=' => array(
                'date' => $end,
            ),
        );
        $orderByArr = array(
            'date' => 'ASC',
        );
        $rowArr = $crontabDataSQLModel->SH()->find($whereArr)->orderBy($orderByArr)->getAll();
        $tempArr = array();
        $columnArr = array();
        foreach ($rowArr as $row) {
            $stepArr = json_decode($row['content'], true);
            $columnArr[] = $row['date'];
            foreach ($stepArr as $key => $num) {
                $tempArr[$key][$row['date']] = $num;
            }
            $tempArr['total'][$row['date']] = $stepArr['success'] + $stepArr['fail'];
        }
        $this->_data = array(
            'filename' => 'stat_show_level.php',
            'data' => $tempArr,
            'column' => $columnArr,
            'start' => $start,
            'end' => $end,
        );
        $this->_ret = 0;
    }

    /**
     * 显示其他统计数据
     */
    public function showOtherStat() {
        $type = isset($_GET['type']) ? $_GET['type'] : '102';
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d', CURRENT_TIME);
        if (!isset($_GET['only_data'])) {
            $this->_data = array(
                'filename' => 'stat_show_other.php',
                'type' => $type,
                'date' => $date,
                'data_file_url' => GATEWAY_URL . 'gateway.php?service=' . $_GET['service'] . '&action=' . $_GET['action'] . '&type=' . $type . '&date=' . $date . '&only_data=1',
            );
            $this->_ret = 0;
            return;
        }
        $start = strtotime($date);
        $end = $start + 86400;
        switch ($type) {
            case User::ACTION_TYPE_USE_ENERGY:
                $sql = "select c1, count(*) as c2 from (select user_id, sum(val1) as c1 from action_record where type={$type} and time>={$start} and time<{$end} group by user_id) as tmp_table group by c1 order by c2 desc;";
                $text = '体力使用量分布';
                $label = '体力使用点数：%s点';
                $sectionArr = array(
                    20 => '1-20',
                    50 => '21-50',
                    100 => '51-100',
                    200 => '101-200',
                    99999999 => '≥201',
                );
                break;
            case User::ACTION_TYPE_BUY_SILVER_ITEM:
                $sql = "select c1, count(*) as c2 from (select user_id, sum(val2) as c1 from action_record where type={$type} and time>={$start} and time<{$end} group by user_id) as tmp_table group by c1 order by c2 desc;";
                $text = '购买道具数分布';
                $label = '购买道具个数：%s个';
                $sectionArr = array(
                    10 => '1-10',
                    20 => '10-20',
                    50 => '21-50',
                    100 => '51-100',
                    99999999 => '≥101',
                );
                break;
            case User::ACTION_TYPE_FINISH_TASK:
                $sql = "select c1, count(*) as c2 from (select user_id,count(val1) as c1 from action_record where type={$type} and time>={$start} and time<{$end} group by user_id) as tmp_table group by c1 order by c2 desc;";
                $text = '完成任务数分布';
                $label = '完成任务个数：%s个';
                $sectionArr = array(
                    5 => '1-5',
                    10 => '6-10',
                    15 => '11-15',
                    16 => '16-20',
                    99999999 => '≥21',
                );
                break;
        }
        $actionRecordSQLModel = new ActionRecordSQLModel();
        $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
        $tempArr = array();
        foreach ($sectionArr as $section) {
            $tempArr[$section] = 0;
        }
        foreach ($rowArr as $row) {
            foreach ($sectionArr as $max => $section) {
                if ($row['c1'] <= $max) {
                    $tempArr[$section] += $row['c2'];
                    break;
                }
            }
        }
        foreach ($tempArr as $section => $val) {
            if ($val == 0) {
                unset($tempArr[$section]);
            }
        }
        $valArr = array();
        foreach ($tempArr as $key => $val) {
            $valArr[] = array(
                'value' => intval($val),
                'label' => sprintf($label, $key),
            );
        }
        $dataArr = array(
            'elements' => array(
                array(
                    'tip' => "所占比例：#percent#\n　(#val#人/#total#人)",
                    'colours' => array(
                        '0x336699', '0x88AACC', '0x999933', '0x666699', '0xCC9933',
                        '0x006666', '0x3399FF', '0x993300', '0xAAAA77', '0x666666', '0xFFCC66',
                        '0x6699CC', '0x663366', '0x9999CC', '0xAAAAAA', '0x669999',
                        '0xBBBB55', '0xCC6600', '0x9999FF', '0x0066CC', '0x99CCCC', '0x999999',
                        '0xFFCC00', '0x009999', '0x99CC33', '0xFF9900',
                        '0x999966', '0x66CCCC', '0x339966', '0xCCCC33',
                    ),
                    'alpha' => 0.2,
                    'start_angle' => 135,
                    'radius' => 180,
                    'no-labels' => false,
                    'ani--mate' => true,
                    'label-colour' => 0,
                    'values' => $valArr,
                    'type' => 'pie',
                    'border' => '1',
                ),
            ),
            'bg_colour' => '#FFFFFF',
            'title' => array(
                'text' => $text,
                'style' => '{font-size: 14px; color:#0000ff; font-family: Verdana; text-align: center;}',
            ),
        );
        $this->_data = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 显示下载页面
     */
    public function showDownload() {
        $this->_data['filename'] = 'stat_show_download.php';
    }

    /**
     * 执行下载
     */
    public function doDownload() {
        $type = $_GET['type'];
        switch ($type) {
            case 'user_hour':
                $orderByArr = array(
                    'date' => 'ASC',
                );
                $statUserHourSQLModel = new StatUserHourSQLModel();
                $rowArr = $statUserHourSQLModel->SH()->orderBy($orderByArr)->getAll();
                $filename = 'user_hour.csv';
                break;
            case 'user_day':
                $orderByArr = array(
                    'date' => 'ASC',
                );
                $statUserDaySQLModel = new StatUserDaySQLModel();
                $rowArr = $statUserDaySQLModel->SH()->orderBy($orderByArr)->getAll();
                $filename = 'user_day.csv';
                break;
            case 'user_month':
                $orderByArr = array(
                    'date' => 'ASC',
                );
                $statUserMonthSQLModel = new StatUserMonthSQLModel();
                $rowArr = $statUserMonthSQLModel->SH()->orderBy($orderByArr)->getAll();
                $filename = 'user_month.csv';
                break;
            case 'grade':
                $crontabDataSQLModel = new CrontabDataSQLModel();
                $whereArr = array(
                    'type' => 1,
                );
                $orderByArr = array(
                    'date' => 'ASC',
                );
                $tempArr = $crontabDataSQLModel->SH()->find($whereArr)->orderBy($orderByArr)->getAll();
                $rowArr = array();
                foreach ($tempArr as $arr) {
                    $arr2 = json_decode($arr['content'], true);
                    $arr2 = array_merge(array('date' => $arr['date']), $arr2);
                    $rowArr[] = $arr2;
                }
                $filename = 'grade.csv';
                break;
            case 'step':
                $crontabDataSQLModel = new CrontabDataSQLModel();
                $whereArr = array(
                    'type' => 2,
                );
                $orderByArr = array(
                    'date' => 'ASC',
                );
                $tempArr = $crontabDataSQLModel->SH()->find($whereArr)->orderBy($orderByArr)->getAll();
                $rowArr = array();
                foreach ($tempArr as $arr) {
                    $arr2 = json_decode($arr['content'], true);
                    $arr2 = array_merge(array('date' => $arr['date']), $arr2);
                    $rowArr[] = $arr2;
                }
                $filename = 'step.csv';
                break;
        }
        $csv = new CSV();
        $dataStr = $csv->makeData($rowArr);
        $this->__download($filename, $dataStr);
        $this->_ret = 0;
    }

    /**
     * 显示站点统计
     */
    public function showSiteStat() {
        $configArr = array(
            'pengyou' => array('siteid' => 3610913, 'name' => '童话迷城(TX-朋友)', 'url' => 'http://apps.pengyou.com/27790'),
            'renren' => array('siteid' => 3610892, 'name' => '童话迷城(人人-旧版)', 'url' => 'http://apps.renren.com/dreaming_adventures/'),
            'renren2' => array('siteid' => 3610811, 'name' => '童话迷城(人人-新版)', 'url' => 'http://apps.renren.com/dreaming_adventures_new/'),
            '4399' => array('siteid' => 3610670, 'name' => '童话迷城(4399)', 'url' => 'http://my.4399.com/game_thmc/'),
        );
        $this->_data = array(
            'filename' => 'stat_show_site.php',
            'data' => $configArr,
        );
        $this->_ret = 0;
    }

    /**
     * 下载文件
     * @param string $filename 文件名
     * @param string $dataStr 数据
     */
    private function __download($filename, $dataStr) {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
        header('Content-type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Length: ' . strlen($dataStr));
        exit($dataStr);
    }
}
?>
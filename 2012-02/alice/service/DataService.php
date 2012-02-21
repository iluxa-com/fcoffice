<?php
/**
 * 数据服务类(后台用)
 *
 * @author xianlinli@gmail.com
 */
class DataService extends Service {
    /**
     * 重载父类的构造函数(目的是阻止调用父类的构造函数)
     * @param array $configArr array($service, $action, $index, $lastLogin)
     */
    public function __construct($configArr) {
        $dataArr = $_POST;
        App::stripSlashes($dataArr);
        if (App::get('Platform') !== 'Devel' && !$this->__checkSign($dataArr)) { // 检查签名
            throw new UserException(UserException::ERROR_SYSTEM, 'API_SIGN_CHECK_FAIL', var_export($dataArr, true));
        }
    }

    /**
     * 计算签名
     * @param string $str 待签名数据
     * @return string
     */
    private function __calcSign($str) {
        return md5($str . 'DW7SWxABRcFGKnrkr4vhgIWK6imsfhQB');
    }

    /**
     * 校验签名
     * @param array $dataArr 数据数组
     * @return bool
     */
    private function __checkSign($dataArr) {
        if (!isset($dataArr['sign'])) {
            return false;
        }
        $sign = $dataArr['sign'];
        unset($dataArr['sign']);
        ksort($dataArr);
        $str = '';
        foreach ($dataArr as $key => $val) {
            $str .= $key . '=' . $val;
        }
        return ($sign == $this->__calcSign($str));
    }

    /**
     * 获取小时相关数据(新注册&在线)
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "2011-08-06 00:00:00": {
     *             "active": "100", // 活跃量
     *             "new": 20, // 新增用户量
     *             "pay_in": 100 // 充值金额
     *         },
     *         "2011-08-06 01:00:00": {
     *             "active": "150",
     *             "new": 28,
     *             "pay_in": 100
     *         }
     *     }
     * }
     */
    public function getHourData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $startDate2 = date('Y-m-d H:00:00', strtotime($date) - 3600);
        $endDate = date('Y-m-d 23:00:00', strtotime($date));
        $statUserHourSQLModel = new StatUserHourSQLModel();
        $whereArr = array(
            '>=' => array(
                'date' => $startDate2,
            ),
            '<=' => array(
                'date' => $endDate,
            ),
        );
        $orderByArr = array(
            'date' => 'ASC',
        );
        $rowArr = $statUserHourSQLModel->SH()->find($whereArr)->orderBy($orderByArr)->getAll();
        $tempArr = array();
        foreach ($rowArr as $row) {
            $tempArr[$row['date']] = $row;
        }
        $dataArr = array();
        foreach ($tempArr as $key => $row) {
            $key2 = date('Y-m-d H:00:00', strtotime('-1 hour', strtotime($key)));
            $dataArr[$key]['active'] = $row['active'];
            $dataArr[$key]['pay_in'] = $row['pay_in'];
            if (isset($tempArr[$key2])) {
                $dataArr[$key]['new'] = $row['total'] - $tempArr[$key2]['total'];
            } else {
                $dataArr[$key]['new'] = 0;
            }
        }
        unset($dataArr[$startDate2]);
        $this->_data['data'] = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 获取天相关数据(一天回头&三天回头&七天回头&普通/挑战/隐藏模式闯关成功/失败次数)
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "2011-08-06": {
     *             "active2": "120", // 活跃用户数(等级>=4)
     *             "back_user1": "50", // 一天回头用户数
     *             "back_user3": "30", // 三天回头用户数
     *             "back_user7": "10", // 七天回头用户数
     *             "mode_0_success": "100", // 普通模式闯关成功次数
     *             "mode_0_fail": "50", // 普通模式闯关失败次数
     *             "mode_1_success": "150", // 挑战模式闯关成功次数
     *             "mode_1_fail": "50", // 挑战模式闯关失败次数
     *             "mode_2_success": "250", // 隐藏模式闯关成功次数
     *             "mode_2_fail": "50", // 隐藏模式闯关失败次数
     *             "pay_num": "10", // 日充值人数
     *             "pay_in": "888", // 日充值总额
     *             "pay_first": "8", // 第一次充值的人数
     *         }
     *     }
     * }
     */
    public function getDayData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $statUserDaySQLModel = new StatUserDaySQLModel();
        $whereArr = array(
            'date' => $date,
        );
        $rowArr = $statUserDaySQLModel->SH()->find($whereArr)->getAll();
        $dataArr = array();
        foreach ($rowArr as $index => $row) {
            $dataArr[$row['date']] = array(
                'active2' => $row['active2'],
                'back_user1' => $row['back_user1'],
                'back_user3' => $row['back_user3'],
                'back_user7' => $row['back_user7'],
                'mode_0_success' => $row['mode_0_success'],
                'mode_0_fail' => $row['mode_0_fail'],
                'mode_1_success' => $row['mode_1_success'],
                'mode_1_fail' => $row['mode_1_fail'],
                'mode_2_success' => $row['mode_2_success'],
                'mode_2_fail' => $row['mode_2_fail'],
                'pay_num' => $row['pay_num'],
                'pay_in' => $row['pay_in'],
                'pay_first' => $row['pay_first'],
            );
        }
        $this->_data['data'] = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 获取月相关数据(月活跃用户(月登录次数>=2) & 活跃用户(月登录次数>=3 && 等级>=4))
     * @param string $date 日期(如:2011-08,默认为当月)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "2011-08-01": {
     *             "active2": "100", // 月活跃用户(月登录次数>=2)
     *             "active3": "70" // 活跃用户(月登录次数>=3 && 等级>=4)
     *         },
     *         "2011-09-01": {
     *             "active2": "200",
     *             "active3": "125"
     *         }
     *     }
     * }
     */
    public function getMonthData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-01', CURRENT_TIME);
        } else {
            $date = date('Y-m-01', strtotime($date));
        }
        $statUserMonthSQLModel = new StatUserMonthSQLModel();
        $whereArr = array(
            'date' => $date,
        );
        $rowArr = $statUserMonthSQLModel->SH()->find($whereArr)->getAll();
        $dataArr = array();
        foreach ($rowArr as $row) {
            $dataArr[$row['date']] = array(
                'active2' => $row['active2'],
                'active3' => $row['active3'],
            );
        }
        $this->_data['data'] = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 获取日体力消耗数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": [{
     *         "c1": "5", // 消耗的体力数
     *         "c2": "3" // 人数
     *     },
     *     {
     *         "c1": "30",
     *         "c2": "1"
     *     },
     *     {
     *         "c1": "70",
     *         "c2": "1"
     *     }]
     * }
     */
    public function getEnergyData($date = NULL) {
        if ($date === NULL) {
            $start = strtotime('today', CURRENT_TIME);
        } else {
            $start = strtotime($date);
        }
        $end = $start + 86400;
        $type = User::ACTION_TYPE_USE_ENERGY;
        $sql = "select c1, count(*) as c2 from (select user_id, sum(val1) as c1 from action_record where type={$type} and time>={$start} and time<{$end} group by user_id) as tmp_table group by c1 order by c2 desc";
        $actionRecordSQLModel = new ActionRecordSQLModel();
        $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
        $this->_data['data'] = $rowArr;
        $this->_ret = 0;
    }

    /**
     * 获取日道具购买统计数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": [{
     *         "item_id": "1001", // 道具ID
     *         "item_name": "XXX" , // 道具名称
     *         "total": "100" // 购买数量
     *     },
     *     {
     *         "item_id": "1002",
     *         "item_name": "YYYYY",
     *         "total": "728"
     *     }]
     * }
     */
    public function getItemData($date = NULL) {
        if ($date === NULL) {
            $start = strtotime('today', CURRENT_TIME);
        } else {
            $start = strtotime($date);
        }
        $end = $start + 86400;
        $type = User::ACTION_TYPE_BUY_SILVER_ITEM;
        $sql = "select val1 as item_id, sum(val2) as total from action_record where type={$type} and time>={$start} and time<{$end} group by item_id";
        $actionRecordSQLModel = new ActionRecordSQLModel();
        $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
        $itemDataSQLModel = new ItemDataSQLModel();
        $tempArr = $itemDataSQLModel->SH()->getAll();
        $nameArr = array();
        foreach ($tempArr as $row) {
            $nameArr[$row['item_id']] = $row['item_name'];
        }
        foreach ($rowArr as &$row) {
            $row['item_name'] = isset($nameArr[$row['item_id']]) ? $nameArr[$row['item_id']] : '';
        }
        $this->_data['data'] = $rowArr;
        $this->_ret = 0;
    }

    /**
     * 获取日金币道具购买统计数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": [{
     *         "item_id": "1001", // 道具ID
     *         "item_name": "XXX" , // 道具名称
     *         "total": "100" // 购买数量
     *     },
     *     {
     *         "item_id": "1002",
     *         "item_name": "YYYYY",
     *         "total": "728"
     *     }]
     * }
     */
    public function getGoldItemData($date = NULL) {
        if ($date === NULL) {
            $start = strtotime('today', CURRENT_TIME);
        } else {
            $start = strtotime($date);
        }
        $end = $start + 86400;
        $type = User::ACTION_TYPE_BUY_GOLD_ITEM;
        $sql = "select val1 as item_id, sum(val2) as total from action_record where type={$type} and time>={$start} and time<{$end} group by item_id";
        $actionRecordSQLModel = new ActionRecordSQLModel();
        $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
        $itemDataSQLModel = new ItemDataSQLModel();
        $tempArr = $itemDataSQLModel->SH()->getAll();
        $nameArr = array();
        foreach ($tempArr as $row) {
            $nameArr[$row['item_id']] = $row['item_name'];
        }
        foreach ($rowArr as &$row) {
            $row['item_name'] = isset($nameArr[$row['item_id']]) ? $nameArr[$row['item_id']] : '';
        }
        $this->_data['data'] = $rowArr;
        $this->_ret = 0;
    }

    /**
     * 获取每日道具使用数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": [{
     *         "item_id": "1001", // 道具ID
     *         "item_name": "XXX" , // 道具名称
     *         "total": "100" // 购买数量
     *     },
     *     {
     *         "item_id": "1002",
     *         "item_name": "YYYYY",
     *         "total": "728"
     *     }]
     * }
     */
    public function getUseItemData($date = NULL) {
        if ($date === NULL) {
            $start = strtotime('today', CURRENT_TIME);
        } else {
            $start = strtotime($date);
        }
        $end = $start + 86400;
        $type = User::ACTION_TYPE_USE_ITEM;
        $sql = "select val1 as item_id, sum(val2) as total from action_record where type={$type} and time>={$start} and time<{$end} group by item_id";
        $actionRecordSQLModel = new ActionRecordSQLModel();
        $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
        $itemDataSQLModel = new ItemDataSQLModel();
        $tempArr = $itemDataSQLModel->SH()->getAll();
        $nameArr = array();
        foreach ($tempArr as $row) {
            $nameArr[$row['item_id']] = $row['item_name'];
        }
        foreach ($rowArr as &$row) {
            $row['item_name'] = isset($nameArr[$row['item_id']]) ? $nameArr[$row['item_id']] : '';
        }
        $this->_data['data'] = $rowArr;
        $this->_ret = 0;
    }

    /**
     * 获取日完成任务数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": [{
     *         "c1": "5", // 完成的任务数
     *         "c2": "3" // 人数
     *     },
     *     {
     *         "c1": "30",
     *         "c2": "1"
     *     },
     *     {
     *         "c1": "70",
     *         "c2": "1"
     *     }]
     * }
     */
    public function getTaskData($date = NULL) {
        if ($date === NULL) {
            $start = strtotime('today', CURRENT_TIME);
        } else {
            $start = strtotime($date);
        }
        $end = $start + 86400;
        $type = User::ACTION_TYPE_FINISH_TASK;
        $sql = "select c1, count(*) as c2 from (select user_id, count(val1) as c1 from action_record where type={$type} and time>={$start} and time<{$end} group by user_id) as tmp_table group by c1 order by c2 desc";
        $actionRecordSQLModel = new ActionRecordSQLModel();
        $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
        $this->_data['data'] = $rowArr;
        $this->_ret = 0;
    }

    /**
     * 获取日所有玩家等级分布数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "1": 116,
     *         "2": 11,
     *         "3": 38,
     *         "4": 10
     *     }
     * }
     */
    public function getGradeData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $whereArr = array(
            'date' => $date,
            'type' => 1, // 所有玩家等级分布统计
        );
        $crontabDataSQLModel = new CrontabDataSQLModel();
        $row = $crontabDataSQLModel->SH()->find($whereArr)->getOne();
        $this->_data['data'] = isset($row['content']) ? json_decode($row['content'], true) : array();
        $this->_ret = 0;
    }

    /**
     * 获取日付费玩家等级分布数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "1": 116,
     *         "2": 11,
     *         "3": 38,
     *         "4": 10
     *     }
     * }
     */
    public function getPayGradeData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $whereArr = array(
            'date' => $date,
            'type' => 2, // 付费玩家等级分布统计
        );
        $crontabDataSQLModel = new CrontabDataSQLModel();
        $row = $crontabDataSQLModel->SH()->find($whereArr)->getOne();
        $this->_data['data'] = isset($row['content']) ? json_decode($row['content'], true) : array();
        $this->_ret = 0;
    }

    /**
     * 获取日闯关进度分布数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "1": { // 地区编号
     *             "0": 15, // 目前在第一个节点的玩家数
     *             "1": 2, // 目前在第二个节点的玩家数
     *             "2": 2, // 目前在第三个节点的玩家数
     *             "3": 0,
     *             "4": 0,
     *             "5": 144,
     *             "6": 1,
     *             "7": 8,
     *             "19": 1,
     *         },
     *         "2": {
     *             "0": 15,
     *             "1": 2,
     *             "2": 2,
     *             "3": 0,
     *             "4": 0,
     *             "5": 144,
     *             "6": 1,
     *             "7": 8,
     *             "19": 1,
     *         }
     *     }
     * }
     */
    public function getProgressData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $whereArr = array(
            'date' => $date,
            'type' => 4, // 闯关进度统计
        );
        $crontabDataSQLModel = new CrontabDataSQLModel();
        $row = $crontabDataSQLModel->SH()->find($whereArr)->getOne();
        $this->_data['data'] = isset($row['content']) ? json_decode($row['content'], true) : array();
        $this->_ret = 0;
    }

    /**
     * 获取每日金币存量
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "1": 14, // 500至2000金币
     *         "2": 2, // 2001至4000金币
     *         "3": 1, // 4001至6000金币
     *         "4": 0, // 6001至10000金币
     *         "5": 1, // 10001至30000金币
     *         "6": 9 // 30001以上金币
     *     }
     * }
     */
    public function getSilverData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $whereArr = array(
            'date' => $date,
            'type' => 5, // 金币存量统计
        );
        $crontabDataSQLModel = new CrontabDataSQLModel();
        $row = $crontabDataSQLModel->SH()->find($whereArr)->getOne();
        $this->_data['data'] = isset($row['content']) ? json_decode($row['content'], true) : array();
        $this->_ret = 0;
    }

    /**
     * 获取每日新手步骤数据
     * @param string $date 日期(如:2011-08-01,默认为当天日期)
     * @return json
     * {
     *     "ret": 0,
     *     "index": -1,
     *     "data": {
     *         "date": "2011-10-16",
     *         "s1": "10", // 过了第一步的玩家数
     *         "s2": "9", // 过了第二步的玩家数
     *         "s3": "8", // 过了第三步的玩家数
     *         "s4": "7",
     *         "s5": "6",
     *         "s6": "5"
     *     }
     * }
     */
    public function getStepData($date = NULL) {
        if ($date === NULL) {
            $date = date('Y-m-d', CURRENT_TIME);
        }
        $whereArr = array(
            'date' => $date,
        );
        $stepDataSQLModel = new StepDataSQLModel();
        $row = $stepDataSQLModel->SH()->find($whereArr)->getOne();
        if (empty($row)) {
            $this->_data['data'] = array();
        } else {
            $this->_data['data'] = $row;
        }
        $this->_ret = 0;
    }
}
?>
<?php
/**
 * 支付类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Pay {
    /**
     * 生成订单号
     * @return string
     */
    public static function genOrderId() {
        return sprintf('8%s%06d', date('ymdHis'), rand(1, 999999));
    }

    /**
     * 注册订单
     * @param int $srcNum 平台币
     * @param int $dstNum 游戏币
     * @return array
     */
    public static function regOrder($srcNum, $dstNum) {
        if (!is_numeric($srcNum) || $srcNum <= 0) {
            return array(
                'ret' => -1,
                'msg' => 'invalid src_num',
            );
        }
        if (!is_numeric($dstNum) || $dstNum <= 0) {
            return array(
                'ret' => -1,
                'msg' => 'invalid dst_num',
            );
        }
        $orderId = self::genOrderId();
        $desc = "{$dstNum}FH币";
        try {
            $sns = App::getSNS();
            $token = $sns->payRegOrder($orderId, $srcNum, $desc);
            // 写充值日志
            $dataArr = array(
                'order_id' => $orderId,
                'type' => 0, // 正式
                'src_num' => $srcNum, // 数量(平台币)
                'dst_num' => $dstNum, // 数量(游戏币)
                'finished' => 0, // 未完成
            );
            User::log(User::LOG_TYPE_GOLD_RECHARGE, $dataArr);
            return array(
                'order_id' => $orderId,
                'token' => $token,
            );
        } catch (Exception $e) {
            return array(
                'ret' => $e->getCode(),
                'msg' => 'catch exception',
            );
        }
    }

    /**
     * 完成订单
     * @param int $orderId 订单编号
     * @return array/false
     */
    public static function finishOrder($orderId) {
        $sns = App::getSNS();
        $result = $sns->payIsCompleted($orderId);
        if ($result == 0) { // 0表示未完成支付
            return false;
        }
        $goldRechargeSQLModel = new GoldRechargeSQLModel();
        $whereArr = array(
            'order_id' => $orderId,
        );
        $row = $goldRechargeSQLModel->SH()->find($whereArr)->getOne();
        if (empty($row)) {
            return false;
        }
        $finished = $row['finished'];
        $retArr = array(
            'app_res_user' => $row['user_id'],
            'app_res_order_id' => $row['order_id'],
            'app_res_amount' => $row['src_num'],
        );
        if ($finished >= 1) { // 已经完成
            return $retArr;
        }
        App::startTrans();
        try {
            // 更新订单状态
            $dataArr = array(
                '+' => array(
                    'finished' => 1,
                ),
            );
            $goldRechargeSQLModel->SH()->find($whereArr)->update($dataArr);
            // 给玩家增加FH币
            $newVal = Bag::incrItem(Common::ITEM_GOLD, $row['dst_num']);
            if ($newVal === false) { // 增加失败,写日志
                $debugDataArr = array(
                    'id' => Common::ITEM_GOLD,
                    'num' => $row['dst_num'],
                );
                Debug::log($debugDataArr, Debug::TYPE_PAY_ADD_GOLD_FAIL);
            }
            // 写消费日志(充值)
            $logDataArr = array(
                'type' => 0, // 充值
                'num' => $row['dst_num'],
                'remain' => $newVal,
            );
            User::log(User::LOG_TYPE_GOLD_LOG, $logDataArr);
            App::commitTrans();
            // 更新每小时充值额
            Stat::userHourIncr('pay_in', $row['src_num']);
            // 设置第一次充值时间
            $userModel = App::getInst('UserModel', $row['user_id'], false, $row['user_id']);
            if ($userModel->hSetNx('pay_first', CURRENT_TIME) !== false) {
                // 更新每天第一次充值人数计数
                Stat::userDayIncr('pay_first');
            }
            return $retArr;
        } catch (Exception $e) {
            App::rollbackTrans();
            return false;
        }
    }
}
?>
<?php
/**
 * 统计类
 */
class Stat {
    /**
     * 详细信息-更新计数
     * @param string $hashKey back_user1/back_user3/back_user7/invite_send/invite_accept
     *                         mode_0_success/mode_0_fail/mode_1_success/mode_1_fail/mode_2_success/mode_2_fail
     * @param int $offset
     */
    public static function userDayIncr($hashKey, $offset = 1) {
        $statDataModel = new StatDataModel('DETAIL', date('Y-m-d', CURRENT_TIME));
        $statDataModel->hIncrBy($hashKey, $offset);
    }

    /**
     * 用户小时数据-更新计数
     * @param string $hashKey pay_in
     * @param int $offset
     */
    public static function userHourIncr($hashKey, $offset = 1) {
        $statDataModel = new StatDataModel('USER_HOUR', date('Y-m-d-H', CURRENT_TIME));
        $statDataModel->hIncrBy($hashKey, $offset);
    }
}
?>
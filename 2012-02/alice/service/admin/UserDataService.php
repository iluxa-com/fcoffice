<?php
/**
 * 用户数据服务类(后台使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class UserDataService extends AuthService {
    /**
     * 显示ID转换页面
     */
    public function showIdConvert() {
        $this->_data['filename'] = 'user_show_id_convert.php';
    }

    /**
     * 执行ID转换
     */
    public function doIdConvert() {
        $userId = $_POST['user_id'];
        $snsUid = $_POST['sns_uid'];
        if (!empty($snsUid)) {
            $relationModel = new RelationModel($snsUid);
            $userId = $relationModel->get();
            if ($userId === false) {
                $this->_data['msg'] = 'sns_uid not exists';
                return;
            }
        } else if (!empty($userId)) {
            $userModel = new UserModel($userId);
            $snsUid = $userModel->hGet('sns_uid', false);
            if ($snsUid === false) {
                $this->_data['msg'] = 'user_id not exists';
                return;
            }
        } else {
            $this->_data['msg'] = 'please input user_id or sns_uid';
            return;
        }
        $dataArr = array(
            'user_id' => $userId,
            'sns_uid' => $snsUid,
        );
        $this->_data = array(
            'callback' => 'ajaxCallback',
            'params' => json_encode($dataArr),
        );
        $this->_ret = 0;
    }

    /**
     * 显示查询页面
     */
    public function showQuery() {
        $this->_data['filename'] = 'user_show_query.php';
    }

    /**
     * 显示用户信息页面
     */
    public function showUser() {
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $userModel = new UserModel($userId);
        $dataArr = $userModel->hGetAll();
        if (empty($dataArr)) {
            $this->_data['msg'] = 'user not exists';
            return;
        }
        $this->_data = array(
            'filename' => 'user_show_user.php',
            'user_id' => $userId,
            'user' => $dataArr,
            'key_suffix' => Common::getKeySuffix(),
        );
        $this->_ret = 0;
    }

    /**
     * 执行用户修改
     */
    public function doUserUpdate() {
        $userId = $_GET['user_id'];
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'invalid user id';
            return;
        }
        $dataArr = $_POST;
        $userModel = new UserModel($userId);
        if (!$userModel->exists()) { // 存在时才修改
            $this->_data['msg'] = 'user not exists';
            return;
        }
        $cannotChangeKeyArr = array('user_id', 'sns_id', 'username', 'head_img', 'gender', 'exp', 'last_exp', 'silver', 'gold', 'benison', 'charm', 'heart', 'progress', 'status', 'create_time');
        foreach ($cannotChangeKeyArr as $key) { // 移除不能在这里改变的Key
            unset($dataArr[$key]);
        }
        if ($userModel->hMset($dataArr) === false) {
            $this->_data['msg'] = 'unknown error';
            return;
        }
        $this->_data['msg'] = '修改成功！';
        $this->_ret = 0;
    }

    /**
     * 显示背包信息页面
     */
    public function showBag() {
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $bagModel = new BagModel($userId);
        $itemArr = $bagModel->hGetAll();
        if ($itemArr === false) {
            $this->_data['msg'] = 'user not exists';
            return;
        }
        ksort($itemArr); // sort by key
        $this->_data = array(
            'filename' => 'user_show_bag.php',
            'user_id' => $userId,
            'itemArr' => $itemArr,
        );
        $this->_ret = 0;
    }

    /**
     * 执行背包动作
     */
    public function doBagAction() {
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        $case = isset($_POST['case']) ? $_POST['case'] : '';
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'bad request';
            return;
        }
        $itemIdArr = array();
        $bagModel = new BagModel($userId);
        switch ($case) {
            case 'update':
                $itemArr = isset($_POST['item']) ? $_POST['item'] : '';
                if (empty($itemArr)) {
                    $this->_data['msg'] = 'no data';
                    return;
                }
                foreach ($itemArr as $itemId => $num) {
                    if (!is_numeric($num) || $num < 1) {
                        $itemIdArr[] = $itemId;
                        continue;
                    }
                    if (in_array($itemId, array(7901, 7902, 7907, 7908))) { // 虚拟道具，不能在这里添加
                        $this->_data['msg'] = 'virtual item, cannot add at here';
                        return;
                    }
                    if ($bagModel->hSet($itemId, $num) === false) {
                        $itemIdArr[] = $itemId;
                    }
                }
                break;
            case 'delete':
                $itemArr = isset($_POST['item']) ? $_POST['item'] : '';
                if (empty($itemArr)) {
                    $this->_data['msg'] = 'no data';
                    return;
                }
                foreach ($itemArr as $itemId => $num) {
                    if ($bagModel->hDel($itemId)) {
                        $itemIdArr[] = $itemId;
                    }
                }
                break;
            case 'add':
                $itemId = $_POST['item_id'];
                $num = $_POST['num'];
                if (!is_numeric($itemId) || !is_numeric($num) || $num < 1) {
                    $this->_data['msg'] = 'bad parameter';
                    return;
                }
                if (in_array($itemId, array(7901, 7902, 7907, 7908))) { // 虚拟道具，不能在这里添加
                    $this->_data['msg'] = 'virtual item, cannot add at here';
                    return;
                }
                if ($bagModel->hIncrBy($itemId, $num) === false) {
                    $this->_data['msg'] = 'add fail';
                    return;
                }
                $itemIdArr[] = $itemId;
                break;
            default:
                $this->_data['msg'] = 'undefined case';
                return;
                break;
        }
        $this->_data = array(
            'callback' => 'ajaxCallback',
            'params' => json_encode(array('case' => $case, 'ids' => $itemIdArr)),
        );
        $this->_ret = 0;
    }

    /**
     * 帐号重置
     */
    public function doReset() {
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'invalid user id';
            return;
        }
        // 执行重置
        $ret = Account::reset($userId);
        if ($ret !== true) {
            $this->_data['msg'] = $ret;
            return;
        }
        $this->_data['msg'] = '帐号重置成功（假如游戏已登录，你需要关闭浏览器重新进游戏）';
        $this->_ret = 0;
    }

    /**
     * 显示数据修改页面(测试用)
     */
    public function showDataModify() {
        $this->_data['filename'] = 'show_data_modify.php';
    }

    /**
     * 执行数据修改页面(测试用)
     */
    public function doDataModify() {
        $userId = $_POST['user_id'];
        if (!is_numeric($userId)) {
            $this->_data['msg'] = 'user id not set or invalid user id';
            return;
        }
        $userModel = new UserModel($userId);
        if (!$userModel->exists()) {
            $this->_data['msg'] = 'user not exists';
            return;
        }
        $statusArr = array();
        foreach ($_POST as $key => $val) {
            if ($val === '') {
                continue;
            }
            switch ($key) {
                case 'exp_incr':
                    if (!is_numeric($val)) {
                        $statusArr[$key] = -2; // 数据无效
                    } else if ($userModel->hIncrBy('exp', $val) !== false) {
                        $exp = $userModel->hGet('exp');
                        $userModel->hSet('last_exp', $exp);
                    } else {
                        $statusArr[$key] = -1; // 更改失败
                    }
                    break;
                case 'gold':
                    if (!is_numeric($val)) {
                        $statusArr[$key] = -2; // 数据无效
                    } else {
                        $newVal = $userModel->hIncrBy('gold', $val);
                        if ($newVal !== false) {
                            App::startTrans();
                            try {
                                // 写充值日志
                                $dataArr = array(
                                    'order_id' => Pay::genOrderId(),
                                    'type' => 1, // 测试
                                    'src_num' => $val,
                                    'dst_num' => $val,
                                    'finished' => 1, // 已完成
                                );
                                User::log(User::LOG_TYPE_GOLD_RECHARGE, $dataArr, $userId);
                                // 写消费日志(充值)
                                $dataArr = array(
                                    'type' => 0, // 充值
                                    'num' => $val,
                                    'remain' => $newVal,
                                );
                                User::log(User::LOG_TYPE_GOLD_LOG, $dataArr, $userId);
                                App::commitTrans();
                            } catch (Exception $e) {
                                $userModel->hIncrBy('gold', -$val); // 回滚
                                App::rollbackTrans();
                                $statusArr[$key] = -1; // 更改失败
                            }
                        } else {
                            $statusArr[$key] = -1; // 更改失败
                        }
                    }
                    break;
                case 'silver':
                case 'exp':
                case 'energy_time':
                case 'benison':
                case 'charm':
                case 'heart':
                case 'gender':
                case 'continue_times':
                case 'last_login':
                case 'sign_times':
                case 'last_sign':
                    if (!is_numeric($val)) {
                        $statusArr[$key] = -2; // 数据无效
                    } else if ($userModel->hSet($key, $val) !== false) {
                        if ($key === 'exp') {
                            $exp = $userModel->hGet('exp');
                            $userModel->hSet('last_exp', $exp);
                        }
                    } else {
                        $statusArr[$key] = -1; // 更改失败
                    }
                    break;
                case 'item':
                    if (strpos($val, ',') === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    list($itemId, $num) = explode(',', $val);
                    $itemDataModel = new ItemDataModel($itemId);
                    if (!$itemDataModel->exists()) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    if (!is_numeric($itemId) || !is_numeric($num) || $num < 0) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    if (in_array($itemId, array(7901, 7902, 7907, 7908))) { // 虚拟道具，不能在这里添加
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    $bagModel = new BagModel($userId);
                    if ($num == 0) { // 删除
                        if ($bagModel->hDel($itemId) === false) {
                            $statusArr[$key] = -1;
                        }
                    } else if ($bagModel->hSet($itemId, $num) === false) {
                        $statusArr[$key] = -1;
                    }
                    break;
                case 'pet':
                    $petId = $val;
                    $itemDataModel = new ItemDataModel($petId);
                    if (!$itemDataModel->exists()) {
                        $statusArr[$key] = -2; // 数据无效
                    } else if (Pet::addPet($petId, false, $userId) === false) {
                        $statusArr[$key] = -1;
                    }
                    break;
                case 'credit':
                    if (strpos($val, ',') === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    list($creditId, $creditTimes) = explode(',', $val);
                    if (!is_numeric($creditId) || !is_numeric($creditTimes) || $creditTimes < 0) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    $creditModel = new CreditModel($userId);
                    if ($creditModel->hSet($creditId, $creditTimes) === false) {
                        $statusArr[$key] = -1; // 更改失败
                    }
                    break;
                case 'level_record':
                    if (strpos($val, ',') === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    list($areaId, $challengeTimes) = explode(',', $val);
                    if (!is_numeric($areaId) || !is_numeric($challengeTimes) || $challengeTimes < 0) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    $levelRecordModel = new LevelRecordModel($userId);
                    if ($levelRecordModel->hSet($areaId, $challengeTimes) === false) {
                        $statusArr[$key] = -1; // 更改失败
                    }
                    break;
                case 'add_task':
                    $taskIdArr = explode(',', $val);
                    foreach ($taskIdArr as $taskId) {
                        if (!is_numeric($taskId)) {
                            continue;
                        }
                        $finishedTaskModel = new FinishedTaskModel($userId);
                        $finishedTaskModel->sRemove($taskId);
                        $unfinishedTaskModel = new UnfinishedTaskModel($userId);
                        $unfinishedTaskModel->sAdd($taskId);
                    }
                    break;
                case 'del_task':
                    $taskIdArr = explode(',', $val);
                    foreach ($taskIdArr as $taskId) {
                        if (!is_numeric($taskId)) {
                            continue;
                        }
                        $finishedTaskModel = new FinishedTaskModel($userId);
                        $finishedTaskModel->sRemove($taskId);
                        $unfinishedTaskModel = new UnfinishedTaskModel($userId);
                        $unfinishedTaskModel->sRemove($taskId);
                    }
                    break;
                case 'level_id':
                    if (is_numeric($val)) {
                        if ($val != 0) {
                            $userModel->hSet('level_id', $val);
                        } else {
                            $userModel->hDel('level_id');
                        }
                    }
                    break;
                case 'progress': // 普通模式
                    if (strpos($val, '-') === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    list($areaId, $node, $flag) = explode('-', $val);
                    $nodeCount = Common::getNodeCountByAreaId($areaId); // 获取节点数
                    if ($nodeCount === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    if (!is_numeric($areaId) || $areaId <= 0 || !is_numeric($node) || $node < 0 || $node > $nodeCount || !in_array($flag, array(0, 1))) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    if ($userModel->hSet('progress', $val) === false) {
                        $statusArr[$key] = -1; // 更改失败
                        continue;
                    }
                    if ($areaId == 1) {
                        $this->__delAllLevelPoint($userId);
                    } else {
                        for ($i = 1; $i < $areaId; ++$i) {
                            $levelPointModel = new LevelPointModel($userId, $i);
                            $levelPointModel->hSetNx(0, -1); // 只设置不存在的
                        }
                    }
                    break;
                case 'progress2': // 挑战模式
                    if (strpos($val, '-') === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    list($areaId, $node) = explode('-', $val);
                    $nodeCount = Common::getNodeCountByAreaId($areaId); // 获取节点数
                    if ($nodeCount === false) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    if (!is_numeric($areaId) || $areaId <= 0 || !is_numeric($node) || $node < 0 || $node > $nodeCount) {
                        $statusArr[$key] = -2; // 数据无效
                        continue;
                    }
                    $this->__setLevelPoint($userId, $areaId, $node);
                    break;
            }
        }
        $this->_data = array(
            'callback' => 'dataModifyCallback',
            'params' => json_encode(array('status' => $statusArr)),
        );
        $this->_ret = 0;
    }

    /**
     * 显示帐号调试页面
     */
    public function showAccountDebug() {
        $this->_data['filename'] = 'show_account_debug.php';
    }

    /**
     * 删除所有挑战模式闯关分数
     * @param int $userId 用户ID
     * @return bool
     */
    private function __delAllLevelPoint($userId) {
        // 删除所有存在的分数记录
        $levelPointModel = new LevelPointModel($userId, '*');
        $keyArr = $levelPointModel->RH()->getKeys($levelPointModel->getStoreKey());
        if (!empty($keyArr) && $levelPointModel->RH()->delete($keyArr) === false) { // 删除失败
            return false;
        }
        return true;
    }

    /**
     * 设置挑战模式闯关分数
     * @param int $userId 用户ID
     * @param int $areaId 地区ID
     * @param int $node 节点编号
     * @return bool
     */
    private function __setLevelPoint($userId, $areaId, $node) {
        $levelPointModel = new LevelPointModel($userId, $areaId);
        if ($levelPointModel->delete() === false) {
            return false;
        }
        $nodeCount = Common::getNodeCountByAreaId($areaId); // 获取节点数
        if ($nodeCount === false) {
            return false;
        }
        for ($j = 0; $j <= $nodeCount - 1; ++$j) {
            if ($j > $node) {
                break;
            } else if ($j == $node) {
                $levelPointModel->hSet($j, -1);
            } else {
                $levelPointModel->hSet($j, 3);
            }
        }
        return true;
    }
}
?>
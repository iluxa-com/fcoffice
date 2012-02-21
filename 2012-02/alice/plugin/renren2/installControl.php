<?php
/**
 * 安装控制(放量、白名单等处理)
 * @param int $snsUid SNS uid
 * @return bool
 */
function installControl($snsUid) {
    return true;
    $relationModel = new RelationModel($snsUid);
    $minUserId = $relationModel->getMinUserId();
    $maxUserId = $relationModel->getMaxUserId();
    $limit = $minUserId - 1 + 50000000;
    while ($maxUserId > $limit) {
        $userId = $relationModel->get();
        if ($userId !== false) { // 帐号已存在,放行
            break;
        }
        $filename = "platform/renren2/white_id.txt";
        if (file_exists($filename)) { // 白名单文件存在
            $snsUidStr = file_get_contents($filename);
            $snsUidArr = preg_split('/[\r\n]+/', $snsUidStr, -1, PREG_SPLIT_NO_EMPTY);
            if (in_array($snsUid, $snsUidArr)) { // 在白名单中,放行
                break;
            }
        }
        require_once BASE_DIR . "/platform/renren2/notice.inc.php";
        exit;
    }
    return true;
}
?>
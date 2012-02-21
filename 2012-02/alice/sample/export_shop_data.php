<?php
require_once 'config.inc.php';

$itemDataSQLModel = new ItemDataSQLModel();
$dataArr = $itemDataSQLModel->SH()->find(array('buyable' => 1))->getAll();
$tempArr = array();
foreach($dataArr as $row) {
    if ($row['silver'] <=0 && $row['gold'] <= 0) { // 跳过价格设置不合理的
        continue;
    }
    $arr = array(
        'silver' => intval($row['silver']),
        'gold' => intval($row['gold']),
        //'discount' => intval($row['discount']),
        'sale' => intval($row['buyable']),
    );
    $tempArr[$row['item_id']] = $arr;
}
$dataStr = json_encode($tempArr);
download('shop_data.json', $dataStr);
?>
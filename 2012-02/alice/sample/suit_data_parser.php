<?php
require_once 'config.inc.php';

$filename = 'suit_data.csv';
$dataStr = file_get_contents($filename);
$dataStr = iconv('GBK', 'UTF-8', $dataStr);

$csv = new CSV();
$dataArr = $csv->restoreData($dataStr);

$suitArr = array();
$suitArr2 = array();
foreach ($dataArr as $row) {
    $i = 0;
    $suitId = $row[$i++];
    if (!is_numeric($suitId)) { // 不是数值型,跳过
        continue;
    }
    $suitId = intval($suitId);
    $name = $row[$i++];
    $description = $row[$i++];
    $itemIdArr = array();
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }
    $itemId = getItemId($row[$i++]);
    if (is_numeric($itemId)) {
        $itemIdArr[] = $itemId;
    }

    $suitArr[] = array(
        'id' => $suitId,
        'name' => $name,
        'description' => $description,
        'items' => $itemIdArr,
    );
    $suitArr2[$suitId] = implode(',', $itemIdArr);
}
echo var_export($suitArr2, true);
echo "<br />";
echo json_encode($suitArr);
?>
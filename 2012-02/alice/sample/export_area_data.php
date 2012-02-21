<?php
require_once 'config.inc.php';

$areaDataSQLModel = new AreaDataSQLModel();
$dataArr = $areaDataSQLModel->SH()->getAll();
$dataStr = json_encode($dataArr);
download('area_data.json', $dataStr);
?>
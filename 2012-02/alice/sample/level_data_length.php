<?php
require_once 'config.inc.php';

$levelDataModel = new LevelDataModel();
$levelDataArr = $levelDataModel->hGetAll();
$lenArr = array();
foreach ($levelDataArr as $levelId => $levelData) {
    $lenArr[$levelId] = strlen($levelData);
}
arsort($lenArr);
$maxLen = 30000;
echo '<pre>';
foreach ($lenArr as $levelId => $len) {
    if ($len > $maxLen) {
        echo '<font color="red">' . $levelId . '=' . $len . "</font>\n";
    } else {
        echo $levelId . '=' . $len . "\n";
    }
}
?>
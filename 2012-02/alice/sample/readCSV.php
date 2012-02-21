<?php
require_once 'config.inc.php';

$newUserData = parseCSVFile('../newUser.csv');
$nieceUserData = parseCSVFile('../nieceUser.csv');

/**
 * CSV返回数据处理方法
 * @staticvar array $keys       内置key-value规则
 * @param <Array> $dataArr      调用CSV方法返回的Array
 * @return <Array>
 * 
 */
function retArrData($dataArr = '') {
    //定义两个数组，cache为缓存数据,Keys是为了减少后面不必要的循环处理
    $cache = array();
    static $keys = array('1至5级' => '5', '6至10级' => '10', '11至15级' => '15', '16至20级' => '20', '21至25级' => '25', '26至30级' => '30', '≥16' => '16');
    if (empty($dataArr)) {
        return array();
    }
    array_shift($dataArr);  //去掉CSV文件中的表头
    foreach ($dataArr as $key => $val) {
       $itemArr = explode('、', $val[1]);    //处理记录将物品分成组数格式
        foreach ($itemArr as $k => $v) {
            $tmpArr = explode('x', $v);  //处理物品，形成 物品名=>数量 格式数据
            $itemId = getItemId($tmpArr[0]); //取物品Id 形成 物品ID=>数量格式数据
            if (!is_numeric($itemId)) {
                echo "Unknown item {$itemId}<br />";
            }
            $cache[$keys[$val[0]]][$itemId] = intval($tmpArr[1]);    //形成最后数据
            unset($val[1], $tmpArr[0]);   //注销临时变量，释放内存
        }
        unset($val[0], $val[1]); //注销临时变量，释放内存
    }
    return $cache;
}

$newUserData = retArrData($newUserData);
$nieceUserData = retArrData($nieceUserData);
echo var_export($newUserData);
echo '<br />';
echo var_export($nieceUserData);
?>

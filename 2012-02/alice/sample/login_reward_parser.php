<?php

require_once 'config.inc.php';

/*
  序号	道具名	概率
  1	金钱100	15%
  2	金钱300	9%
  3	金钱500	7%
  4	金钱700	5%
  5	金钱1000	3%
  6	奶酥包	8%
  7	甜甜圈	4%
  8	奶酥包*2	4%
  9	甜甜圈*2	2%
  10	奶酥包*3	2%
  11	甜甜圈*3	1%
  12	收藏品随机*2	8%
  13	收藏品随机*2	8%
  14	收藏品随机*2	8%
  15	收藏品随机*2	8%
  16	收藏品随机*2	8%
 */

$arr = array(
    0 => array('items' => array('id' => 7901, 'num' => 100), 'chance' => 15),
    1 => array('items' => array('id' => 7901, 'num' => 300), 'chance' => 9),
    2 => array('items' => array('id' => 7901, 'num' => 500), 'chance' => 7),
    3 => array('items' => array('id' => 7901, 'num' => 700), 'chance' => 5),
    4 => array('items' => array('id' => 7901, 'num' => 1000), 'chance' => 3),
    5 => array('items' => array('id' => 4001, 'num' => 1), 'chance' => 8),
    6 => array('items' => array('id' => 4002, 'num' => 1), 'chance' => 4),
    7 => array('items' => array('id' => 4001, 'num' => 2), 'chance' => 4),
    8 => array('items' => array('id' => 4002, 'num' => 2), 'chance' => 2),
    9 => array('items' => array('id' => 4001, 'num' => 3), 'chance' => 2),
    10 => array('items' => array('id' => 4002, 'num' => 3), 'chance' => 1),
    11 => array('items' => array('id' => '5001,5002,5003,5004,5005,5006,5007,5008,5009,5010', 'num' => 2), 'chance' => 8),
    12 => array('items' => array('id' => '5011,5012,5013,5014,5015,5016,5017,5018,5019,5020', 'num' => 2), 'chance' => 8),
    13 => array('items' => array('id' => '5021,5022,5023,5024,5025,5026,5027,5028,5029,5030', 'num' => 2), 'chance' => 8),
    14 => array('items' => array('id' => '5031,5032,5033,5034,5035,5036,5037,5038,5039,5040', 'num' => 2), 'chance' => 8),
    15 => array('items' => array('id' => '5041,5042,5043,5044,5045,5046,5047,5048,5049,5050', 'num' => 2), 'chance' => 8),
);

$tempArr = array();
$chance = 0;
foreach ($arr as $id => $val) {
    $chance += $val['chance'] * 100;
    $tempArr[$chance] = array(
        'item_id' => $val['items']['id'],
        'num' => $val['items']['num'],
    );
}

echo var_export($tempArr, true);
?>

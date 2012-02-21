<?php
require_once 'config.inc.php';

/**
 * 获取子分类名称
 * @param string $name
 * @return string
 */
function getSubCategory($name) {
    switch ($name) {
        case '头饰':
            return 'head';
            break;
        case '上衣':
        case '衣服':
            return 'body_up';
            break;
        case '下衣':
        case '裤子':
            return 'body_down';
            break;
        case '手套':
            return 'hand';
            break;
        case '袜子':
            return 'socks';
            break;
        case '鞋子':
            return 'foot';
            break;
        case '其他':
            return 'other';
            break;
        case '家具':
            return 'furniture';
            break;
//        case '装饰':
//            return 'decoration';
//            break;
        case '房屋':
            return 'house';
            break;
//        case '背景':
//            return 'bg_img';
//            break;
        case '宠物装饰':
            return 'pet';
            break;
        case '房子':
            return 'house';
            break;
        case '背景':
            return 'background';
            break;
        case '雕像':
            return 'statue';
            break;
        case '宠物':
            return 'pet';
            break;
        case '邮箱':
            return 'postbox';
            break;
        case '园林':
            return 'garden';
            break;
        case '桌椅':
            return 'desk';
            break;
        case '娱乐':
            return 'pastime';
            break;
        case '装饰':
            return 'deco';
            break;
        case '栅栏':
            return 'guardrail';
            break;
        case '盆栽':
            return 'flower';
            break;
        case '留言':
            return 'msg';
            break;
        case '奖杯':
            return 'medal';
            break;
        case '任务':
            return 'task';
            break;
        case '合成':
            return 'merge';
            break;
        case 'Buff':
            return 'buff';
            break;
        default:
            exit("Unknown sub category name {$name}!");
    }
}

function getLevelItemCategory($name) {
    switch($name) {
        case '障碍':
            return 1;
        default:
            return 0;
    }
}

/**
 * 获取性别
 * @param string $val
 * @return int 0=女孩,1=男孩
 */
function getSex($val) {
    switch ($val) {
        case ''; // buff
            return 'buff';
            break;
        case '女孩':
            return 0;
            break;
        case '男孩':
            return 1;
            break;
        default:
            exit("Unknown gender {$val}!");
    }
}

/**
 * 获取布尔值
 * @param string $val
 * @return int
 */
function getBool($val) {
    switch ($val) {
        case '能':
        case '是':
            return 1;
            break;
        case '不能':
        case '否':
        case '':
            return 0;
        default:
            exit("Unknown switch {$val}!");
    }
}

function getPetCategory($name) {
    switch($name) {
        case '救援':
            return 1;
            break;
        case '辅助':
            return 2;
            break;
        case '幸运':
            return 3;
            break;
        default :
            exit("Unknown switch {$val}!");
    }
}

/**
 * 获取道具ID
 * @param string $name
 * @return int
 */
function getItemId2($name) {
    if ($name === '') {
        return '';
    } else if (is_numeric($name)) { // 如果是数值型,即已是itemId
        return intval($name);
    }
    global $itemIdArr;
    if (isset($itemIdArr[$name])) {
        return intval($itemIdArr[$name]);
    } else {
        exit("Unknown item name {$name}!");
    }
}

/**
 * 获取Style
 * @param string $name
 * @return string
 */
function getStyle($name) {
    $name = str_replace('主题', '', $name);
    return 'style_' . intval($name);
}

/**
 * 获取使用时效
 * @param string $expire
 * @return int
 */
function getExpire($expire) {
    if (is_numeric($expire)) {
        return $expire;
    } else {
        return -1;
    }
}

$filename = 'item_data.csv';
$dataStr = file_get_contents($filename);
$dataStr = iconv('GBK', 'UTF-8', $dataStr);

$csv = new CSV();
$dataArr = $csv->restoreData($dataStr);
$sqlArr = array();
$itemIdArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $itemId = $row[$i++];
    if (!is_numeric($itemId)) { // 不是数值型,跳过
        continue;
    }
    $itemName = $row[$i++];
    $itemIdArr[$itemName] = $itemId;
}
foreach ($dataArr as $row) {
    $i = 0;
    $itemId = $row[$i++];
    if (!is_numeric($itemId)) { // 不是数值型,跳过
        continue;
    }
    $itemName = $row[$i++];
    $category = $row[$i++];
    $subCategory = $row[$i++];
    $size = $row[$i++];
    $sex = $row[$i++];
    $suitId = $row[$i++];
    $energy = $row[$i++];
    $happy = $row[$i++]; // 宠物开心值消耗点数/次
    $decoName = $row[$i++]; // 宠物装饰名称
    $style = $row[$i++]; // 主题
    $reserved2 = $row[$i++]; // 预留2
    $buyable = getBool($row[$i++]);
    $useable = getBool($row[$i++]);
    $clickable = getBool($row[$i++]);
    $dbclickable = getBool($row[$i++]);
    $dragable = getBool($row[$i++]);
    $linkName = $row[$i++];
    $linkNames = $row[$i++];
    $charm = $row[$i++];
    $grade = $row[$i++];
    $description = $row[$i++];
    $gold = $row[$i++];
    $silver = $row[$i++];
    $isOnline = $row[$i++];
    $expire = getExpire($row[$i++]); // 使用时效
    $tradeable = getBool($row[$i++]); // 是否能交易
    $effectArr = array();
    $k = 0;
    while($k++ < 15) {
        $val = $row[$i++];
        if ($val != '') {
            if (strpos($val, '%') !== false) {
                $val = str_replace('%', '', $val);
                $val = $val / 100;
            } else {
                $val = intval($val);
            }
            $effectArr['e' . $k] = $val;
        }
    }
    $buffTime = $row[$i++];

    $dataArr = array();
    switch ($category) {
        case '服饰':
            $dataArr['category'] = getSubCategory($subCategory);
            $dataArr['sex'] = getSex($sex);
            $dataArr['link_names'] = ($linkNames === '') ? '' : json_decode(stripslashes($linkNames), true);
            break;
        case '装饰品':
            $dataArr['category'] = getSubCategory($subCategory);
            if ($dataArr['category'] === 'pet') {
                $dataArr['deco_id'] = getItemId2($decoName);
            }
            if ($style != '') {
                $dataArr['style'] = getStyle($style);
            }
            break;
        case '过关道具':
            $dataArr['category'] = getLevelItemCategory($subCategory);
            break;
        case '食物':
            $dataArr['energy'] = intval($energy);
            break;
        case '收藏品':
            break;
        case '特殊道具':
            break;
        case '任务道具':
            $dataArr['category'] = getSubCategory($subCategory);
            break;
        case '套装':
            $dataArr['suit_id'] = intval($suitId);
            $dataArr['sex'] = getSex($sex);
            break;
        case '宠物':
            $dataArr['use'] = intval($happy);
            $dataArr['deco_id'] = getItemId2($decoName);
            $dataArr['category'] = getPetCategory($subCategory);
            break;
        default:
            exit("Unknow main category {$category}!");
    }
    $dataArr['expire'] = $expire;
    $dataArr['tradeable'] = $tradeable;
    $dataArr['effect'] = $effectArr;
    $extraInfo = empty($dataArr) ? '' : json_encode($dataArr);
    if ($isOnline == 1) {
        $sqlArr[] = "INSERT INTO item_data (`item_id`, `item_name`, `link_name`, `description`, `buyable`, `useable`, `clickable`, `dbclickable`, `dragable`, `grade`, `silver`, `gold`, `extra_info`) VALUES ('{$itemId}', '{$itemName}', '{$linkName}', '{$description}', '{$buyable}', '{$useable}', '{$clickable}', '{$dbclickable}', '{$dragable}', '{$grade}', '{$silver}', '{$gold}', '{$extraInfo}');";
    }
}
$dataStr = implode("\r\n", $sqlArr);
echo $dataStr;
?>
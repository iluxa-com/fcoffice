<?php
if (php_sapi_name() !== 'cli') {
    exit('This script can only run under cli mode!');
}
switch ($platform) {
    case 'renren':
        $host = 'alrr.fanhougame.com';
        break;
    case 'renren2':
        $host = 'newalrr.fanhougame.com';
        break;
    case '4399':
        $host = 'alice4399.fanhouapp.com';
        break;
    case 'pengyou':
        $host = 'app27790.qzoneapp.com';
        break;
    case 'qzone':
        $host = 'app27790.qzone.qzoneapp.com';
        break;
    default:
        $host = 'alice-dev.fanhougame.com';
        break;
}
$_SERVER['HTTP_HOST'] = $host;
require_once dirname(__FILE__) . '/../config.php';

/**
 * 根据道具名称获取道具ID
 * @param string $name 道具名称
 * @return int/string
 */
function getItemId($name) {
    if ($name === '') {
        return '';
    }
    if (is_numeric($name)) {
        return $name;
    }
    $itemDataSQLModel = new ItemDataSQLModel();
    $row = $itemDataSQLModel->SH()->find(array('item_name' => $name))->fields('item_id')->getOne();
    if (empty($row)) {
        return $name;
    } else {
        return $row['item_id'];
    }
}

/**
 * 追加SNS信息
 * @param string $type 类型(exp/silver/heart)
 * @param array $dataArr 排名数据数组
 * @param bool $needExp 是否需要前三名的经验
 * @return array
 */
function appendSNSInfo($type, $dataArr, $needExp) {
    $tempArr = array();
    $num = 0;
    $keySuffix = Common::getKeySuffix();
    $usernameKey = 'username' . $keySuffix;
    $headImgKey = 'head_img' . $keySuffix;
    foreach ($dataArr as $userId => $val) {
        $userModel = new UserModel($userId);
        $userDataArr = $userModel->hMget(array('sns_uid', $usernameKey, $headImgKey));
        if ($userDataArr['sns_uid'] === false) { // 没取到
            $tempArr[$userId] = array(
                $type => $val,
                'username' => '',
                'head_img' => '',
            );
        } else if ($userDataArr[$usernameKey] !== false && $userDataArr[$headImgKey] !== false) { // 有取到
            $tempArr[$userId] = array(
                $type => $val,
                'username' => $userDataArr[$usernameKey],
                'head_img' => $userDataArr[$headImgKey],
            );
        } else {
            $tempArr[$userId] = array(
                $type => $val,
                'username' => '',
                'head_img' => '',
            );
        }
        if ($needExp && $num < 3) {
            $tempArr[$userId]['exp'] = $userModel->hGet('exp', 0);
            ++$num;
        }
    }
    return $tempArr;
}

/**
 * 输出图表
 * @param string $title
 * @param array $dataArr
 */
function outputGraph($title, $dataArr) {
    $titleLen = strlen($title);
    $maxKeyLen = 0;
    $maxValLen = 0;
    foreach ($dataArr as $key => $val) {
        $len = strlen($key);
        if ($maxKeyLen < $len) {
            $maxKeyLen = $len;
        }
        $len = strlen($val);
        if ($maxValLen < $len) {
            $maxValLen = $len;
        }
    }
    if ($maxKeyLen + $maxValLen < $titleLen) {
        $maxValLen = $titleLen - $maxKeyLen;
    }
    $maxLen = $maxKeyLen + 3 + $maxValLen;
    $str = CONSOLE_COLOR_START;
    $str .= '+-' . str_repeat('-', $maxLen) . '-+' . "\n";
    $str .= '| ' . str_pad($title, $maxLen, ' ', STR_PAD_RIGHT) . ' |' . "\n";
    $str .= '+-' . str_repeat('-', $maxLen) . '-+' . "\n";
    foreach ($dataArr as $key => $val) {
        $str .= '| ' . str_pad($key, $maxKeyLen, ' ', STR_PAD_RIGHT) . ' | ' . str_pad($val, $maxValLen, ' ', STR_PAD_RIGHT) . ' |' . "\n";
    }
    $str .= '+-' . str_repeat('-', $maxLen) . '-+' . "\n";
    $str .= CONSOLE_COLOR_END;
    echo $str;
}

define('CONSOLE_COLOR_START', "\033[35m");
define('CONSOLE_COLOR_END', "\033[0m");
set_time_limit(0);
?>
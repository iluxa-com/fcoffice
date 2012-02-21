<?php
/**
 * CSV类
 *
 * @author xianlinli@gmail.com
 * @link http://en.wikipedia.org/wiki/Comma-separated_values
 * @package Alice
 */
class CSV {
    /**
     * 分隔符
     * @var string
     */
    private $_delimiter = ',';
    /**
     * 界限符
     * @var string
     */
    private $_enclosure = '"';
    /**
     * 行结束符
     * @var string
     */
    private $_lineEnding = "\r\n";

    /**
     * 配置参数
     * @param string $delimiter 分隔符
     * @param string $enclosure 界限符
     * @param string $lineEnding 行结束符
     */
    public function config($delimiter, $enclosure, $lineEnding) {
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;
        $this->_lineEnding = $lineEnding;
    }

    /**
     * 在给定的字符串两边包裹指定的字符串
     * @param string $innerStr
     * @param string $outerStr
     * @return string
     */
    private function __wrapStr($innerStr, $outerStr) {
        return $outerStr . $innerStr . $outerStr;
    }

    /**
     * 添加界限符
     * @param string $val
     * @param bool $forceValToString
     * @return string
     */
    private function __addEnclosure($val, $forceValToString) {
        $val = str_replace($this->_enclosure, $this->_enclosure . $this->_enclosure, $val);
        if ($forceValToString) {
            return $this->__wrapStr($val, $this->_enclosure . $this->_enclosure . $this->_enclosure);
        } else {
            return $this->__wrapStr($val, $this->_enclosure);
        }
    }

    /**
     * 移去界限符
     * @param string $val
     * @param bool $addslashes
     * @return string
     */
    private function __removeEnclosure($val, $addslashes) {
        $val = preg_replace('/^' . preg_quote($this->_enclosure) . '|' . preg_quote($this->_enclosure) . '$/', '', $val);
        $val = str_replace($this->_enclosure . $this->_enclosure, $this->_enclosure, $val);
        if ($addslashes) {
            return addslashes($val);
        }
        return $val;
    }

    /**
     * 将给定的二维数组构造成CSV格式的字符串
     * @param array $rowArr
     * @param bool $handleColumnHeader
     * @param bool $forceValToString
     * @return string
     */
    public function makeData($rowArr, $handleColumnHeader = true, $forceValToString = false) {
        $dataStr = '';

        if ($handleColumnHeader) {
            // 处理字段名(取键名)
            foreach ($rowArr as $row) {
                $tempArr = array();
                foreach ($row as $key => $val) {
                    $tempArr[] = $this->__addEnclosure($key, $forceValToString);
                }
                $dataStr .= implode($this->_delimiter, $tempArr) . $this->_lineEnding;
                break;
            }
        }

        // 处理字段内容(取值)
        foreach ($rowArr as $row) {
            $tempArr = array();
            foreach ($row as $val) {
                $tempArr[] = $this->__addEnclosure($val, $forceValToString);
            }
            $dataStr .= implode($this->_delimiter, $tempArr) . $this->_lineEnding;
        }
        return $dataStr;
    }

    /**
     * 将给定CSV格式的字符串还原到数组当中
     * @param string $dataStr
     * @param bool $addslashes
     * @return array
     */
    public function restoreData($dataStr, $addslashes = true) {
        // 数据字节长度
        $dataLength = strlen($dataStr);
        // 行结束符字节长度
        $lineEndingLength = strlen($this->_lineEnding);
        // 分隔符字节长度
        $delimiterLength = strlen($this->_delimiter);
        // 当前位置
        $cur = 0;
        // 截取开始位置
        $start = 0;
        // 界限符个数
        $enclosureCount = 0;
        // 行号
        $row = 0;
        $dataArr = array();
        while ($cur < $dataLength) {
            if ($dataStr[$cur] === $this->_lineEnding[0] && ($enclosureCount % 2 === 0)) {
                ++$cur;
                $cur2 = 1;
                $flag = true;
                while ($cur < $dataLength && $cur2 < $lineEndingLength) {
                    if ($dataStr[$cur] !== $this->_lineEnding[$cur2]) {
                        $flag = false;
                        break;
                    }
                    ++$cur;
                    ++$cur2;
                }
                if ($flag) {
                    $dataArr[$row][] = $this->__removeEnclosure(substr($dataStr, $start, $cur - $lineEndingLength - $start), $addslashes);
                    $start = $cur;
                    $enclosureCount = 0;
                    ++$row;
                }
                continue;
            } else if ($dataStr[$cur] === $this->_delimiter[0] && ($enclosureCount % 2 === 0)) {
                ++$cur;
                $cur3 = 1;
                $flag = true;
                while ($cur < $dataLength && $cur3 < $delimiterLength) {
                    if ($dataStr[$cur] !== $this->_delimiter[$cur3]) {
                        $flag = false;
                        break;
                    }
                    ++$cur;
                    ++$cur3;
                }
                if ($flag) {
                    $dataArr[$row][] = $this->__removeEnclosure(substr($dataStr, $start, $cur - $delimiterLength - $start), $addslashes);
                    $start = $cur;
                    $enclosureCount = 0;
                }
                continue;
            } else if ($dataStr[$cur] === $this->_enclosure) {
                ++$enclosureCount;
            }
            ++$cur;
        }
        return $dataArr;
    }
}
?>
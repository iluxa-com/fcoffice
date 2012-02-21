<?php
/**
 * open flash chart图表数据构造类
 * 
 * @author xianlinli@gmail.com
 * @package Alice
 */
class FlashChart {
    /**
     * 线条属性
     * @var array
     */
    private $_elements = array();
    /**
     * Y坐标属性
     * @var array
     */
    private $_yAxis = array();
    /**
     * X坐标属性
     * @var array
     */
    private $_xAxis = array();
    /**
     * Y坐标最小值
     * @var int
     */
    private $_yMin = NULL;
    /**
     * Y坐标最大值
     * @var int
     */
    private $_yMax = NULL;

    /**
     * 添加Line
     * @param array $values 值数组
     * @param string $text 文本
     * @param string $tip Tip
     * @param string $colour Color
     */
    public function addLine($values, $text, $tip, $colour = '#ffae00') {
        $element = array(
            'type' => 'line',
            'values' => $values,
            'colour' => $colour,
            'text' => $text,
            'font-size' => 12,
            'tip' => $tip,
        );
        $this->_elements[] = $element;
        foreach ($values as $val) {
            if ($this->_yMin === NULL) {
                $this->_yMin = $val;
            } else if ($this->_yMin > $val) {
                $this->_yMin = $val;
            }
            if ($this->_yMax === NULL) {
                $this->_yMax = $val;
            } else if ($this->_yMax < $val) {
                $this->_yMax = $val;
            }
        }
    }

    /**
     * 设置Y坐标属性
     * @param int $min 最小值
     * @param int $max 最大值
     * @param int $steps 间隔
     */
    public function setYAxis($min = NULL, $max = NULL, $steps = NULL) {
        if ($min === NULL) {
            $min = ($this->_yMin === NULL) ? 0 : $this->_yMin;
        }
        if ($max === NULL) {
            $max = ($this->_yMax === NULL) ? 400 : $this->_yMax;
        }
        if ($min == $max) {
            $max += 400;
        }
        if ($steps === NULL) {
            $steps = ceil(($max - $min) / 20);
        }
        $max = $min + ceil(($max - $min) / $steps) * $steps;
        $this->_yAxis = array(
            'stroke' => 1,
            'colour' => '#c6d9fd',
            'grid-colour' => '#cccccc',
            'min' => $min,
            'max' => $max,
            'steps' => $steps,
        );
    }

    /**
     * 设置X坐标属性
     * @param array $labels
     */
    public function setXAxis($labels) {
        $this->_xAxis = array(
            'offset' => false,
            'stroke' => 1,
            'colour' => '#c6d9fd',
            'grid-colour' => '#cccccc',
            'labels' => array(
                'labels' => $labels,
            ),
        );
    }

    /**
     * 获取数据
     * @return array
     */
    public function getData() {
        if (empty($this->_yAxis)) {
            $this->setYAxis();
        }
        $dataArr = array(
            'elements' => $this->_elements,
            'y_axis' => $this->_yAxis,
            'x_axis' => $this->_xAxis,
            'bg_colour' => '#ffffff',
        );
        return $dataArr;
    }
}
?>
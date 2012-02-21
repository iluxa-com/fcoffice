<?php
/**
 * 第一次系统服务
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class FirstService extends Service {
    /**
     * 动作最大偏移(按需设定)
     * @var int
     */
    const ACTION_MAX_OFFSET = 39;
    /**
     * Feed最大偏移(按需设定)
     */
    const FEED_MAX_OFFSET = 7;

    /**
     * 完成动作
     * @param int $offset 偏移
     */
    public function finishAction($offset = NULL) {
        if (!is_numeric($offset) || $offset < 0 || $offset > self::ACTION_MAX_OFFSET) {
            $this->_data['msg'] = 'invalid offset';
            return;
        }
        $firstSystemModel = new FirstSystemModel();
        $val = $firstSystemModel->getBit($offset);
        if ($val == 1) {
            $this->_data['msg'] = 'alread finished';
            return;
        }
        $val = $firstSystemModel->setBit($offset, 1);
        if ($val === false || $val == 1) {
            $this->_data['msg'] = 'setBit error';
            return;
        }
        $dataArr = array();
        $rewardArr = Common::getFirstReward($offset);
        if ($rewardArr !== false) {
            foreach ($rewardArr as $item) { // 奖励处理
                Bag::incrItem($item['id'], $item['num']);
            }
            $dataArr['reward'] = $rewardArr;
        }
        $this->_data = $dataArr;
        $this->_ret = 0;
    }

    /**
     * 完成指导
     * @param int $itemIdArr 道具ID数组
     */
    public function finishGuide($itemIdArr = NULL) {
        if (!is_array($itemIdArr) || empty($itemIdArr)) {
            $this->_data['msg'] = 'invalid item id array';
            return;
        }
        $itemGuideModel = new ItemGuideModel();
        foreach ($itemIdArr as $itemId) {
            if ($itemGuideModel->sAdd($itemId) === false) {
//                $this->_data['msg'] = 'alread finished or error';
//                return;
            }
        }
        $this->_ret = 0;
    }

    /**
     * feed发送标记
     * @param int $offset 偏移
     */
    public function finishFeed($offset = NULL) {
        if (!is_numeric($offset) || $offset < 0 || $offset > self::FEED_MAX_OFFSET) {
            $this->_data['msg'] = 'invalid offset';
            return;
        }
        $flagModel = new FlagModel(NULL, FlagModel::TYPE_FEED);
        $flagModel->setBit($offset, 1);
        $this->_ret = 0;
    }
}
?>
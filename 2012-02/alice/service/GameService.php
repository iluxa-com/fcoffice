<?php
/**
 * 小游戏服务类
 *
 * @author seasonxin@yahoo.cn
 * @package Alice
 */
class GameService extends Service {
    /**
     * 水管迷途
     * @play_times1
     */
    public function tube() {
        $curPlayTimes1 = $this->_userModel->hGet('play_times1');
        if ($curPlayTimes1 <= 0) {
            $this->_data = 'bad request';
            return;
        }
        $rewardArr = array(
            array(
                'id' => 4001,
                'num' => 2,
            ),
            array(
                'id' => Common::ITEM_SILVER,
                'num' => 50,
            ),
        );
        foreach ($rewardArr as $item) {
            Bag::incrItem($item['id'], $item['num']);
        }
        $this->_userModel->hIncrBy('play_times1', -1);
        $this->_data['reward'] = $rewardArr;
        $this->_ret = 0;
    }
}
?>
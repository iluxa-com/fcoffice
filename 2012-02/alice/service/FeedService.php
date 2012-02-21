<?php
/**
 * Feed服务类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class FeedService extends Service {
    /**
     * 发布Feed
     * @param array $paramArr 参数数组
     */
    public function publishFeed($paramArr) {
        if (App::get('Platform') !== '4399') {
            $this->_data['msg'] = 'This platform is not supported!';
            return;
        }
        if (!isset($paramArr['title_template']) || !isset($paramArr['title_data'])) {
            $this->_data['msg'] = 'invalid param';
            return;
        }
        $sns = App::getSNS();
        $ret = $sns->publishFeed($paramArr);
        if (!$ret) {
            $this->_data['msg'] = 'pub feed fail';
            return;
        }
        $this->_ret = 0;
    }
}
?>
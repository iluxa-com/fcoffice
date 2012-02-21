<?php
/**
 * 默认服务类(服务测试用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class DefaultService extends Service {
    /**
     * 问好方法
     */
    public function sayHello() {
        fb("test");
        $this->_data['msg'] = "Hello world!";
        $this->_ret = 0;
    }
}
?>
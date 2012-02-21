<?php
/**
 * 合成炉子模型类(hash)
 *
 * @author jj.comeback@gmail.com
 * @package Alice
 */
class StoveModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'SO';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'stove_id1' => '{"id":1}', // 1号炉子空闲状态
        'stove_id2' => '{"id":2,"time":12000,"cd":1000,"mergeId":1,"luckyId":7001}', // 2号炉子合成中，合成公式1，合成起始时间12000，合成时长1000秒。幸运卡ID 7001
        // 3,4,5,6,7,8号炉子无数据表示未解锁
    );
}
?>
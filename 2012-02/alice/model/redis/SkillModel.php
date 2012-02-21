<?php
/**
 * 合成技能熟练度模型(hash)
 *
 * @author jj.comeback@gmail.com
 * @package Alice
 */
class SkillModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'SK';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'merge_id1' => 11, // 合成功能Id：合成熟练度
    );
}
?>
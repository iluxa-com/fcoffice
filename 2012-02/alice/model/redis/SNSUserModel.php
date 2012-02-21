<?php
/**
 * SNS用户模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $snsUserModel = new SNSUserModel($snsUid);
 * @package Alice
 */
class SNSUserModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'main.relation';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'SU'; // sns_user
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'email' => 'string',
        'openid' => 'int',
        'password' => 'string',
        'nickname' => 'string',
        'gender' => 'string',
        'province' => 'string',
        'city' => 'string',
        'figureurl' => 'string',
        'is_vip' => 'bool',
        'is_year_vip' => 'bool',
        'vip_level' => 'int',
        'balance' => 'int',
        'create_time' => 'int',
    );
}
?>
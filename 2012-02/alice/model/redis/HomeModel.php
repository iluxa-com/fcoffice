<?php
/**
 * 家模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $homeModel = new HomeModel($userId);<br />
 *           $homeModel->hMset($dataArr);<br />
 *           $homeModel->hSet('bg_img', $itemId);<br />
 *           $homeModel->hSet('home', $itemId);<br />
 *           $homeModel->hIncrBy('level', 1);<br />
 *           $dataArr = $homeModel->hGetAll();
 * @package Alice
 */
class HomeModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'H';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'house' => 2079, // 房子
        'background' => 2080, // 背景
        'pet' => 2081, // 宠物
        'postbox' => 2082, // 邮箱
        'statue' => 2083, // 雕像
        'garden' => 2084, // 园林
        'desk' => 2085, // 桌椅
        'pastime' => 2086, // 娱乐
        'deco' => 2087, // 装饰
        'guardrail' => 2088, // 护栏
        'flower' => 2089, // 盆栽
        'msg' => 2090, // 留言
        // 新版
        'house' => '{"id":2094,"level":0}', // 房子
        'board' => '{"id":2090,"level":0}', // 告示板
        'postbox' => '{"id":2082,"level":0}', // 邮箱
        'tree' => '{"id":2084,"level":0,"last_pick":0}', // 魔法果树
        'horse' => '{"id":"2086","level":0,"last_level_up":0', // 马车"trade_info":{"friend_user_id":245,"start_time":1323166954,"trade_hours":3,"reward":372}
        'honeybee' => '{"id":2085,"level":0,"last_pick":0,"last_level_up":0}', // 魔法蜂巢
        'workshop' => '{"id":2088,"level":0,"last_level_up":0}', // 精灵工坊
        'farm' => '{"id":2083,"level":0}', // 磨坊农田"land":{"level":1,"crop_id":1,"time":0}}
        'trade' => '{"id":2092,"level":0}', // 摊位
        'spirit' => '{"id":2089,"level":0,"last_level_up":0}', // 精灵屋"last_refresh":123456789,"index":0,"last_invite":123456789
        'totem' => '{"id":2091,"level":0}', // 图腾柱
        'pet' => '{"id":2081,"level":0}', // 宠物屋
        'hole' => '{}', // 树洞
        'radio' => '{"id":2093,"level":0}', // 收音机
    );
}
?>
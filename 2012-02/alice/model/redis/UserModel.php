<?php
/**
 * 用户模型类(hash)
 *
 * @author xianlinli@gmail.com
 * @example $userModel = new UserModel($userId);<br />
 *           $userModel->hMset($dataArr);<br />
 *           $dataArr = $userModel->hGetAll();
 * @package Alice
 */
class UserModel extends RedisModel {
    /**
     * 服务器组
     * @var string
     */
    protected $_serverGroup = 'circle.user';
    /**
     * 存储键前缀
     * @var string
     */
    protected $_storeKeyPrefix = 'U';
    /**
     * 结构数组
     * @var array
     */
    protected $_structArr = array(
        'user_id' => 'int', // 用户ID
        'sns_uid' => 'string', // SNS UID
        'gender' => 'int', // 性别(-1=未设置,0=girl,1=boy)
        'title' => 'int', // 称号
        'exp' => 'int', // 经验值
        'last_exp' => 'int', // 上一次升级后的经验值
        'energy_time' => 'int', // 体力值最后同步时间
        'benison' => 'int', // 祝福值
        'silver' => 'int', // 金币
        'gold' => 'int', // FH币
        'charm' => 'int', // 魅力值
        'heart' => 'int', // 爱心值
        'cancel_task' => 'int', // 今天可取消任务剩余次数
        'npc_times' => 'int', // 今天可自动推送NPC任务剩余次数
        'help_times' => 'int', // 今天可帮助好友完成NPC任务剩余次数
        'feed_times' => 'int', // 今天可吃食物剩余次数
        'play_times1' => 'int', // 今天可玩次数
        'friends_num' => 'int', // 好友数
        'invite_num' => 'int', // 邀请好友数
        'invite_reward_num' => 'int', // 邀请好友奖励领取数
        'chest_no' => 'int', // 宝箱编号
        'chest_invite_num' => 'int', // 今天宝箱邀请人数
        'newer_test' => 'int', // 新手引导调查标志(0=放弃,1=完成)
        'progress' => 'string', // 普通模式下的进度
        'revive_times1' => 'int', // 今天可使用祝福复活剩余次数
        'revive_times2' => 'int', // 今天可使用金币复活剩余次数
        'level_success' => 'int', // 闯关成功数
        'level_fail' => 'int', // 闯关失败数
        'activity_flag' => 'string', // 活动标志
        'wish_items' => 'string', // 许愿道具(逗号分隔)
        'carry_pets' => 'string', // 携带宠物(逗号分隔)
        'login_reward' => 'int', // 登录奖励领取标志(0=未领取,1=已领取)
        'status' => 'int', // 帐号状态
        'last_login' => 'int', // 最后登录时间
        'month_login_times' => 'int', // 当月登录次数
        'continue_times' => 'int', // 连续登录次数
        'sign_times' => 'int', // 签到次数
        'last_sign' => 'int', // 最后签到时间
        'create_time' => 'int', // 帐号创建时间
    );
}
?>
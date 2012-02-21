<?php
/**
 * 调试类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Debug {
    /**
     * 存储类型(file/mysql/none)
     * @var string
     */
    const STORE_TYPE = 'mysql';
    /**
     * 存储文件名
     * @var string
     */
    const FILENAME = 'debug.log';
    /**
     * 类型-调试信息
     * @var int
     */
    const TYPE_DEBUG_INFO = 0;
    /**
     * 类型-应用添加
     */
    const TYPE_APP_ADD = 1;
    /**
     * 类型-应用移除
     * @var int
     */
    const TYPE_APP_REMOVE = 2;
    /**
     * 类型-充值添加金币失败
     * @var int
     */
    const TYPE_PAY_ADD_GOLD_FAIL = 3;
    /**
     * 类型-作弊调试
     * @var int
     */
    const TYPE_CHEAT_DEBUG = 4;
    /**
     * 类型-首页异常
     * @var int
     */
    const TYPE_INDEX_EXCEPTION = 10000;
    /**
     * 类型-服务异常
     * @var int
     */
    const TYPE_SERVICE_EXCEPTION = 20000;
    /**
     * 类型-服务失败
     * @var int
     */
    const TYPE_SERVICE_FAIL = 30000;
    /**
     * 类型-运行时错误
     * @var int
     */
    const TYPE_RUNTIME_ERROR = 40000;

    /**
     * 记录日志(如果日志是写入MySQL,则不能用于SQL模型类中,否则会死循环)
     * @param mixed $val 数据
     * @param int $type 类型
     */
    public static function log($val, $type = self::TYPE_DEBUG_INFO) {
        if (!is_string($val)) {
            $val = json_encode($val);
        }
        // 获取用户ID,没取到时返回0
        $userId = App::get('user_id', 0);
        switch (self::STORE_TYPE) {
            case 'mysql':
                $dataArr = array(
                    'user_id' => $userId,
                    'log_type' => $type,
                    'log_data' => addslashes($val),
                    'create_time' => time(),
                );
                try {
                    $debugLogSQLModel = new DebugLogSQLModel();
                    $debugLogSQLModel->SH()->insert($dataArr);
                } catch (Exception $e) {
                    // 不处理
                }
                break;
            case 'file':
                $data = date('r') . "\n" . $userId . "\n" . $type . "\n" . $val . "\n\n";
                file_put_contents(BASE_DIR . DS . self::FILENAME, $data, FILE_APPEND);
                break;
            default:
                break;
        }
    }

    /**
     * 获取异常信息
     * @param object $e 异常类的实例
     * @return string
     */
    public static function getExceptionMsg($e) {
        $post = $_POST;
        $get = $_GET;
        if (defined('GPC_SLASHES_ADDED')) {
            !empty($post) && App::stripSlashes($post);
            !empty($get) && App::stripSlashes($get);
        }
        $str = '';
        $str .= '$_POST=' . json_encode($post);
        $str .= "\n";
        $str .= '$_GET=' . json_encode($get);
        $str .= "\n";
        $str .= '$_SESSION=' . json_encode($_SESSION);
        $str .= "\n";
        $str .= get_class($e);
        $str .= "\n";
        $str .= $e->getCode();
        $str .= "\n";
        $str .= $e->getFile() . '(' . $e->getLine() . ')';
        $str .= "\n";
        $str .= $e->getMessage();
        $str .= "\n";
        $str .= $e->getTraceAsString();
        return $str;
    }
}
?>
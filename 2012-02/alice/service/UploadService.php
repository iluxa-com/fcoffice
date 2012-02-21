<?php
/**
 * 上传服务类(**此服务中的uploadPhoto方法固定用GET方式请求**)
 *
 * @author xianlinli@gmail.com
 */
class UploadService extends Service {
    /**
     * 上传图片
     */
    public function uploadPhoto() {
        // 获取上传图片数据
        if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            $dataStr = $GLOBALS['HTTP_RAW_POST_DATA'];
        } else {
            $dataStr = file_get_contents('php://input');
        }
        // 创建临时文件
        $filename = tempnam("/tmp", "ALICE_PHOTO_");
        // 写入临时文件
        if (file_put_contents($filename, $dataStr) === false) {
            $this->_data['msg'] = 'write tmp file fail';
            return;
        }
        // 上传图片
        $sns = App::getSNS();
        try {
            $retArr = $sns->uploadPhoto($filename);
            // 删除临时文件
            unlink($filename);
        } catch (Exception $e) {
            // 删除临时文件
            unlink($filename);
            $this->_data['msg'] = $e->getCode();
            $this->_ret = 0;
            return;
        }

        $this->_data['data'] = $retArr;
        $this->_ret = 0;
    }
}
?>
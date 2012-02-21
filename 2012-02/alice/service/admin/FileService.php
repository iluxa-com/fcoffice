<?php
/**
 * 文件服务类(AS编辑器使用)
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class FileService extends AuthService {
    /**
     * 文件上传路径
     * @var string
     */
    const UPLOAD_DIR = '/data/www/alicedev/upload';

    /**
     * 获取文件列表
     * @param string $subDir 子路径
     */
    public function getFileList($subDir = NULL) {
        if (!preg_match('/^[a-z0-9\_]+$/i', $subDir)) { // 子路径有效性检查
            $this->_data['msg'] = 'invalid sub path';
            return;
        }
        $dir = self::UPLOAD_DIR . DIRECTORY_SEPARATOR . $subDir;
        if (!is_dir($dir)) { // 路径检查
            $this->_data['msg'] = 'dir invalid or not exists';
            return;
        }
        $fileArr = scandir($dir);
        $dataArr = array();
        foreach ($fileArr as $file) {
            if ($file === '.' || $file === '..') { // 去掉"."和".."
                continue;
            }
            $dataArr[] = DIRECTORY_SEPARATOR . $subDir . DIRECTORY_SEPARATOR . $file;
        }
        $this->_data = array('data' => $dataArr);
        $this->_ret = 0;
    }
}
?>
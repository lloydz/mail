<?php
namespace common;

use yii\web\UploadedFile;
use yii\helpers\FileHelper;
/**
 * 上传文件
 * @author wei.w.zhou.integle.com
 * @property string basepath 文件存放根目录
 * @copyright 2016-7-14下午4:57:38
 */
class FileUpload extends UploadedFile {
    
    /**
     * 文件相对目录
     */
    public $deep_path;
    
    /**
     * 文件上传后名称
     */
    public $save_name;
    
    /**
     * 自动保存，算好路径和名称
     * @author wei.w.zhou.integle.com
     * @return boolean
     * @copyright 2016-7-14下午5:15:23
     */
    public function save() {
        $deepPath = $this->createDeepPath();
        $path = $this->basepath .DIRECTORY_SEPARATOR.$deepPath;
        
        if (!FileHelper::createDirectory($path)) {
            return FALSE;
        }
        $fileName = uniqid() . '.' . $this->extension;
        if (!parent::saveAs($path . DIRECTORY_SEPARATOR . $fileName)) {
            return FALSE;
        }
        
        $this->save_name = $fileName;
        $this->deep_path = $deepPath;
        return TRUE;
    }
    
    
    /**
     * 生成存文件深度路径
     *
     * @access public
     * @return string
     */
    public function createDeepPath() {
        $deep = time() % 2000;
        $deep = sprintf('%04s', $deep) .DIRECTORY_SEPARATOR;
        $time = substr(microtime(TRUE), -6, 5);
        $time = intval(str_replace('.', '', $time));
        $deep .= $time < 5000 ? '0' : '1';
        $deep .= substr($time, -3, 3);
        return $deep;
    }
    
    /**
     * 文件存放根目录
     * @author wei.w.zhou.integle.com
     * @return string
     * @copyright 2016年8月18日上午10:15:01
     */
    public static function getBasepath() {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { // windows系统(开发用)
            return '\\\\192.168.100.18' . '/' . 'uploads' . '/' . 'files' . '/' . 'attachments';
        }
        return '/home' . '/' . 'uploads' . '/' . 'files';
    }
    
}
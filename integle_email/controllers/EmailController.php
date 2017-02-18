<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
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

class EmailController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    
    {
        // var_dump(file_exists('\\\\192.168.100.18\uploads\files\0000\indraw\installer.txt'));die;
        \Yii::$app->mailer->setTransport([
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.163.com',
            'username' => 'zhhjchxy@163.com',
            'password' => 'wy123456',
            'port' => '25',
            'encryption' => 'tls',
        ]);
        
        $mail = \Yii::$app->mailer->compose()
            ->setFrom('zhhjchxy@163.com')
            ->setTo('huajun.h.zhu@integle.com')
            ->setSubject('测试')
            ->attach('\\\\192.168.100.18\uploads\files\attachments\0307\1187\58a7be9350579.pdf', ['fileName' => '1.pdf'])
            ->attach('\\\\192.168.100.18\uploads\files\attachments\0307\1187\58a7be9350579.pdf')
            ->setTextBody('现在时间是' . date('Y-m-d H:i:s'));
        if($mail->send()) {
            echo '邮件发送成功..';
        } else {
            echo '邮件发送失败';
        }
        die;
        
        return $this->render('index');
    }
    
    public function actionUploadAttachments() {
        echo date('Y-m-d H:i:s') . '<br>';
        $attachments = FileUpload::getInstancesByName('attachments');
        var_dump($attachments);die;
        foreach ($attachments as $attachment) {
            if(!$attachment->save()) {
                echo '附件上传失败';
            }
        }
        echo '附件上传成功';
        echo '<br>' . date('Y-m-d H:i:s');
        // var_dump($attachments);
    }
}

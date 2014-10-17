<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-8-2
 * Time: 下午8:49
 */
class CbFileUpload {

    private $pc_image_server;
    private $pc_upload_dir;

    private $allowedImageExtensions = array('jpg','jpeg','png',);
    // 10M
    private $sizeLimit = 10285760;

    public function __construct() {
        $this->pc_image_server = 'http://'.Yii::app()->params['pc_image_server'];
        $this->pc_upload_dir = Yii::app()->params['pc_upload_dir'];
    }

    /** 上传用户头像 */
    public function uploadAvatar($file) {
        $handle = new Upload($file);
        $relative_dir = '/avatar/'.AppUtils::hash_dir();
        $abs_dir = $this->pc_upload_dir.$relative_dir;

        $handle->file_src_name = AppUtils::rename($handle->file_src_name);
        if ($handle->uploaded) {
            $handle->Process($abs_dir);
            if ($handle->processed) {
                $url = $this->pc_image_server.$relative_dir.'/'.$handle->file_dst_name;
            }
        }
        unset($handle);
        return isset($url) ? $url : false;
    }

    /**
     * Fixes the odd indexing of multiple file uploads from the format:
     *
     * $_FILES['field']['key']['index']
     *
     * To the more standard and appropriate:
     *
     * $_FILES['field']['index']['key']
     *
     * @param array $files
     */
    public function reArrayFiles(&$file_post) {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    /** 多文件上传 */
    public function uploadMultFiles(&$file_post) {
        $images = array();
        $file_ary = $this->reArrayFiles($file_post);
        foreach ($file_ary as $file) {
            $handle = new Upload($file);
            $relative_dir = '/answer/'.AppUtils::hash_dir();
            $abs_dir = $this->pc_upload_dir.$relative_dir;

            $handle->file_src_name = AppUtils::rename($handle->file_src_name);
            if ($handle->uploaded) {
                $handle->Process($abs_dir);
                if ($handle->processed) {
                    array_push($images, $this->pc_image_server.$relative_dir.'/'.$handle->file_dst_name);
                }
            }
            unset($handle);
        }
        return $images;
    }
}
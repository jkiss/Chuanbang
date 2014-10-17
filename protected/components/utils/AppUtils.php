<?php
/**
 * Created by PhpStorm.
 * User: gracier11
 * Date: 13-12-16
 * Time: 下午11:10
 */
class AppUtils {
    public static function is_email($email) {
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
            if (preg_match($chars, $email)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function is_number($s) {
        if(preg_match("/^[1-9]{1}[0-9]*$/i", $s)) return true;
        return false;
    }

    public static function encrypt($text, $key='dotfive.cn', $iv='1234567812345678') {
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($key), $text, MCRYPT_MODE_CBC, $iv);
        return base64_encode($crypttext);
    }

    public static function decrypt($crypttext, $key='dotfive.cn', $iv='1234567812345678') {
        $decode = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($key), base64_decode($crypttext), MCRYPT_MODE_CBC, $iv);
        return $decode;
    }

    public static function pack($values) {
        return array_map(function($v) {
            return "(".$v.")";
        },$values);
    }

    public static function rand($s='0123456789', $len=6) {
        $ss = '';
        for ($i=0,$len_=mb_strlen($s);$i<$len;$i++)
        {
            $num=mt_rand(0,$len_-1);//产生一个0到$len之间的随机数
            $ss.=$s[$num];
        }
        return $ss;
    }

    public static function randNum($len=6) {
        $arr = array('0','1','2','3','4','5','6','6','7','8','9');
        $ss = '';
        for ($i=0,$len_=count($arr);$i<$len;$i++)
        {
            $num=mt_rand(0,$len_-1);//产生一个0到$len之间的随机数
            $ss.=$arr[$num];
        }
        return $ss;
    }

    static public function hash_dir($deep=2, $s='0123456789abcdef') {
        $arr = array();
        $len = mb_strlen($s, 'utf-8') - 1;
        for($i = 0; $i < $deep; $i++) {
            $arr[] = $s[mt_rand(0, $len)].$s[mt_rand(0, $len)];
        }
        return implode('/', $arr);
    }

    static public function serial($s) {
        return hash('md5', $s);
    }

    /**
     * 2014-09-03 转成2014年9月3号
     * @param $date
     */
    public static function formatDate($date) {
        if(empty($date)) return "";
        $parts = explode('-', $date);
        if(count($parts) < 3) return $date;
        return $parts[0].'年'.intval($parts[1]).'月'.intval($parts[2]).'日';
    }

    static public function rename($filename) {
        $pathinfo = pathinfo($filename);
        $filename = date('His').substr(md5($pathinfo['filename']), 0, 8);
        $ext = @$pathinfo['extension'];		// hide notices if extension is empty
        $ext = ($ext == '') ? $ext : '.' . $ext;
        return $filename . $ext;
    }

    static public function getExtension($filename) {
        $pathinfo = pathinfo($filename);
        $ext = @$pathinfo['extension'];		// hide notices if extension is empty
        return $ext;
    }

    public static function rmkdir($path, $mode = 0777) {
        return is_dir($path) || ( self::rmkdir(dirname($path), $mode) && self::_mkdir($path, $mode) );
    }

    private static function _mkdir($path, $mode = 0777) {
        $old = umask(0);
        $res = @mkdir($path, $mode);
        umask($old);
        return $res;
    }

    /** 删除图片 */
    public static function deleteImage($url) {
        $url = str_replace('http://'.Yii::app()->params['pc_image_server'], Yii::app()->params['pc_upload_dir'], $url);
        if(file_exists($url)) @unlink($url);
    }

    /**
     * @param $sec 秒
     * @param int $ups 支持数
     * @param int $downs 反对数
     * @return float 热度
     */
    public static function scoreHot($sec,$ups=0,$downs=0) {
        $diff = $ups-$downs;
        $order = log(max(abs($diff), 1), 10);
        $sign = $diff > 0 ? 1 : ($diff < 0 ? -1 : 0);
        return round($order + $sign * ($sec-1134028003) / 45000, 7);
    }

    /** 下载QQ，微博头像 */
    public static function syncAvatar($url) {
        $data = @file_get_contents($url);
        if($data && (strpos($url, 'q.qlogo.cn') !== false || strpos($url, 'sinaimg.cn') !== false)) {
            $relative_dir = AppUtils::hash_dir();
            if(strpos($url, 'q.qlogo.cn') !== false) $relative_dir = 'qq/'.$relative_dir;
            if(strpos($url, 'sinaimg.cn') !== false) $relative_dir = 'weibo/'.$relative_dir;
            $filename = self::rename($url).'.jpg';
            $file = Yii::app()->params['pc_upload_dir'].'/'.$relative_dir.'/'.$filename;
            if(!is_dir(dirname($file))) self::rmkdir(dirname($file));
            $len = @file_put_contents($file, $data);
            if($len > 0) return "http://".Yii::app()->params['pc_image_server'].'/'.$relative_dir.'/'.$filename;
        }
        return $url;
    }

}
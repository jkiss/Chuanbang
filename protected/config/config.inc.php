<?php
//域名配置
define('DOMAIN_NAME','3w.chuanbang.com');
define('WEB_URL','http://' . DOMAIN_NAME);

// 数据库配置
define('DB_HOST','127.0.0.1');
define('DB_PORT',3306);
define('DB_USERNAME','root');
define('DB_PASSWORD','root');
define('DB_NAME','chuanbang');

// 微博
define( "WB_AKEY" , '2558507317' );
define( "WB_SKEY" , '1daf9ed15b9ed29cc8f239fb0bf2633d' );
define( "WB_CALLBACK_URL" , 'http://'.DOMAIN_NAME.'/weibo/callback' );

// QQ
define( "QQ_APP_ID" , '101157811' );
define( "QQ_SECRET" , '127d57f79ef0efc2155f55d700035daf' );
define( "QQ_CALLBACK_URL" , 'http://'.DOMAIN_NAME.'/qq/callback' );

define('PC_UPLOAD_DIR', 'e:/www/img1');
define('PC_IMAGE_SERVER', 'img1.chuanbang.com');
?>

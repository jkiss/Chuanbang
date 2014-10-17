<?php
require_once('config.inc.php');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'homeUrl'=>'/',
	'charset'=>'UTF-8',

	// preloading 'log' component
	'preload'=>array('log'),
	'language'=>'zh_cn',

	// autoloading model and component classes
	'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.components.form.*',
        'application.components.utils.*',
        'application.extensions.upload.Upload',
        'application.extensions.xunsearch.*',
	),
	
	'modules'=>array(
	),

	// application components
	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
                '/sign_in'=>'/user/login',
                '/forgot_password'=>'user/forgotPassword',
                '/register'=>'user/register',
                '/redirect'=>'site/redirect',
        		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
        'mailer' => array(
            'class' => 'application.extensions.mailer.EMailer',
            'pathViews' => 'application.views.email',
            'pathLayouts' => 'application.views.email.layouts'
        ),
		'session'=>array(
			'autoStart'=>true,
			'cookieMode'=>'allow',
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
			'emulatePrepare' => true,
			'username' =>DB_USERNAME,
			'password' => DB_PASSWORD,
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
			'schemaCachingDuration' => 180,
			/*'enableProfiling'=>true,
            'enableParamLogging' => true,*/
		),
		/*'search' => array(
            'class' => 'EXunSearch',
            'xsRoot' => '/usr/local/xunsearch',  // xunsearch 安装目录
            'project' => 'tnc', // 搜索项目名称或对应的 ini 文件路径
            'charset' => 'utf-8', // 您当前使用的字符集（索引、搜索结果）
        ),*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'info, debug',
				),
			),
		),
		/*'session' => array(
            'class' => 'CCacheHttpSession',
			'cacheID' =>'sessionCache',
        ),*/
        'cache' => array(
            'class' => 'CFileCache',
        ),
	),
    'params'=>array(
        'pc_image_server'=>PC_IMAGE_SERVER,
        'pc_upload_dir'=>PC_UPLOAD_DIR,
    ),
);
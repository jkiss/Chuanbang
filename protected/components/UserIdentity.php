<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_INVALID = 1;

    private $_id;
    private $_type;

    public function __construct($username,$password,$type)
    {
        parent::__construct($username,$password);
        $this->_type = $type;
    }

    public function authenticate()
    {
        if($this->type == User::EMAIL) {
            if(strpos($this->username, '@')) {
                $login = Login::model()->findByAttributes(array('email'=>$this->username, 'password'=>self::encrypt($this->password)));
            } else {
                $login = AdminUser::model()->findByAttributes(array('username'=>$this->username, 'password'=>self::encrypt($this->password)));
            }
        } else if($this->type == User::WEIBO) {
            $login = WeiboUser::model()->findByAttributes(array('tid'=>$this->username, 'access_token'=>$this->password));
        } else if($this->type == User::QQ) {
            $login = QQUser::model()->findByAttributes(array('openid'=>$this->username, 'access_token'=>$this->password));
        }
        if(null !== $login) {
            $this->_id = $login->user_id;
            $this->errorCode=self::ERROR_NONE;
        } else {
            $this->errorCode=self::ERROR_INVALID;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getType() {
        return $this->_type;
    }

	public function login($duration=0)
	{
        if(!isset($this->_id)) $this->authenticate();
		if($this->errorCode===UserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this,$duration);
			return true;
		}
        return false;
	}

    public static function encrypt($pwd) {
        return base64_encode(md5($pwd));
    }
}
<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm {
	public $email;
	public $password;
	public $rememberMe = true;

    public $error;

    public function __construct($values) {
        if(!is_array($values))
            return;
        isset($values['email']) && $this->email = $values['email'];
        isset($values['password']) && $this->password = $values['password'];
        isset($values['rememberMe']) && $this->rememberMe = $values['rememberMe'];
    }

    public function validate() {
        if(strpos($this->email, '@') && !AppUtils::is_email($this->email)) {
            $this->error = '邮箱无效';
            return false;
        }

        if(!isset($this->password)) {
            $this->error = '密码不能为空';
            return false;
        }
        return true;
    }
}

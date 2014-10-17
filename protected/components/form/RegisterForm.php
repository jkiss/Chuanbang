<?php
/** 用户注册 */
class RegisterForm
{
	public $email;
	public $password;
//    public $password_again;

    public $error;

    public function __construct($values) {
        if(!is_array($values))
            return;
        isset($values['email']) && $this->email = $values['email'];
        isset($values['password']) && $this->password = $values['password'];
//        isset($values['password_again']) && $this->password_again = $values['password_again'];
    }

    public function validate() {
        if(!AppUtils::is_email($this->email)) {
            $this->error = '邮箱无效';
            return false;
        }
        $login = Login::model()->findByAttributes(array('email'=>$this->email));
        if($login !== null) {
            $this->error = '邮箱已注册';
            return false;
        }

        if(!isset($this->password)) {
            $this->error = '密码不能为空';
            return false;
        } else {
            if(function_exists('mb_strlen'))
                $length=mb_strlen($this->password, 'UTF-8');
            else
                $length=strlen($this->password);
            if($length < 6) {
                $this->error = '密码长度不能小于6位';
                return false;
            }
            if($length > 16) {
                $this->error = '密码长度不能多于16位';
                return false;
            }
        }
//        if(!isset($this->password_again) || $this->password != $this->password_again) {
//            $this->error = '两次输入密码不一致';
//            return false;
//        }
        return true;
    }

}

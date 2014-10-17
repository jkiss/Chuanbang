<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-24
 * Time: 下午3:51
 */

class QqController extends Controller{

    private $access_token;
    private $expires_in;
    private $refresh_token;
    private $openid;

    public function init() {
        parent::init();
        session_start();
    }

    public function actionLogin() {
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        $_SESSION['redirect_uri'] = Yii::app()->request->urlReferrer;
        // 获取授权码
        $url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.QQ_APP_ID.'&redirect_uri='.QQ_CALLBACK_URL.'&scope=get_info,get_user_info&state='.$_SESSION['state'];
        $this->redirect($url);
    }

    public function actionCallback($code,$state) {
        $redirect_uri = isset($_SESSION['redirect_uri']) ? $_SESSION['redirect_uri'] : Yii::app()->user->returnUrl;
        unset($_SESSION['redirect_uri']);
        if($state != $_SESSION['state']) {
            unset($_SESSION['state']);
            exit('非法请求');
        }

        // 获取token
        $url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id='.QQ_APP_ID.'&client_secret='.QQ_SECRET.'&code='.$code.'&redirect_uri='.QQ_CALLBACK_URL;
        $response = Http::request($url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response, true);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }
        $params = array();
        parse_str($response, $params);

        // 刷新token
        $url = 'https://graph.qq.com/oauth2.0/token?grant_type=refresh_token&client_id='.QQ_APP_ID.'&client_secret='.QQ_SECRET.'&refresh_token='.$params['refresh_token'];
        $response = Http::request($url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response, true);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }
        $params = array();
        parse_str($response, $params);

        $this->access_token = $params['access_token'];
        $this->expires_in = $params['expires_in'];
        $this->refresh_token = $params['refresh_token'];

        // 获取openid
        $url = "https://graph.qq.com/oauth2.0/me?access_token=".$this->access_token;
        $response = Http::request($url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
        }
        $response = json_decode($response, true);
        $this->openid = $response['openid'];

        // QQ资料
        $user_info = $this->get_user_info($code);
        try {
            $qq_user = QQUser::model()->findByAttributes(array('openid'=>$this->openid));
            if($qq_user === null) {
                $qq_user = new QQUser();
                $user = new User();
            } else {
                $user = $qq_user->user;
            }
            $qq_user->access_token = $this->access_token;
            $qq_user->expires_in = $this->expires_in;
            $qq_user->refresh_token = $this->refresh_token;
            $qq_user->openid = $this->openid;
            if($user_info) {
                $qq_user->nickname = $user_info['nickname'];
                $qq_user->gender = $user_info['gender'];
                if($user_info['figureurl_2']) {
                    $qq_user->head = AppUtils::syncAvatar($user_info['figureurl_qq_2']);
                } else {
                    $qq_user->head = AppUtils::syncAvatar($user_info['figureurl_qq_1']);
                }
            } else {
                $qq_user->nickname = time();
            }

            $user->nick = $qq_user->nickname;
            $user->head = $qq_user->head;
            $user->gender = $qq_user->gender == '女' ? 'F' : 'M';
            $user->type = USER::QQ;
            if(!$user->save()) throw new CHttpException(500, CJSON::encode($user->errors));

            $qq_user->user_id = $user->id;
            if(!$qq_user->save()) throw new CHttpException(500, CJSON::encode($qq_user->errors));

            $identity = new UserIdentity($qq_user->openid, $qq_user->access_token, User::QQ);
            if($identity->login(3600*24*30)) {
                $this->redirect(Yii::app()->createUrl('user/index'));
//                    $this->redirect($redirect_uri);
            } else {
                exit(CJSON::encode($this->response(500,'server error')));
            }

        } catch(CException $e) {
            $this->redirect(Yii::app()->createUrl('/site/error'));
        }
    }

    // 微博资料
    private function get_info() {
        $url = "https://graph.qq.com/user/get_info?access_token=".$this->access_token."&oauth_consumer_key=".QQ_APP_ID."&openid=".$this->openid."&format=json";
        $response = Http::request($url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
        }
        $user_info = json_decode($response, true);
        if($user_info['ret'] != 0) return false;
        return $user_info['data'];
    }

    /** QQ资料 */
    private function get_user_info() {
        $url = "https://graph.qq.com/user/get_user_info?access_token=".$this->access_token."&oauth_consumer_key=".QQ_APP_ID."&openid=".$this->openid;
        $response = Http::request($url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
        }

        $user_info = json_decode($response, true);
        if($user_info['ret'] != 0) return false;
        return $user_info;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-24
 * Time: 下午3:51
 */

class WeiboController extends Controller{

    public function init() {
        parent::init();
        require_once Yii::app()->basePath.'/extensions/weibo/saetv2.ex.class.php';
    }

    public function actionLogin() {
        $redirect_uri = urlencode(Yii::app()->request->urlReferrer);
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL.'?redirect_uri='.$redirect_uri);
        $this->redirect($code_url);
    }

    public function actionCallback($redirect_uri='') {
        if($redirect_uri == '') $redirect_uri = Yii::app()->user->returnUrl;
        $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            try {
                $token = $o->getAccessToken( 'code', $keys ) ;
            } catch (OAuthException $e) {
                $this->redirect(Yii::app()->baseUrl.'/site/error');
            }
        }

        if ($token) {
            $c = new SaeTClientV2( WB_AKEY , WB_SKEY , $token['access_token'] );
            $uid_get = $c->get_uid();
            $uid = $uid_get['uid'];
            $user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

            try {
                $weibo_user = WeiboUser::model()->findByAttributes(array('tid'=>$user_message['id']));
                if($weibo_user === null) {
                    $weibo_user = new WeiboUser();
                    $user = new User();
                } else {
                    $user = $weibo_user->user;
                }
                $weibo_user->access_token = $token['access_token'];
                $weibo_user->remind_in = $token['remind_in'];
                $weibo_user->expires_in = $token['expires_in'];
                $weibo_user->tid = $user_message['id'];
                $weibo_user->name = $user_message['name'];
                $weibo_user->screen_name = $user_message['screen_name'];
                $weibo_user->gender = $user_message['gender'];
                $weibo_user->head = AppUtils::syncAvatar($user_message['profile_image_url']);

                $user->nick = $weibo_user->screen_name;
                $user->head = $weibo_user->head;
                $user->gender = strtoupper($weibo_user->gender);
                $user->type = USER::WEIBO;
                if(!$user->save()) throw new CHttpException(500, CJSON::encode($user->errors));

                $weibo_user->user_id = $user->id;
                if(!$weibo_user->save()) throw new CHttpException(500, CJSON::encode($weibo_user->errors));

                $identity = new UserIdentity($weibo_user->tid, $weibo_user->access_token, User::WEIBO);
                if($identity->login(3600*24*30)) {

                    $this->redirect($redirect_uri);
                } else {
                    exit(CJSON::encode($this->response(500,'server error')));
                }

            } catch(CException $e) {
                $this->redirect(Yii::app()->createUrl('/site/error'));
            }
        }
    }
} 
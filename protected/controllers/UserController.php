<?php

class UserController extends Controller
{
    public $layout="//layouts/user";

    public $role;

    public function filters()
    {
        return array_merge(parent::filters(),array(
            'checkLogin - verifyEmail register activate login questions answers fans idols',
        ));
    }

    /** 验证邮箱是否可用 */
    public function actionVerifyEmail() {
        $email = Yii::app()->request->getPost('email');
        $login = Login::model()->findByAttributes(array('email'=>$email));
        if($login !== null) {
            echo CJSON::encode(array('exist'=>true));
        } else {
            echo CJSON::encode(array('exist'=>false));
        }
    }
    /** 用户注册 */
    public function actionRegister() {
        $model = new RegisterForm($_POST);
        if(!$model->validate()) exit(CJSON::encode($this->response(400, $model->error)));

        $email = $model->email;
        $password = UserIdentity::encrypt($model->password);
        $type = User::EMAIL;
        $nick = substr($email, 0,strpos($email, '@'));
        $now = time();

        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "INSERT INTO tbl_user(type,nick,createtime,updatetime) values('$type', '$nick', $now, $now)";
            $rows = DbUtils::execute($sql);
            if(empty($rows)) throw new CDbException('server error');
            $user_id = Yii::app()->db->getLastInsertID();

            $sql = "INSERT INTO tbl_login(user_id,email, password) values($user_id,'$email', '$password')";
            $rows = DbUtils::execute($sql);
            if(empty($rows)) throw new CDbException('server error');

            $trans->commit();
        } catch(CException $e) {
            $trans->rollBack();
            exit(CJSON::encode($this->response(500, $e->getMessage())));
        }

        $identity = new UserIdentity($email, $model->password, $type);
        if($identity->login()) {
            exit(CJSON::encode($this->response(0,'success')));
        } else {
            exit(CJSON::encode($this->response(500,'server error')));
        }
    }

    /** 登录 */
    public function actionLogin() {
        $model=new LoginForm($_POST);
        if(!$model->validate()) exit(CJSON::encode($this->response(400, $model->error)));

        $identity = new UserIdentity($model->email, $model->password, User::EMAIL);
        $duration=$model->rememberMe ? 3600*24*30 : 0; // 30 days
        if(!$identity->login($duration)) {
            exit(CJSON::encode($this->response(400, '用户名或密码有误')));
        } else {
            exit(CJSON::encode($this->response(0,'success')));
        }
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect('/');
    }

    // 找回密码
    public function actionRetrieve() {
        $req = Yii::app()->request;
        if($req->isPostRequest) {
            $email = $req->getPost('email');
            $step = $req->getPost('step');
            $login = Login::model()->findByAttributes(array('email'=>$email));
            if($login === null) exit(CJSON::encode($this->response(400, 'email is invalid')));
            if($login->isAvailable() !== true) {
                exit(CJSON::encode($this->response(500, 'fail', $login->errors)));
            }
            if($req->getPost('step') == "1") {
                // 邮箱发送校验码
                $check_code = AppUtils::randNum();
                $mailSender = new MailSender();
                if($mailSender->send($email, '找回密码', '校验码:'.$check_code)) {
                    $pwdHis = new PasswordHis();
                    $pwdHis->uid = $login->uid;
                    $pwdHis->email = $email;
                    $pwdHis->check_code = $check_code;
                    $pwdHis->check_code_expired = time() + 3600 * 1000 * 24;
                    if($pwdHis->save()) {
                        exit(CJSON::encode($this->response(0, 'Success')));
                    } else {
                        exit(CJSON::encode($this->response(500, 'Server Error')));
                    }
                } else {
                    exit(CJSON::encode($this->response(500, 'Mail Error')));
                }
            } else if($step == "2") {
                // 重置密码
                $check_code = $req->getPost('check_code');
                $password = $this->getPost('new_password');
                $pwdHis = PasswordHis::model()->find(array(
                    'condition'=>"email=:email and check_code=:check_code and check_code_expired>:now and result='PENDING'",
                    'params'=>array(':email'=>$email,'check_code'=>$check_code,':now'=>time()),
                    'order'=>'createtime desc',
                    'limit'=>1,
                ));
                if($pwdHis === null) {
                    exit(CJSON::encode($this->response(400, '验证码有误或已失效')));
                } else {
                    $login->password = UserIdentity::encrypt($password);
                    if($login->save()) {
                        $pwdHis->success();
                        exit(CJSON::encode($this->response(0, 'Success')));
                    } else {
                        exit(CJSON::encode($this->response(500, 'Server Error')));
                    }
                }
            }
        } else {
            $this->render('retrieve');
        }
    }

    /** 设置头像 */
    public function actionAvatar() {
        if (isset($_FILES['file']) && $_FILES['file']) {
            $uploader = new CbFileUpload();
            $url = $uploader->uploadAvatar($_FILES['file']);
            if(!$url) exit(CJSON::encode($this->response(500,'server error')));

            $user = User::model()->findByPk(Yii::app()->user->id);
            $user->head = $url;
            if($user->save()) {
                exit(CJSON::encode($this->response(0,'success', array('url'=>$url))));
            } else {
                exit(CJSON::encode($this->response(500,'server error')));
            }
        } else {
            exit(CJSON::encode($this->response(400, '参数有误')));
        }
    }

    /** 修改个人资料 */
    public function actionProfile() {
        $this->role = 'self';
        $user = User::model()->findByPk(Yii::app()->user->id);
        if(isset($_POST['User']))
        {
            $user->attributes=$_POST['User'];
            $user->save();
        }
        $this->render('profile', array(
            'user'=>$user,
        ));
    }

    /** 个人主页 */
    public function actionIndex() {
        $this->redirect(Yii::app()->createUrl('user/view',array('id'=>Yii::app()->user->id)));
    }

    /** 其他人主页 */
    public function actionView($id) {
        $rows = UserService::getById($id);
        $user = $rows[0];
        //相关回答
        $total_answers = UserService::countAnswersByAuthor($id);
        $offset = $this->getOffset(1, 8);
        $answers = UserService::listAnswersByAuthor($id, $offset, 8);

        // 关注他的人
        $total_fans = UserService::countFans($id);
        $fans = UserService::listFans($id, 0, 4);

        $data = array(
            'user'=>$user,
            'total_answers'=>$total_answers,
            'answers'=>$answers,
            'total_fans'=>$total_fans,
            'fans'=>$fans,
        );
        if($id == Yii::app()->user->id) {
            $this->role = 'self';
            $view = 'index';
        } else {
            $this->role = 'other';
            $view = 'other';
        }
        $this->render($view,$data);
    }

    /** 用户提问 */
    public function actionQuestions($id=0, $page = 1, $pageSize = 8) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = UserService::countQuestionsByAuthor($id);
        $offset = $this->getOffset($page, $pageSize);
        $questions = UserService::listQuestionsByAuthor($id, $offset, $pageSize);
        for($i = 0, $len = count($questions); $i < $len; $i++) {
            $questions[$i]['href'] = Yii::app()->createUrl('question/view', array('id'=>$questions[$i]['ques_id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$questions,
        );
        exit(CJSON::encode($rs));
    }

    /** 用户回答 */
    public function actionAnswers($id=0,$page = 1, $pageSize = 8) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = UserService::countAnswersByAuthor($id);
        $offset = $this->getOffset($page, $pageSize);
        $answers = UserService::listAnswersByAuthor($id, $offset, $pageSize);
        for($i = 0, $len = count($answers); $i < $len; $i++) {
            $answers[$i]['href'] = Yii::app()->createUrl('answer/view', array('id'=>$answers[$i]['ans_id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$answers,
        );
        exit(CJSON::encode($rs));
    }

    /** 用户收藏*/
    public function actionFavorite(){
        $this->role = 'self';
        $user_id = Yii::app()->user->id;

//        $offset = $this->getOffset(1, 15);
//        // 提问
//        $total_questions = FavService::countQuestionsByUserId($user_id);
//        $questions = FavService::listQuestionsByUserId($user_id, $offset, 15);
//
//        // 回答
//        $total_answers = FavService::countAnswersByUserId($user_id);
//        $answers = FavService::listAnswersByUserId($user_id, $offset, 15);
//
//        $total_compares = FavService::countComparesByUserId($user_id);
//        $compares = FavService::listComparesByUserId($user_id, $offset, 15);
//
//        $total_topics = FavService::countTopicsByUserId($user_id);
//        $topics = FavService::listTopicsByUserId($user_id, $offset, 15);

        $this->render('favorite', array(
//            'total_questions'=>$total_questions,
//            'questions'=>$questions,
//            'total_answers'=>$total_answers,
//            'answers'=>$answers,
//            'total_compares'=>$total_compares,
//            'compares'=>$compares,
//            'total_topics'=>$total_topics,
//            'topics'=>$topics,
        ));
    }

    /* 用户赞的 */
    public function actionZan(){
        $this->role = 'self';
        $this->render('zan');
    }

    /* 用户草稿 */
    public function actionDraft(){
        $this->role = 'self';
        $this->render('draft');
    }

    /* 用户消息 */
    public function actionInfo(){
        $this->role = 'self';
        $this->render('info');
    }

    /* 用户私信 */
    public function actionMsg(){
        $this->role = 'self';
        $this->render('msg');
    }

    /* 私信详情 */
    public function actionMsgDetail(){
        $this->role = 'self';
        $this->render('msg_detail');
    }

    /** 关注 */
    public function actionFollow() {
        $this->role = 'self';
        $this->render('follow');
    }

    /** 用户粉丝 */
    public function actionFans($id=0,$page = 1, $pageSize = 8) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = UserService::countFans($id);
        $offset = $this->getOffset($page, $pageSize);
        $users = UserService::listFans($id, $offset, $pageSize);
        for($i = 0, $len = count($users); $i < $len; $i++) {
            $users[$i]['href'] = Yii::app()->createUrl('user/view', array('id'=>$users[$i]['id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$users,
        );
        exit(CJSON::encode($rs));
    }

}
<?php

class TopicController extends Controller
{
    /**话题-导航页面 */
    public function actionIndex()
    {
        $models_lf = TopicService::getSInfoByTime(0, 10);

        $rows = TopicService::listByHot(0, 9);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'cover'=>$row['cover'],
                    'follow'=>$row['follow'],
                    'support'=>$row['support'],
                    'total_cele'=>$row['total_cele'],
                    'celebrities'=>array(),
                );
            }
            array_push($data[$row['id']]['celebrities'], array(
                'id'=>$row['celebrity_id'],
                'head'=>$row['head'],
            ));
        }
        $models_hot = array_values($data);

        $models_more = TopicService::getSInfoByHot(3, 12);

        $this->render('index',array(
            'trend'=>$models_lf,
            'hots'=>$models_hot,
            'more'=>$models_more,
        ));
    }

    /** 话题-主界面 */
    public function actionView($id) {
        $total = TopicService::countAnswers($id);
        // 资料
        $rows = TopicService::getById($id);
        $model = $rows[0];

        $models_lf = TopicService::getSInfoByPos($id);

        // 动态
        $rows = TopicService::listAnswersByTime($id, 0, 15);
        $answers = array();
        foreach($rows as $row) {
            array_push($answers, array(
                'id'=>$row['ans_id'],
                'img'=>$row['img'],
                'ans_time'=>$row['ans_time'],
                'author'=>array(
                    'id'=>$row['user_id'],
                    'nick'=>$row['user_nick'],
                    'head'=>$row['user_head'],
                ),
                'detail'=>array(
                    'celebrity'=>array(
                        'id'=>$row['celebrity_id'],
                        'name'=>$row['celebrity_name']
                    ),
                    'brand'=>array(
                        'id'=>$row['brand_id'],
                        'name'=>$row['brand_name'],
                    ),
                    'style'=>$row['style'],
                    'clothes_type'=>$row['clothes_type'],
                ),
            ));
        }

         $this->render('view',array(
             'model'=>$model,
             'suggest'=>$models_lf,
             'answers'=>$answers,
             'total_page'=>$this->getTotalPage($total, 15),
         ));
    }

    /**相关回答 **/
    public function actionAnswer($id, $page = 1, $pageSize = 10){
        $total = TopicService::countAnswers($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = TopicService::listAnswersByTime($id, $offset, $pageSize);
        $answers = array();
        foreach($rows as $row) {
            array_push($answers, array(
                'id'=>$row['ans_id'],
                'img'=>$row['img'],
                'ans_time'=>$row['ans_time'],
                'author'=>array(
                    'id'=>$row['user_id'],
                    'nick'=>$row['user_nick'],
                    'head'=>$row['user_head'],
                ),
                'detail'=>array(
                    'celebrity'=>array(
                        'id'=>$row['celebrity_id'],
                        'name'=>$row['celebrity_name']
                    ),
                    'brand'=>array(
                        'id'=>$row['brand_id'],
                        'name'=>$row['brand_name'],
                    ),
                    'style'=>$row['style'],
                    'clothes_type'=>$row['clothes_type'],
                ),
            ));
        }
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$answers,
            ));
        } else {
            $this->render('celebrity',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'answers'=>$answers
            ));
        }
    }

    // 关注
    public function actionFollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = TopicService::follow($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    // 关注-取消
    public function actionUnfollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = TopicService::unfollow($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    //赞
    public function actionSupport() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = TopicService::support($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    //赞-取消
    public function actionUnsupport() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = TopicService::unsupport($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }
}
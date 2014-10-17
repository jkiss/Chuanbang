<?php

class QuestionController extends Controller
{
    public function filters()
    {
        return array_merge(parent::filters(),array(
            'checkLogin - index,pending,view,fresh,listPending',
        ));
    }

    /**待答-导航页面 */
    public function actionIndex()
    {
        $models_lf = QuestionService::listPendingByTime(0, 10);

        $total = QuestionService::countPending();
        $models_hot = QuestionService::listPendingByHot(0, 18);

        $this->render('index',array(
            'trend'=>$models_lf,
            'total_page'=>$this->getTotalPage($total, 18),
            'models'=>$models_hot,
        ));
    }

    /** 分页 */
    public function actionList($page=1,$pageSize=18) {
        $total = QuestionService::countPending();
        $offset = $this->getOffset($page, $pageSize);
        $rows = QuestionService::listPendingByHot($offset, $pageSize);

        exit(CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'models'=>$rows,
        )));
    }

    /** 查看 */
    public function actionView($id)
    {
        if(Yii::app()->user->isGuest) {
            $models_lf = QuestionService::listPendingByTime(0, 10);
            $rows = QuestionService::getById($id);
        } else {
            $models_lf = QuestionService::listPendingByTime(0, 10, Yii::app()->user->id);
            $rows = QuestionService::getById($id, Yii::app()->user->id);
        }

        $row0 = $rows[0];
        $model = array(
            'id'=>$row0['id'],
            'content'=>$row0['content'],
            'follow'=>$row0['follow'],
            'support'=>$row0['support'],
            'total_imgs'=>$row0['total_imgs'],
            'total_comments'=>$row0['total_comments'],
            'total_answers'=>$row0['total_answers'],
            'time'=>$row0['createtime'],
            'author'=>array(
                'id'=>$row0['user_id'],
                'nick'=>$row0['user_nick'],
                'head'=>$row0['user_head'],
            ),
            'imgs'=>array(),
            'comments'=>array(),
        );

        foreach($rows as $row) {
            $model['imgs'][] = array(
                'id'=>$row['ques_img_id'],
                'url'=>$row['img'],
                'supports'=>$row['img_supports'],
                'support'=>$row['img_support'],
            );
        }

        // 评论
        $comments = QuestionService::listCommentsByTime($model['id']);
        if(isset($comments)) {
            foreach($comments as $comment) {
                array_push($model['comments'], array(
                    'id'=>$comment['id'],
                    'content'=>$comment['content'],
                    'time'=>$comment['createtime'],
                    'author'=>array(
                        'id'=>$comment['user_id'],
                        'head'=>$comment['user_head'],
                        'nick'=>$comment['user_nick'],
                    )
                ));
            }
        }

        $data = array(
            'trend'=>$models_lf,
            'model'=>$model,
            'compares'=>array(),
        );
        if(!Yii::app()->user->isGuest) {
            $rows = UserService::getById(Yii::app()->user->id);
            $data['user'] = $rows[0];

            // 对比-草稿箱
            $rows = CompareService::listDraft();
            $data['compares'] = $rows;
        }
        $this->render('view',$data);
    }

    /** 草稿箱 */
    public function actionDraft() {
        $rows = QuestionService::getDraft(Yii::app()->user->id);
        exit(CJSON::encode($rows));
    }

    public function actionDelimg() {
        if(!isset($_POST['url']) || empty($_POST['url']))
            exit(CJSON::encode($this->response(400, '参数有误')));
        $success = QuestionService::delDraft(Yii::app()->user->id,$_POST['url']);
        if($success) {
            AppUtils::deleteImage($_POST['url']);
            exit(CJSON::encode($this->response(0, 'success')));
        } else {
            exit(CJSON::encode($this->response(500, 'fail')));
        }
    }

    /** 提问 */
    public function actionAsk() {
        if(Yii::app()->request->isPostRequest) {
            $content = isset($_POST['content']) ? $_POST['content'] : null;
            $success = QuestionService::applyDraft(Yii::app()->user->id, $content);
            if($success) {
                exit(CJSON::encode($this->response(0, 'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        $this->render('ask');
    }

    /** 添加评论 */
    public function actionComment() {
        if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['content']) || empty($_POST['content'])) exit(CJSON::encode($this->response(400, 参数有误)));
        $id = intval($_POST['id']);
        $content = $_POST['content'];
        $success = QuestionService::saveComment($id, $content, Yii::app()->user->id);
        if($success) {
            exit(CJSON::encode($this->response(0,'success')));
        } else {
            exit(CJSON::encode($this->response(500, 'fail')));
        }
    }

    /** 评论列表 */
    public function actionComments($id, $page = 1, $pageSize = 10) {
        $total = QuestionService::countComments($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = QuestionService::listCommentsByTime($id, $offset, $pageSize);
        $data = array();
        foreach($rows as $row) {
            array_push($data, array(
                'id'=>$row['id'],
                'content'=>$row['content'],
                'time'=>$row['createtime'],
                'author'=>array(
                    'id'=>$row['user_id'],
                    'head'=>$row['user_head'],
                    'nick'=>$row['user_nick'],
                )
            ));
        }
        echo CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'rows'=>$data,
        ));
    }

    // 关注
    public function actionFollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = QuestionService::follow($req->getPost('id'), Yii::app()->user->id);
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
            $success = QuestionService::unfollow($req->getPost('id'), Yii::app()->user->id);
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
            $success = QuestionService::support($req->getPost('id'), Yii::app()->user->id);
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
            $success = QuestionService::unsupport($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    /** 图片(提问)-赞 */
    public function actionSupportImg() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = QuestionService::supportImg($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
    }

    /** 图片(提问)-取消赞 */
    public function actionUnsupportImg() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = QuestionService::unsupportImg($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
    }
}
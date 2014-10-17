<?php

class CompareController extends Controller {

    /**对比-导航页面 */
    public function actionIndex()
    {
        $rows = CompareService::listByTime(0, 10);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'total_comments'=>$row['total_comments'],
                    'imgs'=>array()
                );
            }
            if(count($data[$row['id']]['imgs']) < 2) array_push($data[$row['id']]['imgs'], $row['img']);
        }
        $models_lf = array_values($data);

        $total = CompareService::count();
        $rows = CompareService::listByHot(0, 12);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'follow'=>$row['follow'],
                    'support'=>$row['support'],
                    'createtime'=>$row['createtime'],
                    'total_imgs'=>$row['total_imgs'],
                    'total_comments'=>$row['total_comments'],
                    'author'=>array(
                        'id'=>$row['user_id'],
                        'nick'=>$row['user_nick'],
                        'head'=>$row['user_head'],
                    ),
                    'imgs'=>array()
                );
            }
            if(count($data[$row['id']]['imgs']) < 2) array_push($data[$row['id']]['imgs'], array(
                'url'=>$row['img'],
                'celebrity'=>$row['celebrity'],
                'brand'=>$row['brand'],
                'clothes_type'=>$row['clothes_type'],
                'style'=>$row['style'],
            ));
        }
        $models = array_values($data);

        $this->render('index',array(
            'trend'=>$models_lf,
            'total_page'=>$this->getTotalPage($total, 12),
            'models'=>$models,
        ));
    }

    /** 分页 */
    public function actionList($page=1,$pageSize=20) {
        $total = CompareService::count();
        $offset = $this->getOffset($page, $pageSize);
        $rows = CompareService::listByHot($offset, $pageSize);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'follow'=>$row['follow'],
                    'support'=>$row['support'],
                    'createtime'=>$row['createtime'],
                    'total_imgs'=>$row['total_imgs'],
                    'total_comments'=>$row['total_comments'],
                    'author'=>array(
                        'id'=>$row['user_id'],
                        'nick'=>$row['user_nick'],
                        'head'=>$row['user_head'],
                    ),
                    'imgs'=>array()
                );
            }
            if(count($data[$row['id']]['imgs']) < 2) array_push($data[$row['id']]['imgs'], array(
                'url'=>$row['img'],
                'celebrity'=>$row['celebrity'],
                'brand'=>$row['brand'],
                'clothes_type'=>$row['clothes_type'],
                'style'=>$row['style'],
            ));
        }
        $models = array_values($data);
        echo CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'compares'=>$models,
        ));
    }

    /**对比-主界面 */
    public function actionView($id) {
        $rows = CompareService::listByTime(0, 10);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'total_comments'=>$row['total_comments'],
                    'imgs'=>array()
                );
            }
            if(count($data[$row['id']]['imgs']) < 2) array_push($data[$row['id']]['imgs'], $row['img']);
        }
        $models_lf = array_values($data);

        $rows = CompareService::getById($id);
        $model = array();
        foreach($rows as $row) {
            if(empty($model)) {
                $model = array(
                    'id'=>intval($row['id']),
                    'title'=>$row['title'],
                    'author'=>array(
                        'id'=>$row['user_id'],
                        'nick'=>$row['user_nick'],
                        'head'=>$row['user_head'],
                    ),
                    'createtime'=>$row['createtime'],
                    'follow'=>$row['follow'],
                    'support'=>$row['support'],
                    'total_follows'=>intval($row['total_follows']),
                    'total_ups'=>intval($row['total_ups']),
                    'total_imgs'=>intval($row['total_imgs']),
                    'total_comments'=>intval($row['total_comments']),
                    'imgs'=>array(),
                    'comments'=>array(),
                );
            }
            array_push($model['imgs'], array(
                'url'=>$row['img'],
                'celebrity'=>$row['celebrity'],
                'brand'=>$row['brand'],
                'clothes_type'=>$row['clothes_type'],
                'style'=>$row['style'],
            ));
        }

        $comments = CompareService::listCommentsByTime($model['id'], 0, 20);
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

        $this->render('view',array(
            'trend'=>$models_lf,
            'model'=>$model,
        ));
    }

    /**草稿箱 */
    public function actionDraft() {
        $rows = CompareService::listDraft();
        $images = array();
        foreach($rows as $row) {
            $images[] = $row['img'];
        }
        echo CJSON::encode($images);
    }

    //添加图片
    public function actionAddImg() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $url = $req->getPost('url');
            $ans_id = $req->getPost('ans_id');
            $result = CompareService::addImg($url,$ans_id);
            if($result) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500,'server error')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    //删除图片
    public function actionDelImg() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $url = $req->getPost('url');
            $result = CompareService::delImg($url);
            if($result) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500,'server error')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    /** 开始比较 */
    public function actionApply() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $title = $req->getPost('title');
            $result = CompareService::apply($title);
            if($result) {
                exit(CJSON::encode($this->response(0,'success', array('id'=>$result))));
            } else {
                exit(CJSON::encode($this->response(500,'server error')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    /** 添加评论 */
    public function actionComment() {
        if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['content']) || empty($_POST['content'])) exit(CJSON::encode($this->response(400, 参数有误)));
        $id = intval($_POST['id']);
        $content = $_POST['content'];
        $success = CompareService::saveComment($id, $content, Yii::app()->user->id);
        if($success) {
            exit(CJSON::encode($this->response(0,'success')));
        } else {
            exit(CJSON::encode($this->response(500, 'fail')));
        }
    }

    /** 评论列表 */
    public function actionComments($id, $page = 1, $pageSize = 10) {
        $total = CompareService::countComments($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = CompareService::listCommentsByTime($id, $offset, $pageSize);
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
            $success = CompareService::follow($req->getPost('id'), Yii::app()->user->id);
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
            $success = CompareService::unfollow($req->getPost('id'), Yii::app()->user->id);
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
            $success = CompareService::support($req->getPost('id'), Yii::app()->user->id);
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
            $success = CompareService::unsupport($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }
} 
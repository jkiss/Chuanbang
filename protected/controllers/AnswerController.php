<?php

class AnswerController extends Controller
{
    public function filters()
    {
        return array_merge(parent::filters(),array(
            'checkLogin - view,fresh',
        ));
    }

    /** 最新动态 */
    public function actionFresh($page = 1, $pageSize = 30) {
        $offset = $this->getOffset($page, $pageSize);
        $answers = AnswerService::listByTime($offset, $pageSize);
        $data = array();
        foreach($answers as $answer) {
            array_push($data, array(
                'id'=>$answer['ans_id'],
                'href'=>Yii::app()->createUrl('answer/'.$answer['ans_id']),
                'time'=>$answer['ans_time'],
                'celebrity'=>array(
                    'id'=>$answer['celebrity_id'],
                    'name'=>$answer['celebrity_name'],
                ),
                'brand'=>array(
                    'id'=>$answer['brand_id'],
                    'name'=>$answer['brand_name'],
                ),
                'author'=>array(
                    'id'=>$answer['user_id'],
                    'nick'=>$answer['user_nick'],
                    'head'=>$answer['user_head'],
                )
            ));
        }
        echo CJSON::encode($data);
    }

    public function actionListClothesType() {
        $data = array();
        $models = ClothesType::model()->findAll();
        foreach($models as $model) {
            $data[] = array(
                'id'=>$model->id,
                'value'=>$model->name,
            );
        }
        echo CJSON::encode($data);
    }

    // 自动完成-名人
    public function actionSuggestCelebrity($term) {
        $sql = "SELECT id,`name`,name_cn,name_en,head FROM tbl_celebrity WHERE isdel='N' AND (name_cn LIKE :search OR name_en LIKE :search) ORDER BY score DESC,id DESC LIMIT 5";
        $rows = DbUtils::query($sql, array(
            ':search'=>'%'.$term.'%',
        ));
        $data = array();
        foreach($rows as $row) {
            $data[] = array(
                'id'=>$row['id'],
                'name'=>$row['name'],
                'name_cn'=>$row['name_cn'],
                'name_en'=>$row['name_en'],
                'head'=>$row['head'],
            );
        }
        echo CJSON::encode($data);
    }

    // 自动完成-品牌
    public function actionSuggestBrand($term) {
        $sql = "SELECT id,`name`,name_cn,name_en,logo FROM tbl_brand WHERE isdel='N' AND (name_cn LIKE :search OR name_en LIKE :search) ORDER BY score DESC,id DESC LIMIT 5";
        $rows = DbUtils::query($sql, array(
            ':search'=>'%'.$term.'%',
        ));
        $data = array();
        foreach($rows as $row) {
            $data[] = array(
                'id'=>$row['id'],
                'name'=>$row['name'],
                'name_cn'=>$row['name_cn'],
                'name_en'=>$row['name_en'],
                'logo'=>$row['logo'],
            );
        }
        echo CJSON::encode($data);
    }

    // 自动完成-事件
    public function actionSuggestHappens($term) {
        $sql = "SELECT DISTINCT happens,occurdate,place FROM tbl_answer WHERE isdel='N' AND happens LIKE :search ORDER BY score DESC,id DESC LIMIT 5";
        $rows = DbUtils::query($sql, array(
            ':search'=>'%'.$term.'%',
        ));
        $data = array();
        foreach($rows as $row) {
            $data[] = array(
                'happens'=>$row['happens'],
                'occurdate'=>$row['occurdate'],
                'place'=>$row['place'],
            );
        }
        echo CJSON::encode($data);
    }

    // 自动完成-地点
    public function actionSuggestPlace($term) {
        $sql = "SELECT DISTINCT place FROM tbl_answer WHERE isdel='N' AND place LIKE :search ORDER BY score DESC,id DESC LIMIT 5";
        $rows = DbUtils::query($sql, array(
            ':search'=>'%'.$term.'%',
        ));
        $data = array();
        foreach($rows as $row) {
            $data[] = $row['place'];
        }
        echo CJSON::encode($data);
    }

    /** 查看 */
    public function actionView($id) {
        // 相关名人
        $celebrities = array();
        // 相关品牌
        $brands = array();
        // 相关话题
        $topics = AnswerService::getTopics($id);

        if(Yii::app()->user->isGuest) {
            $rows = AnswerService::queryById($id);
        } else {
            $rows = AnswerService::queryById($id, Yii::app()->user->id);
        }
        // 回答
        $row0 = $rows[0];
        $answer = array(
            'id'=>$row0['ans_id'],
            'ques_id'=>$row0['ques_id'],
            'content'=>$row0['content'],
            'happens'=>$row0['happens'],
            'occurdate'=>$row0['occurdate'],
            'place'=>$row0['place'],
            'hot'=>$row0['hot'],
            'follow'=>$row0['follow'],
            'support'=>$row0['support'],
            'total_comments'=>intval($row0['total_comments']),
            'total_follows'=>intval($row0['total_follows']),
            'total_supports'=>intval($row0['total_supports']),
            'time'=>$row0['createtime'],
            'author'=>array(
                'id'=>$row0['user_id'],
                'head'=>$row0['user_head'],
                'nick'=>$row0['user_nick']
            ),
            'celebrities'=>array(),
            'imgs'=>array(),
        );
        $celebrities_x = array();
        foreach($rows as $row) {
            // 相关名人
            if(!array_key_exists($row['celebrity_id'], $celebrities)) {
                $celebrities[$row['celebrity_id']] = array(
                    'id'=>$row['celebrity_id'],
                    'name_cn'=>$row['celebrity_name_cn'],
                    'name_en'=>$row['celebrity_name_en'],
                    'head'=>$row['celebrity_head'],
                );
            }
            // 相关品牌
            if(!array_key_exists($row['brand_id'], $brands)) {
                $brands[$row['brand_id']] = array(
                    'id'=>$row['brand_id'],
                    'name_cn'=>$row['brand_name_cn'],
                    'name_en'=>$row['brand_name_en'],
                    'logo'=>$row['brand_logo'],
                    'follows'=>$row['brand_follows'],
                );
            }

            // 回答
            if(!array_key_exists($row['celebrity_id'], $celebrities_x)) {
                $celebrities_x[$row['celebrity_id']] = array(
                    'id'=>$row['celebrity_id'],
                    'name'=>$row['celebrity_name'],
                    'attaches'=>array(),
                );
            }

            array_push($celebrities_x[$row['celebrity_id']]['attaches'], array(
                'brand_id'=>$row['brand_id'],
                'brand_name'=>$row['brand_name'],
                'clothes_type'=>$row['clothes_type'],
                'style'=>$row['style'],
            ));
        }
        $answer['celebrities'] = array_values($celebrities_x);

        // 回答相关图片
        $ans_imgs = AnswerService::listPictures($answer['id']);
        foreach($ans_imgs as $img) {
            if(!isset($answer['imgs'])) {
                $answer['imgs'] = array();
            }
            $answer['imgs'][] = $img['img'];
        }

        // 提问
        if(Yii::app()->user->isGuest) {
            $ques_rows = QuestionService::getById(intval($answer['ques_id']));
        } else {
            $ques_rows = QuestionService::getById(intval($answer['ques_id']), Yii::app()->user->id);
        }

        $ques_row0 = $ques_rows[0];
        $question = array(
            'id'=>$ques_row0['id'],
            'content'=>$ques_row0['content'],
            'follow'=>$ques_row0['follow'],
            'support'=>$ques_row0['support'],
            'time'=>$ques_row0['createtime'],
            'total_answers'=>intval($ques_row0['total_answers']),
            'author'=>array(
                'id'=>$ques_row0['user_id'],
                'nick'=>$ques_row0['user_nick'],
                'head'=>$ques_row0['user_head'],
            ),
            'imgs'=>array(),
        );
        foreach($ques_rows as $ques_row) {
            $question['imgs'][] = array(
                'id'=>$ques_row['ques_img_id'],
                'url'=>$ques_row['img'],
                'supports'=>intval($ques_row['img_supports']),
                'support'=>$ques_row['img_support'],
            );
        }

        // 评论
        $comments = array();
        $rows = AnswerService::listCommentsByTime($answer['id']);
        foreach($rows as $row) {
            array_push($comments, array(
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

        // 其他答案
        $answers = array();
        if($question['total_answers'] > 1) {
            if(Yii::app()->user->isGuest) {
                $rows = QuestionService::listAnswersByHot($question['id']);
            } else {
                $rows = QuestionService::listAnswersByHot($question['id'], Yii::app()->user->id);
            }
            foreach($rows as $row) {
                if($row['ans_id'] == $id) continue;
                if(!array_key_exists($row['ans_id'], $answers)) {
                    $answers[$row['ans_id']] = array(
                        'id'=>$row['ans_id'],
                        'content'=>$row['content'],
                        'happens'=>$row['happens'],
                        'occurdate'=>$row['occurdate'],
                        'place'=>$row['place'],
                        'follow'=>$row['follow'],
                        'support'=>$row['support'],
                        'total_comments'=>$row['total_comments'],
                        'total_follows'=>$row['total_follows'],
                        'total_supports'=>$row['total_supports'],
                        'time'=>$row['createtime'],
                        'author'=>array(
                            'id'=>$row['user_id'],
                            'head'=>$row['user_head'],
                            'nick'=>$row['user_nick']
                        ),
                        'celebrities'=>array(),
                    );
                }
                // 回答
                if(!array_key_exists($row['celebrity_id'], $answers[$row['ans_id']]['celebrities'])) {
                    $answers[$row['ans_id']]['celebrities'][$row['celebrity_id']] = array(
                        'id'=>$row['celebrity_id'],
                        'name'=>$row['celebrity_name'],
                        'attaches'=>array(),
                    );
                }
                array_push($answers[$row['ans_id']]['celebrities'][$row['celebrity_id']]['attaches'], array(
                    'brand_id'=>$row['brand_id'],
                    'brand_name'=>$row['brand_name'],
                    'clothes_type'=>$row['clothes_type'],
                    'style'=>$row['style'],
                ));
            }

            foreach($answers as &$a) {
                $a['celebrities'] = array_values($a['celebrities']);
            }
            $answers = array_values($answers);
        }

        $compares = array();
        if(!Yii::app()->user->isGuest) {
            // 对比-草稿箱
            $compares = CompareService::listDraft();
        }

        $this->render('//question/answer',array(
            'celebrities'=>$celebrities,
            'brands'=>$brands,
            'topics'=>$topics,
            'question'=>$question,
            'answer'=>$answer,
            'comments'=>$comments,
            'answers'=>$answers,
            'compares'=>$compares,
        ));
    }
    /** 提交 */
    public function actionSubmit() {
        if(!isset($_POST['AnswerForm']['ques_id']) || intval($_POST['AnswerForm']['ques_id']) == 0) exit(CJSON::encode($this->response(400, "fail")));
        if(!isset($_POST['AnswerDetailForm']) || empty($_POST['AnswerDetailForm'])) exit(CJSON::encode($this->response(400, "fail")));

        // 回答
        $answerForm = new AnswerForm();
        $answerForm->attributes=$_POST['AnswerForm'];
        $answerForm->user_id = Yii::app()->user->id;

        // 详情
        $details = array();
        foreach($_POST['AnswerDetailForm'] as $detail) {
            $detailModel = new AnswerDetailForm();
            $detailModel->attributes = $detail;
            array_push($details, $detailModel);
        }

        // 配图
        $images = array();
        if(isset($_FILES['file']) && !empty($_FILES['file'])) {
            $upload = new CbFileUpload();
            $images = $upload->uploadMultFiles($_FILES['file']);
            if(empty($images)) {
                exit(CJSON::encode($this->response(400, "fail")));
            }
        }

        // 保存
        if(empty($images)) {
            $id = AnswerService::save($answerForm, $details);
        } else {
            $id = AnswerService::save($answerForm, $details, $images);
        }
        if($id > 0) {
            exit(CJSON::encode($this->response(0,'success', array('id'=>$id))));
        } else {
            if(!empty($imgs)) {
                foreach($imgs as $img) {
                    AppUtils::deleteImage($img);
                }
            }
            exit(CJSON::encode($this->response(500, "sever error")));
        }

    }

    /** 添加评论 */
    public function actionComment() {
        if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['content']) || empty($_POST['content'])) exit(CJSON::encode($this->response(400, 参数有误)));
        $id = intval($_POST['id']);
        $content = $_POST['content'];
        $success = AnswerService::saveComment($id, $content, Yii::app()->user->id);
        if($success) {
            exit(CJSON::encode($this->response(0,'success')));
        } else {
            exit(CJSON::encode($this->response(500, 'fail')));
        }
    }

    /** 评论列表 */
    public function actionComments($id, $page = 1, $pageSize = 10) {
        $total = AnswerService::countComments($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = AnswerService::listCommentsByTime($id, $offset, $pageSize);
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

    // 收藏
    public function actionFollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = AnswerService::follow($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    // 收藏-取消
    public function actionUnfollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = AnswerService::unfollow($req->getPost('id'), Yii::app()->user->id);
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
            $success = AnswerService::support($req->getPost('id'), Yii::app()->user->id);
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
            $success = AnswerService::unsupport($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }
}
<?php

class CelebrityController extends Controller
{
    public $layout="//layouts/celebrity";

    public $model;

    protected function beforeAction($action)
    {
        if(isset($this->actionParams['id'])) {
            $rows = CelebrityService::getById($this->actionParams['id']);
            $this->model = $rows[0];
        }
        return parent::beforeAction($action);
    }


    /** 明星-导航页面 */
    public function actionIndex()
    {
        $this->layout = "//layouts/web";
        $models_lf = CelebrityService::getSInfoByTime(0, 10);

        $models_hot = CelebrityService::listByHot(0, 20);
        $data = array();
        foreach($models_hot as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'name'=>$row['name'],
                    'name_cn'=>$row['name_cn'],
                    'name_en'=>$row['name_en'],
                    'head'=>$row['head'],
                    'follow'=>$row['follow'],
                    'total_qa'=>$row['total_qa'],
                    'total_fans'=>$row['total_fans'],
                    'details'=>array(),
                );
            }
            array_push($data[$row['id']]['details'], array(
                'id'=>$row['ans_id'],
                'img'=>$row['img'],
                'brand'=>$row['brand_name'],
                'author'=>array(
                    'id'=>$row['user_id'],
                    'nick'=>$row['user_nick'],
                    'head'=>$row['user_head'],
                ),
                'createtime'=>$row['ans_time'],
            ));
        }
        $models_hot = array_values($data);

        $models_more = CelebrityService::getSInfoByHot(20, 36);
        $this->render('index',array(
            'trend'=>$models_lf,
            'hots'=>$models_hot,
            'more'=>$models_more,
        ));
    }

    //明星-主页面
    public function actionView($id, $page = 1, $pageSize = 20){
        // 动态
        $total = CelebrityService::countAnswers($id);
        $answers = CelebrityService::listSAnswersByTime($id, 0, 20);
        // 相关活动
        $topics = CelebrityService::listTopicsByTime($id, 0, 2);
        // 相关品牌
        $brands = CelebrityService::listBrandsByTime($id, 0, 2);
        // 粉丝
        $fans = CelebrityService::listFansByTime($id, 0, 2);

        $this->render('view',array(
            'model'=>$this->model,
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'answers'=>$answers,
            'topics'=>$topics,
            'brands'=>$brands,
            'fans'=>$fans,
        ));
    }

    /**动态 **/
    public function actionFresh($id, $page = 1, $pageSize = 5){
        $total = CelebrityService::countAnswers($id);
        // 动态
        $offset = $this->getOffset($page, $pageSize);
        $rows = CelebrityService::listMAnswersByTime($id, $offset, $pageSize);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['ans_id'], $data)) {
                $data[$row['ans_id']] = array(
                    'id'=>$row['ans_id'],
                    'ques_id'=>$row['ques_id'],
                    'img'=>$row['img'],
                    'happens'=>$row['happens'],
                    'occurdate'=>$row['occurdate'],
                    'place'=>$row['place'],
                    'createtime'=>$row['createtime'],
                    'author'=>array(
                        'id'=>$row['user_id'],
                        'nick'=>$row['user_nick'],
                        'head'=>$row['user_head'],
                    ),
                    'total_imgs'=>$row['total_imgs'],
                    'total_comments'=>$row['total_comments'],
                    'total_answers'=>$row['total_answers'],
                    'details'=>array(),
                );
            }
            array_push($data[$row['ans_id']]['details'], array(
                'brand'=>$row['brand_name'],
                'clothes_type'=>$row['clothes_type'],
                'style'=>$row['style'],
            ));
        }
        $answers = array_values($data);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$answers,
            ));
        } else {
            $this->render('fresh',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'answers'=>$answers,
            ));
        }
    }

    /**相关品牌 **/
    public function actionBrands($id, $page = 1, $pageSize = 10){
        $total = CelebrityService::countBrands($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = CelebrityService::listBrandsByTime($id, $offset, $pageSize);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$rows,
            ));
        } else {
            $this->render('brands',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'brands'=>$rows
            ));
        }
    }

    /**相关话题 */
    public function actionTopics($id, $page = 1, $pageSize = 3){
        $total = CelebrityService::countTopics($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = CelebrityService::listTopicsByTime($id, $offset, $pageSize);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$rows,
            ));
        } else {
            $this->render('topics',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'topics'=>$rows
            ));
        }
    }

    /**粉丝 **/
    public function actionFans($id, $page = 1, $pageSize = 4){
        $total = CelebrityService::countFans($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = CelebrityService::listMFansByTime($id, $offset, $pageSize);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$rows,
            ));
        } else {
            $this->render('fans',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'fans'=>$rows
            ));
        }
    }

    // 关注
    public function actionFollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = CelebrityService::follow($req->getPost('id'), Yii::app()->user->id);
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
            $success = CelebrityService::unfollow($req->getPost('id'), Yii::app()->user->id);
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

    public function actionSuggest($term) {
        $data = array();
        $models = Star::model()->findAll(array(
            'condition'=>'name_cn like :search or name_en like :search',
            'params'=>array(
                ":search"=>'%'.$term.'%',
            ),
            'limit'=>5,
            'order'=>'id desc',
        ));

        foreach($models as $model) {
            $data[] = array(
                'id'=>$model->id,
                'name'=>$model->name,
            );
        }
        echo CJSON::encode($data);
    }
}
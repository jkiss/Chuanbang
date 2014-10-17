<?php

class BrandController extends Controller
{
    public $layout="//layouts/brand";

    public $model;

    protected function beforeAction($action)
    {
        if(isset($this->actionParams['id'])) {
            $rows = BrandService::getById($this->actionParams['id']);
            $this->model = $rows[0];
        }
        return parent::beforeAction($action);
    }

    /** 品牌-导航页面 */
    public function actionIndex()
    {
        $this->layout = "//layouts/web";

        $models_lf = BrandService::getSInfoByTime(0, 10);
        $models_hot = BrandService::listByHot(0, 20);
        $data = array();
        foreach($models_hot as $row) {
            if(!array_key_exists($row['id'], $data)) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'name'=>$row['name'],
                    'name_cn'=>$row['name_cn'],
                    'name_en'=>$row['name_en'],
                    'logo'=>$row['logo'],
                    'follow'=>$row['follow'],
                    'total_qa'=>$row['total_qa'],
                    'total_fans'=>$row['total_fans'],
                    'details'=>array(),
                );
            }
            array_push($data[$row['id']]['details'], array(
                'id'=>$row['ans_id'],
                'img'=>$row['img'],
                'celebrity'=>$row['celebrity_name'],
                'author'=>array(
                    'id'=>$row['user_id'],
                    'nick'=>$row['user_nick'],
                    'head'=>$row['user_head'],
                ),
                'createtime'=>$row['ans_time'],
            ));
        }
        $models_hot = array_values($data);

        $models_more = BrandService::getSInfoByHot(20, 36);
        $this->render('index',array(
            'trend'=>$models_lf,
            'hots'=>$models_hot,
            'more'=>$models_more,
        ));
    }

    /**品牌-主页面 */
    public function actionView($id) {
        $total = BrandService::countAnswers($id);
        // 产品
        $rows = BrandService::getFProductByTime($id);
        $product = array();
        foreach($rows as $row) {
            if(empty($product)) {
                $product = array(
                    'id'=>$row['id'],
                    'brand_id'=>$row['brand_id'],
                    'name'=>$row['name'],
                    'description'=>$row['description'],
                    'images'=>array(),
                );
            }
            array_push($product['images'], $row['url']);
        }
        // 动态
        $answers = BrandService::listSAnswersByTime($id, 0, 20);
        //设计师
        $designers = BrandService::listDesignersByTime($id, 0, 2);
        // 相关话题
        $topics = BrandService::listTopicsByTime($id, 0, 2);
        // 相关名人
        $celebrities = BrandService::listCelebritiesByTime($id, 0, 2);
        // 粉丝
        $fans = BrandService::listFansByTime($id, 0, 2);
        $this->render('view',array(
            'total_page'=>$this->getTotalPage($total, 20),
            'model'=>$this->model,
            'product'=>$product,
            'designers'=>$designers,
            'answers'=>$answers,
            'topics'=>$topics,
            'celebrities'=>$celebrities,
            'fans'=>$fans,
        ));
    }

    /**动态 **/
    public function actionFresh($id, $page = 1, $pageSize = 5){
        $total = BrandService::countAnswers($id);
        // 动态
        $offset = $this->getOffset($page, $pageSize);
        $rows = BrandService::listMAnswersByTime($id, $offset, $pageSize);
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
                'celebrity'=>$row['celebrity_name'],
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

    /**产品 **/
    public function actionProducts($id){
        $rows = BrandService::getProductsByTime($id);
        $products = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $products)) {
                $products[$row['id']] = array(
                    'id'=>$row['id'],
                    'brand_id'=>$row['brand_id'],
                    'name'=>$row['name'],
                    'description'=>$row['description'],
                    'images'=>array(),
                );
            }
            array_push($products[$row['id']]['images'], $row['url']);
        }
        $products = array_values($products);
        $this->render('product',array(
            'products'=>$products,
        ));
    }
    public function actionProduct($id){
        $rows = BrandService::getProduct($id);
        $product = array();
        foreach($rows as $row) {
            if(empty($product)) {
                $product = array(
                    'id'=>$row['id'],
                    'brand_id'=>$row['brand_id'],
                    'name'=>$row['name'],
                    'description'=>$row['description'],
                    'images'=>array(),
                );
            }
            array_push($product['images'], $row['url']);
        }
        echo CJSON::encode($product);
    }

    /**相关设计师 **/
    public function actionDesigner($id){
        //品牌
        $rows = BrandService::listDesignersByTime($id);
        $this->render('designer',array(
            'designers'=>$rows,
        ));
    }

    /**相关名人 **/
    public function actionCelebrity($id, $page = 1, $pageSize = 10){
        $total = BrandService::countCelebrities($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = BrandService::listCelebritiesByTime($id, $offset, $pageSize);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$rows,
            ));
        } else {
            $this->render('celebrity',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'celebrities'=>$rows
            ));
        }
    }

    /**相关话题 */
    public function actionTopic($id, $page = 1, $pageSize = 3){
        $total = CelebrityService::countTopics($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = BrandService::listTopicsByTime($id, $offset, $pageSize);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$rows,
            ));
        } else {
            $this->render('topic',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'topics'=>$rows
            ));
        }
    }

    /**粉丝 **/
    public function actionFan($id, $page = 1, $pageSize = 4){
        $total = CelebrityService::countFans($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = BrandService::listMFansByTime($id, $offset, $pageSize);
        if(isset($_GET['ajax'])) {
            echo CJSON::encode(array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'rows'=>$rows,
            ));
        } else {
            $this->render('fan',array(
                'total_page'=>$this->getTotalPage($total, $pageSize),
                'fans'=>$rows
            ));
        }
    }

    // 关注
    public function actionFollow() {
        $req = Yii::app()->request;
        if($req->isPostRequest && !Yii::app()->user->isGuest) {
            $success = BrandService::follow($req->getPost('id'), Yii::app()->user->id);
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
            $success = BrandService::unfollow($req->getPost('id'), Yii::app()->user->id);
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
        $models = Brand::model()->findAll(array(
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
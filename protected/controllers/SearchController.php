<?php

class SearchController extends Controller
{
    public function actionIndex($word) {
        // 总条数
        $total_rows = SearchService::countAll($word);
        $map = array();
        foreach($total_rows as $row) {
            $map[$row['tag']] = $row['total'];
        }

        // 数据
        $rows = SearchService::findAll($word);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['tag'], $data)) {
                $data[$row['tag']] = array(
                    'total'=>$map[$row['tag']],
                    'data'=>array(),
                );
            }
            array_push($data[$row['tag']]['data'], array(
                'id'=>$row['id'],
                'name'=>$row['name'],
                'img'=>$row['img'],
            ));
        }

        $this->render('index', array(
            'word'=>$word,
            'result'=>$data
        ));
    }

    /** 搜索所有 */
    public function actionAll() {
        $word = Yii::app()->request->getPost('word');
        if(empty($word)) exit(CJSON::encode(array()));

        // 总条数
        $total_rows = SearchService::countAll($word);
        $map = array();
        foreach($total_rows as $row) {
            $map[$row['tag']] = $row['total'];
        }

        // 数据
        $rows = SearchService::findAll($word, 3, 3, 3, 3, 3);
        $data = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['tag'], $data)) {
                $data[$row['tag']] = array(
                    'total'=>$map[$row['tag']],
                    'data'=>array(),
                );
            }
            array_push($data[$row['tag']]['data'], array(
                'id'=>$row['id'],
                'name'=>$row['name'],
                'img'=>$row['img'],
            ));
        }
        exit(CJSON::encode($data));
    }

    public function actionStar($word, $page = 1, $pageSize = 21) {
        $total = SearchService::countStar($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listStars($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('celebrity/view', array('id'=>$rows[$i]['id']));
        }

        $this->render('celebrity', array(
            'word'=>$word,
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'stars'=>$rows,
        ));
    }

    /** 搜索名人 */
    public function actionListStar($word, $page = 1, $pageSize = 21) {
        $total = SearchService::countStar($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listStars($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('celebrity/view', array('id'=>$rows[$i]['id']));
        }

        exit(CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        )));
    }

    /** 品牌 */
    public function actionBrand($word, $page = 1, $pageSize = 21) {
        $total = SearchService::countBrand($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listBrands($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('brand/view', array('id'=>$rows[$i]['id']));
        }

        $this->render('brand', array(
            'word'=>$word,
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'brands'=>$rows,
        ));
    }
    /** 品牌 */
    public function actionListBrand($word, $page = 1, $pageSize = 21) {
        $total = SearchService::countBrand($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listBrands($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('brand/view', array('id'=>$rows[$i]['id']));
        }

        exit(CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        )));
    }

    /** 话题 */
    public function actionTopic($word, $page = 1, $pageSize = 12) {
        $total = SearchService::countTopic($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listTopics($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('topic/view', array('id'=>$rows[$i]['id']));
        }

        $this->render('topic', array(
            'word'=>$word,
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'topics'=>$rows,
        ));
    }

    /** 话题 */
    public function actionListTopic($word, $page = 1, $pageSize = 12) {
        $total = SearchService::countTopic($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listTopics($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('topic/view', array('id'=>$rows[$i]['id']));
        }

        exit(CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        )));
    }

    /** 对比 */
    public function actionCompare($word, $page = 1, $pageSize = 12) {
        $total = SearchService::countCompare($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listCompares($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('compare/view', array('id'=>$rows[$i]['id']));
        }

        $this->render('compare', array(
            'word'=>$word,
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'compares'=>$rows,
        ));
    }

    /** 搜索对比 */
    public function actionListCompare($word, $page = 1, $pageSize = 20) {
        $total = SearchService::countCompare($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listCompares($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('compare/view', array('id'=>$rows[$i]['id']));
        }

        exit(CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        )));
    }

    /** 用户 */
    public function actionUser($word, $page = 1, $pageSize = 24) {
        $total = SearchService::countUser($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listUsers($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('user/view', array('id'=>$rows[$i]['id']));
        }

        $this->render('user', array(
            'word'=>$word,
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'users'=>$rows,
        ));
    }

    /** 用户 */
    public function actionListUser($word, $page = 1, $pageSize = 20) {
        $total = SearchService::countUser($word);

        $offset = $this->getOffset($page, $pageSize);
        $rows = SearchService::listUsers($word, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('user/view', array('id'=>$rows[$i]['id']));
        }

        exit(CJSON::encode(array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        )));
    }
}
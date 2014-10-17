<?php

class SupportController extends Controller
{

    /** 提问 */
    public function actionQuestions($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = SupportService::countQuestionsByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = SupportService::listQuestionsByUserId($id, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('question/view', array('id'=>$rows[$i]['id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        );
        exit(CJSON::encode($rs));
    }

    /** 回答 */
    public function actionAnswers($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = SupportService::countAnswersByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = SupportService::listAnswersByUserId($id, $offset, $pageSize);

        $foo = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $foo)) {
                $foo[$row['id']] = array(
                    'id'=>$row['id'],
                    'href'=>Yii::app()->createUrl('answer/view', array('id'=>$row['id'])),
                    'content'=>$row['content'],
                    'happens'=>$row['happens'],
                    'occurdate'=>$row['occurdate'],
                    'place'=>$row['place'],
                    'total_comments'=>$row['total_comments'],
                    'time'=>$row['ans_time'],
                    'author'=>array(
                        'id'=>$row['user_id'],
                        'head'=>$row['user_head'],
                        'nick'=>$row['user_nick'],
                        'href'=>Yii::app()->createUrl('user/view', array('id'=>$row['user_id'])),
                    ),
                    'celebrities'=>array(),
                );
                // 回答
                if(!array_key_exists($row['celebrity_id'], $foo[$row['id']]['celebrities'])) {
                    $foo[$row['id']]['celebrities'][$row['celebrity_id']] = array(
                        'id'=>$row['celebrity_id'],
                        'name'=>$row['celebrity_name'],
                        'attaches'=>array(),
                    );
                }
                array_push($foo[$row['id']]['celebrities'][$row['celebrity_id']]['attaches'], array(
                    'brand_id'=>$row['brand_id'],
                    'brand_name'=>$row['brand_name'],
                    'clothes_type'=>$row['clothes_type'],
                    'style'=>$row['style'],
                ));
            }
        }

        foreach($foo as &$answer) {
            $answer['celebrities'] = array_values($answer['celebrities']);
        }
        $answers = array_values($foo);

        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$answers,
        );
        exit(CJSON::encode($rs));
    }

    /** 对比 */
    public function actionCompares($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = SupportService::countComparesByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = SupportService::listComparesByUserId($id, $offset, $pageSize);
        $foo = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $foo)) {
                $foo[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'href'=>Yii::app()->createUrl('compare/view', array('id'=>$row['id'])),
                    'total_comment'=>$row['total_comments'],
                    'time'=>$row['createtime'],
                    'author'=>array(
                        'id'=>$row['uid'],
                        'nick'=>$row['nick'],
                        'head'=>$row['head'],
                        'href'=>Yii::app()->createUrl('user/view', array('id'=>$row['uid'])),
                    ),
                    'images'=>array(),
                );
            }
            array_push($foo[$row['id']]['images'], array(
                'url'=>$row['img'],
                'celebrity'=>$row['celebrity'],
                'brand'=>$row['brand'],
                'clothes_type'=>$row['clothes_type'],
                'style'=>$row['style'],
            ));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>array_values($foo),
        );
        exit(CJSON::encode($rs));
    }

    /** 话题 */
    public function actionTopics($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = SupportService::countTopicsByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = SupportService::listTopicsByUserId($id, $offset, $pageSize);
        $foo = array();
        foreach($rows as $row) {
            if(!array_key_exists($row['id'], $foo)) {
                $foo[$row['id']] = array(
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'cover'=>$row['cover'],
                    'follow'=>$row['follow'],
                    'support'=>$row['support'],
                    'total_cele'=>$row['total_cele'],
                    'celebrities'=>array(),
                );
            }
            array_push($foo[$row['id']]['celebrities'], array(
                'id'=>$row['celebrity_id'],
                'head'=>$row['head'],
            ));
        }
        $topics = array_values($foo);
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$topics,
        );
        exit(CJSON::encode($rs));
    }

    /** 单图 */
    public function actionPictures($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = SupportService::countPicturesByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = SupportService::listPicturesByUserId($id, $offset, $pageSize);
        $data = array();
        foreach($rows as $row) {
            array_push($data, array(
                'id'=>$row['id'],
                'url'=>$row['url'],
            ));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$data,
        );
        exit(CJSON::encode($rs));
    }

}
<?php

class SiteController extends Controller
{
    public function actionIndex() {
        // 回答
        $answers = AnswerService::listByTime(0, 60);

        // 话题
        $topics = TopicService::getSInfoByHot(0,5);

        // 趋势
        $trend = array();

        $rows = CelebrityService::getSInfoByTime(0, 10);
        foreach($rows as $row) {
            array_push($trend, array(
                'tag'=>'celebrity',
                'id'=>$row['id'],
                'name_cn'=>$row['name_cn'],
                'name_en'=>$row['name_en'],
                'img'=>$row['head'],
                'total_qa'=>intval($row['total_qa']),
            ));
        }

        $rows = BrandService::getSInfoByTime(0, 10);
        foreach($rows as $row) {
            array_push($trend, array(
                'tag'=>'brand',
                'id'=>$row['id'],
                'name_cn'=>$row['name_cn'],
                'name_en'=>$row['name_en'],
                'img'=>$row['logo'],
                'total_qa'=>intval($row['total_qa']),
            ));
        }

        usort($trend, function($a, $b) {
            return $a['total_qa'] > $b['total_qa'] ? -1 : ($a['total_qa'] == $b['total_qa'] ? (strncmp($a['name_en'],$b['name_en'], min(strlen($a['name_en']), strlen($b['name_en'])))) : 1);
        });

        $this->render('index',array(
            'topics'=>$topics,
            'answers'=>$answers,
            'trend'=>$trend,
        ));
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout = "//layouts/error";
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

}
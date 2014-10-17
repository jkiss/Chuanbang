<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property string $title
 * @property string $summary
 * @property string $content
 * @property string $pubtime
 * @property string $createtime
 * @property string $lastupdate
 * @property integer $publish
 * @property integer $top
 * @property integer $hot
 * @property string $quoted_url
 * @property string $quoted
 * @property integer $clothesType
 * @property string $tags
 */
abstract class XSActiveRecord extends CActiveRecord
{
	protected function afterSave() {
		try {
            if($this->isNewRecord) {
                $this->addIndex();
            } else {
                $this->updateIndex();
            }
        } catch (XSException $e) {
            $error = strval($e);
        }
		parent::afterSave();
	}
	
	protected function beforeDelete() {
		try {
			$this->delIndex();
		} catch (XSException $e) {
            $error = strval($e);
        }
        return parent::beforeDelete();
	}
    public function addIndex() {
    	if(Yii::app()->hasComponent('search')) {
	        $data = $this->indexData();
	        if(is_array($data)) Yii::app()->search->add($data);
    	}
    }
    public function updateIndex() {
    	if(Yii::app()->hasComponent('search')) {
	        $data = $this->indexData();
	        if(is_array($data)) Yii::app()->search->update($data);
    	}
    }
    public function delIndex() {
    	if(Yii::app()->hasComponent('search')) {
	    	$data = $this->indexData();
	        if(is_array($data)) Yii::app()->search->del($data['id']);
    	}
    }
    abstract protected function indexData();
}
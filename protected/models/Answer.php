<?php

class Answer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{answer}}';
	}

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('ques_id,user_id,', 'numerical', 'integerOnly'=>true),
            array('isdel','in','range'=>array('Y','N')),
            array('happens,occurdate,place,content,ups,score,createtime,updatetime,', 'safe'),
		);
	}

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'question' => array(self::BELONGS_TO, 'Question', 'ques_id'),
            'author' => array(self::BELONGS_TO, 'User', 'user_id'),
            'details'=>array(self::HAS_MANY,'AnswerDetail','ans_id'),
            'pictures' => array(self::HAS_MANY, 'AnswerPicture', 'ans_id'),
            'topic'=>array(self::BELONGS_TO, 'Topic', 'topic_id'),
        );
    }

    protected function beforeSave() {
        if($this->isNewRecord) {
            $this->createtime = $this->updatetime = time();
        } else {
            $this->updatetime = time();
        }
        return parent::beforeSave();
    }

}
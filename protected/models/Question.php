<?php

class Question extends CActiveRecord
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
		return '{{question}}';
	}

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content,ups,score,createtime,updatetime', 'safe'),
            array('isdel','in','range'=>array('Y','N')),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'user_id'),
            'followers' => array(self::MANY_MANY, 'User', 'tbl_ques_follow(ques_id, user_id)'),
            'pictures' => array(self::HAS_MANY, 'QuestionPicture', 'ques_id', 'alias'=>'ques_pictures','order'=>'ques_pictures.ups desc,ques_pictures.id asc'),
        );
    }

    public function scopes() {
        return array(
            'available'=>array(
                'condition'=>"isdel='N'",
            ),
            'removed'=>array(
                'condition'=>"isdel='Y'",
            )
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
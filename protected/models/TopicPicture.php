<?php

class TopicPicture extends CActiveRecord
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
		return '{{topic_picture}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('topic_id,', 'numerical', 'integerOnly'=>true),
            array('topic_id','required'),
			array('img,', 'safe'),
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
            'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
		);
	}

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'img'=>Yii::t('core', 'Background Image'),
        );
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('topic_id',$this->topic_id);
        $criteria->order = 'id desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    protected function beforeDelete() {
        AppUtils::deleteImage($this->img);
        return parent::beforeDelete();
    }
}
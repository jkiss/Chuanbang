<?php

class User extends CActiveRecord
{
    const WEIBO = 'WEIBO';
    const QQ = 'QQ';
    const EMAIL = 'EMAIL';
    const RESERVE = 'RESERVE';

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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nick,country,region,city,county,company,', 'length', 'max'=>50),
            array('head,job,', 'length', 'max'=>100),
            array('gender','in','range'=>array('F','M')),
            array('type','in','range'=>array(self::WEIBO, self::QQ, self::EMAIL,self::RESERVE)),
			array('signature,description,createtime,updatetime,', 'safe'),
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
            'followUsers'=>array(self::MANY_MANY, 'User', 'tbl_user_follow(uid, follower_id)'),
            'followings'=>array(self::MANY_MANY, 'User', 'tbl_user_follow(follower_id, uid)'),
            'followStars'=>array(self::MANY_MANY, 'Star', 'tbl_star_follow(user_id, star_id)'),
            'favQuestions'=>array(self::MANY_MANY, 'Question', 'tbl_ques_follow(uid, ques_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'type' => Yii::t('core','Platform'),
			'nick' => Yii::t('core','Nick'),
			'head' => Yii::t('core','Head'),
            'company'=>Yii::t('core','Company'),
			'job' => Yii::t('core','Job'),
			'gender' => Yii::t('core','Gender'),
			'description' => Yii::t('core','Personal Summary'),
            'signature' => Yii::t('core','Personal Signature'),
			'createtime' => Yii::t('core','Create Time'),
			'updatetime' => Yii::t('core','Last Update Time'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
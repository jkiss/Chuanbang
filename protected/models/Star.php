<?php

class Star extends CActiveRecord
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
		return '{{celebrity}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('createtime,lastupdate', 'numerical', 'integerOnly'=>true),
			array('name,name_en,name_cn,', 'length', 'max'=>50),
            array('country,state,city,job,head,cover', 'length', 'max'=>100),
            array('birthday,die','date','format'=>'yyyy-MM-dd'),
            array('alias', 'length', 'max'=>200),
			array('gender', 'length', 'max'=>1),
            array('hot','in','range'=>array('Y','N')),
			array('summary', 'safe'),
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
            'followers' => array(self::MANY_MANY, 'User', 'tbl_star_follow(star_id, user_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => Yii::t('core','Name'),
            'name_en' => Yii::t('core','English Name'),
			'name_cn' => Yii::t('core','Chinese Name'),
            'alias' => Yii::t('core','Other Name'),
			'country' => Yii::t('core','Country'),
            'state' => Yii::t('core','State'),
            'city' => Yii::t('core','City'),
			'job' => Yii::t('core','Job'),
			'head' => Yii::t('core','Head'),
			'gender' => Yii::t('core','Gender'),
			'summary' => Yii::t('core','Star Summary'),
			'createtime' => Yii::t('core','Create Time'),
			'lastupdate' => Yii::t('core','Last Update Time'),
            'cover'=>Yii::t('core', 'Cover'),
            'birthday' => Yii::t('core','Birthday'),
            'die' => Yii::t('core','Date Of Death'),
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
		$criteria->compare('name',$this->name, true);
		$criteria->compare('name_en',$this->name_en, true);
        $criteria->compare('name_cn',$this->name_cn, true);
		$criteria->compare('gender',$this->gender);
        $criteria->compare('hot',$this->hot);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave() {
		if($this->isNewRecord) {
			$this->createtime = $this->lastupdate = time();
			// if(!isset($this->uid)) $this->uid = md5(time());
		} else {
			$this->lastupdate = time();
		}
		return parent::beforeSave();
	}
}
<?php

class Tag extends CActiveRecord
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
		return '{{tag}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hot,hits,followings,createtime,lastupdate', 'numerical', 'integerOnly'=>true),
			array('name,', 'length', 'max'=>20),
            array('refimg', 'length', 'max'=>100),
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
            'followers' => array(self::MANY_MANY, 'User', 'tbl_brand_follow(brand_id, user_id)')
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
			'summary' => Yii::t('core','Tag Summary'),
			'hits' => Yii::t('core','Hits'),
            'hot' => Yii::t('core','Hot'),
			'followings' => Yii::t('core','Followings'),
			'createtime' => Yii::t('core','Create Time'),
			'lastupdate' => Yii::t('core','Last Update Time'),
            'refimg'=>Yii::t('core', 'Tag Icon'),
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
		$criteria->compare('name',$this->name);

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
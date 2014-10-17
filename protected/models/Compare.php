<?php

class Compare extends CActiveRecord
{
    /** 草稿 */
    const DRAFT = 'DRAFT';
    /** 已发布 */
    const PUBLISHED = 'PUBLISHED';

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{compare}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id,', 'numerical', 'integerOnly'=>true),
			array('title,', 'length', 'max'=>100),
            array('state','in','range'=>array(self::DRAFT, self::PUBLISHED)),
            array('createtime,updatetime,','safe'),
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
            'details' => array(self::HAS_MANY, 'CompareDetail', 'compare_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'visits' => Yii::t('core','Visits'),
			'followings' => Yii::t('core','Followings'),
			'createtime' => Yii::t('core','Create Time'),
			'updatetime' => Yii::t('core','Last Update Time'),
            'status'=>Yii::t('core', 'Status'),
            'title'=>Yii::t('core', 'Title'),
            'authorName'=>Yii::t('core', 'Author'),
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
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('author.nick',$this->authorName, true);
        $criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave() {
		if($this->isNewRecord) {
			$this->createtime = $this->updatetime = time();
			$this->state = self::DRAFT;
		} else {
			$this->updatetime = time();
		}
		return parent::beforeSave();
	}

    public $authorName;
}
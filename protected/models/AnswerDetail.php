<?php

class AnswerDetail extends CActiveRecord
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
		return '{{answer_detail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('ans_id,star_id,brand_id,clothes_type','required'),
            array('ans_id,star_id,brand_id,', 'numerical', 'integerOnly'=>true),
            array('clothes_type,style,,', 'safe'),
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
            'answer' => array(self::BELONGS_TO, 'Answer', 'ans_id'),
            'celebrity'=>array(self::BELONGS_TO,'Star','star_id'),
            'brand'=>array(self::BELONGS_TO,'Brand','brand_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => 'ID',
            'ans_id' => Yii::t('core', 'Answer'),
            'style' => Yii::t('core', 'Style'),
            'star_id'=>Yii::t('core','Star'),
            'brand_id'=>Yii::t('core','Brand'),
            'clothes_type'=>Yii::t('core','Clothes Type'),
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
        $criteria->compare('ans_id',$this->ans_id);
        $criteria->compare('star_id',$this->star_id);
        $criteria->compare('brand_id',$this->brand_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
}
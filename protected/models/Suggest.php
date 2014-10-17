<?php

class Suggest extends CActiveRecord
{
    static $TYPES = array(
        'BRAND'=>'品牌',
        'CELEBRITY'=>'名人',
        'TOPIC'=>'话题',
        'COMPARE'=>'对比',
    );
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
		return '{{suggest}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,', 'numerical', 'integerOnly'=>true),
            array('type,title,url','required'),
            array('type','in', 'range'=>array_keys(self::$TYPES)),
			array('title', 'length', 'max'=>100),
            array('url', 'length', 'max'=>255),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => Yii::t('core','Type'),
            'title'=>Yii::t('core', 'Title'),
            'url'=>Yii::t('core', 'Url'),
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
        $criteria->compare('type',$this->type);
        $criteria->compare('title',$this->title, true);
        $criteria->order = 'id asc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}
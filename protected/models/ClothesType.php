<?php

class ClothesType extends CActiveRecord
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
		return '{{clothes_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,sno,', 'numerical', 'integerOnly'=>true),
            array('name,sno','required'),
			array('name', 'length', 'max'=>20),
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
			'sno' => Yii::t('core','Order Number'),
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
        $criteria->compare('sno',$this->sno);
        $criteria->order = 'sno, id asc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function init()
    {
        $sql = 'select max(sno) sno from tbl_clothes_type';
        $rows = DbUtils::query($sql);
        $this->sno = $rows[0]['sno'] + 1;
    }

}
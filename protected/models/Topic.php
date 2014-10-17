<?php

class Topic extends CActiveRecord
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
		return '{{topic}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            array('title,','required'),
            array('isdel','in','range'=>array('N','Y')),
			array('occurdate,place,description,cover,ups,score,createtime', 'safe'),
		);
	}
}
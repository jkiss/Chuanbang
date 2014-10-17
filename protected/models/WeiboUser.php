<?php
/** 微博用户 */
class WeiboUser extends CActiveRecord
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
		return '{{weibo_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            array('user_id,tid,name,access_token,remind_in,expires_in,screen_name,gender,head','safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'tid'=> Yii::t('core','Weibo Id'),
            'name'=>Yii::t('core','Real Name'),
            'screen_name'=>Yii::t('core','Nick'),
			'gender' => Yii::t('core','Gender'),
            'head'=>Yii::t('core','Head'),
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
        $criteria->compare('screen_name',$this->screen_name);
		$criteria->compare('gender',$this->gender);
        $criteria->order = 'id desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}
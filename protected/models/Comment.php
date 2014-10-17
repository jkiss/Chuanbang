<?php

class Comment extends CActiveRecord
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
		return '{{answer_comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('answer_id,pid,user_id,createtime,praises,updatetime', 'numerical', 'integerOnly'=>true),
			array('content,', 'safe'),
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
            'answer' => array(self::BELONGS_TO, 'Answer', 'answer_id'),
            'author' => array(self::BELONGS_TO, 'User', 'user_id'),
            'reply'=>array(self::BELONGS_TO,'Comment','pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'author' => Yii::t('core', 'Author'),
            'content' => Yii::t('core', 'Comment Content'),
			'praises' => Yii::t('core','Praises'),
            'createtime' => Yii::t('core', 'Comment Time'),
            'updatetime' => Yii::t('core', 'Update Time'),
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
        $criteria->compare('answer_id',$this->answer_id);
        $criteria->compare('user_id',$this->user_id);
		$criteria->compare('content',$this->content, true);
        $criteria->compare('pid',$this->pid);

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

    protected function afterSave() {
        if($this->isNewRecord) {
            $answer = Answer::model()->findByPk($this->answer_id);
            $answer->replies++;
            $answer->save();
        }
    }

}
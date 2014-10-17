<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-10
 * Time: 下午7:44
 */

class CompareDetail extends CActiveRecord {

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{compare_pic}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('compare_id,ques_id,ques_pic_id,ans_detail_id','safe'),
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
            'compare' => array(self::BELONGS_TO, 'Compare', 'compare_id'),
            'question' => array(self::BELONGS_TO, 'Question', 'ques_id'),
            'questionPicture' => array(self::BELONGS_TO, 'QuestionPicture', 'ques_pic_id'),
            'answerDetail' => array(self::BELONGS_TO, 'AnswerDetail', 'ans_detail_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
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
        $criteria->compare('compare_id',$this->compare_id);
        $criteria->compare('ques_id',$this->ques_id);
        $criteria->compare('ans_detail_id',$this->ans_detail_id);
        $criteria->compare('ques_pic_id',$this->ques_pic_id);
        $criteria->order = 'id DESC';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
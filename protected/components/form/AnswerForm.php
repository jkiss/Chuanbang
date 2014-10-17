<?php
/** 回答 */
class AnswerForm extends CFormModel
{
    /*用户id**/
    public $user_id;
    /** 提问id */
	public $ques_id;
    /** 事件 */
    public $happens;
    /** 日期 */
    public $occurdate;
    /** 地点 */
    public $place;
    /** 内容 */
    public $content;

    public function rules()
    {
        return array(
            array('user_id,ques_id,happens,occurdate,place,content,', 'safe')
        );
    }
}

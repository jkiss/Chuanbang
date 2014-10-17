<?php
/** 回答 */
class AnswerDetailForm extends CFormModel
{
    /** 名人 */
	public $celebrity_more;
    public $celebrity;
    /** 品牌 */
    public $brand_more;
    public $brand;
    /** 类型 */
	public $clothes_type;
    /** 款式 */
    public $style;

    public function rules()
    {
        return array(
            array('celebrity_more,celebrity,brand_more,brand,clothes_type,style,', 'safe')
        );
    }
}

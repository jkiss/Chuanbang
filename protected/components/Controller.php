<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout="//layouts/web";

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    public function filterCheckLogin($filterChain) {
        if(Yii::app()->user->isGuest) {
            Yii::app()->user->loginRequired();
            return false;
        }
        $filterChain->run();
    }
	
	/**
	 * 
	 * ret: 返回值，0-成功，非0-失败
	 * msg:错误信息
	 * errcode:返回错误码
	 */
	protected function response($ret,$msg,$data=array(),$errcode=-1) {
        $r = array("ret"=>$ret,"msg"=>$msg,"errcode"=>$errcode);
        if(is_array($data) && !empty($data)) $r['data'] = $data;
        else if(is_object($data) && $data != null) $r['data'] = $data;
        return $r;
	}

    /** 获取分页下标 */
    protected function getOffset($page, $per_page) {
        return (max($page, 1) - 1) * $per_page;
    }

    /** 获取总页数 */
    protected function getTotalPage($total, $per_page) {
        return $total % $per_page == 0 ? $total / $per_page : floor($total / $per_page) + 1;
    }
}
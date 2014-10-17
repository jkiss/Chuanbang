<?php
class FollowController extends Controller
{
    /** 名人 */
    public function actionCelebrities($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = FollowService::countCelebritiesByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = FollowService::listCelebritiesByUserId($id, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('celebrity/view', array('id'=>$rows[$i]['id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        );
        exit(CJSON::encode($rs));
    }

    /** 品牌 */
    public function actionBrands($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = FollowService::countBrandsByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = FollowService::listBrandsByUserId($id, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('brand/view', array('id'=>$rows[$i]['id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        );
        exit(CJSON::encode($rs));
    }

    /** 用户 */
    public function actionUsers($id=0, $page = 1, $pageSize = 20) {
        $id = isset($id) && intval($id) > 0 ? intval($id) : (Yii::app()->user->isGuest ? 0 : Yii::app()->user->id);
        $total = FollowService::countIdolsByUserId($id);
        $offset = $this->getOffset($page, $pageSize);
        $rows = FollowService::listIdolsByUserId($id, $offset, $pageSize);
        for($i = 0, $len = count($rows); $i < $len; $i++) {
            $rows[$i]['href'] = Yii::app()->createUrl('user/view', array('id'=>$rows[$i]['id']));
        }
        $rs = array(
            'total_page'=>$this->getTotalPage($total, $pageSize),
            'data'=>$rows,
        );
        exit(CJSON::encode($rs));
    }

    // 用户
    public function actionUser() {
        $req = Yii::app()->request;
        if(Yii::app()->user->isGuest) {
            if($req->isPostRequest) {
                $success = UserService::follow($req->getPost('id'), Yii::app()->user->id);
            } else if($req->isDeleteRequest) {
                $success = UserService::unfollow($req->getDelete('id'), Yii::app()->user->id);
            } else {
                $success = false;
            }
            if($success) {
                exit(CJSON::encode($this->response(0,'success')));
            } else {
                exit(CJSON::encode($this->response(500, 'fail')));
            }
        }
        exit(CJSON::encode($this->response(400, 'fail')));
    }

}
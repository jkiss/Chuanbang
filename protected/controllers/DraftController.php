<?php

class DraftController extends Controller
{
    // 草稿箱
    public function actionIndex() {
        $user = AppUtils::currentUser();
        $uid = $user->id;
        $models = Draft::model()->findAll(array(
            'condition'=>'uid=:uid',
            'params'=>array(':uid'=>$uid),
            'order'=>'gid desc, id asc',
        ));

        $drafts = array();
        foreach($models as $model) {
            if(!array_key_exists($model->gid, $drafts)) $drafts[$model->gid] = array();
            array_push($drafts[$model->gid], $model);
        }

        $this->render('/explore/draft_index', array(
            'drafts'=>$drafts,
        ));
    }

    // 草稿-上传图片
    public function actionCreate() {
        $req = Yii::app()->request;
        if($req->isPostRequest) {
            $user = AppUtils::currentUser();
            $uid = $user->id;
            $gid = time();
            $sql = 'insert into tbl_draft(uid,gid,img) values';
            if(isset($_POST['images']) && count($_POST['images']) > 0) {
                $parts = array();
                foreach($_POST['images'] as $img) {
                    $parts[] = "($uid,$gid,'".$img."')";
                }
                $sql .= implode(',', $parts);
                $rows = DbUtils::execute($sql);
                if($rows > 0) {
                    $this->redirect(array('view','gid'=>$gid));
                }
            }
        }
        $this->render('/explore/draft_create');
    }

    // 草稿-查看
    public function actionView($gid) {
        $user = AppUtils::currentUser();
        $uid = $user->id;
        $models = Draft::model()->findAllByAttributes(array(
            'uid'=>$uid,
            'gid'=>$gid,
        ));
        $this->render('/explore/draft_view', array(
            'gid'=>$gid,
            'models'=>$models,
        ));
    }

    // 发布
    public function actionPublish($gid) {
        $user = AppUtils::currentUser();
        $uid = $user->id;
        // todo begin trans
        $question = new Question();
        $question->user_id = $uid;
        if($question->save()) {
            $qid = $question->id;
            $sql = "INSERT INTO tbl_ques_picture(ques_id,img) SELECT $qid,img FROM tbl_draft where gid=$gid and uid=$uid";
            $rows = DbUtils::execute($sql);
            if($rows > 0) {
                $rows = Draft::model()->deleteAllByAttributes(array('gid'=>$gid, 'uid'=>$uid));
                if($rows > 0) {
                    // todo end trans
                    $this->redirect(array('/question/pending'));
                }
            }
        }
        // todo rollback
        $this->redirect(array('/draft/view', array('gid'=>$gid)));
    }

    // 删除草稿
    public function actionDelete() {
        $req = Yii::app()->request;
        if($req->isPostRequest) {
            $user = AppUtils::currentUser();
            $uid = $user->id;
            $model = Draft::model()->findByAttributes(array(
                'id'=>$req->getPost('id'),
                'uid'=>$uid
            ));
            AppUtils::deleteImage($model->img);
            if($model->delete()) {
                echo CJSON::encode($this->response(0, 'success'));
            } else {
                echo CJSON::encode($this->response(500, 'fail'));
            }
        } else {
            echo CJSON::encode($this->response(400, 'fail'));
        }
    }

    public function loadModel($id)
    {
        $model=Draft::model()->with(array('author'))->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: gracier11
 * Date: 14-3-13
 * Time: 下午11:26
 */

class QuestionService {

    /** 统计总数 */
    static function countPending() {
        $sql = "SELECT COUNT(1) total FROM tbl_question q WHERE q.isdel='N' AND NOT EXISTS (SELECT 1 FROM tbl_answer WHERE ques_id=q.id AND isdel='N')";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }

    /**  待答-按时间  */
    static function listPendingByTime($offset=0, $rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT q.id,q.createtime,qp.img,u.id user_id,u.head user_head,u.nick user_nick,
                IF((SELECT id FROM tbl_ques_follow WHERE ques_id=q.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_ques_support WHERE ques_id=q.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_ques_picture WHERE ques_id=q.id) total_imgs,
                (SELECT COUNT(1) FROM tbl_ques_comment WHERE ques_id=q.id) total_comments,
                IF((SELECT id FROM tbl_ques_img_support WHERE ques_img_id=qp.ques_img_id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') img_support
                FROM (
                    SELECT id,user_id,createtime FROM tbl_question q WHERE q.isdel='N' AND NOT EXISTS (SELECT 1 FROM tbl_answer WHERE ques_id=q.id AND isdel='N') ORDER BY q.id DESC LIMIT $offset, $rows
                ) q
                LEFT JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) qp ON qp.ques_id=q.id
                JOIN tbl_user u ON u.id=q.user_id
                ORDER BY q.id DESC";
        return DbUtils::query($sql, array(':user_id'=>$user_id));
    }

    /** 待答-按热度 */
    static function listPendingByHot($offset=0, $rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT q.id,q.createtime,qp.img,u.id user_id,u.head user_head,u.nick user_nick,
                IF((SELECT id FROM tbl_ques_follow WHERE ques_id=q.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_ques_support WHERE ques_id=q.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_ques_picture WHERE ques_id=q.id) total_imgs,
                (SELECT COUNT(1) FROM tbl_ques_comment WHERE ques_id=q.id) total_comments,
                IF((SELECT id FROM tbl_ques_img_support WHERE ques_img_id=qp.ques_img_id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') img_support
                FROM (
                    SELECT id,user_id,createtime,score FROM tbl_question q WHERE q.isdel='N' AND NOT EXISTS (SELECT 1 FROM tbl_answer WHERE ques_id=q.id AND isdel='N') ORDER BY q.score DESC,q.id DESC LIMIT $offset, $rows
                ) q
                LEFT JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) qp ON qp.ques_id=q.id
                JOIN tbl_user u ON u.id=q.user_id
                ORDER BY q.score DESC,q.id DESC";
        return DbUtils::query($sql, array(':user_id'=>$user_id));
    }

    /** 添加图片到草稿箱 */
    static function addDraft($user_id,$url) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "SELECT COUNT(1) total_imgs FROM tbl_ques_draft WHERE user_id=:user_id";
            $rows = DbUtils::query($sql,array(':user_id'=>$user_id));
            if(!empty($rows)) {
                //最多9张
                if(intval($rows[0]['total_imgs']) >=9) return false;
            }
            $sql = "INSERT INTO tbl_ques_draft(user_id,img) VALUES(:user_id,:img)";
            $rows = DbUtils::execute($sql, array(
                ':user_id'=>$user_id,
                ':img'=>$url,
            ));
            if($rows == 0) throw new CDbException('server error');
            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }

        return $rows == 0 ? false : true;
    }

    /** 删除草稿图片 */
    static function delDraft($user_id,$url) {
        $sql = "DELETE FROM tbl_ques_draft WHERE user_id=:user_id AND img=:img";
        $rows = DbUtils::execute($sql, array(
            ':user_id'=>$user_id,
            ':img'=>$url,
        ));
        return $rows == 0 ? false : true;
    }

    /** 提交草稿 */
    static function applyDraft($user_id, $content) {
        $now = time();
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "INSERT INTO tbl_question(user_id,content,createtime,updatetime) values($user_id, :content,:now,:now)";
            $rows = DbUtils::execute($sql, array(
                ':user_id'=>$user_id,
                ':content'=>$content,
                ':now'=>$now,
            ));
            if($rows == 0) throw new CDbException('server error');
            $question_id = Yii::app()->db->getLastInsertID();

            $sql = "INSERT INTO tbl_ques_picture(ques_id,img) SELECT :id,img FROM tbl_ques_draft WHERE user_id=:user_id ORDER BY id ASC";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$question_id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException('server error');

            $sql = "DELETE FROM tbl_ques_draft WHERE user_id=:user_id";
            $rows = DbUtils::execute($sql, array(':user_id'=>$user_id));
            if($rows == 0) throw new CDbException('server error');
            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 草稿箱 */
    static function getDraft($user_id) {
        if(!isset($user_id)) $user_id = 0;
        $sql = "SELECT id,img FROM tbl_ques_draft WHERE user_id=:user_id ORDER BY id ASC";
        return DbUtils::query($sql, array(':user_id'=>$user_id));
    }

    /**
     * 提问详情
     * @param $id 提问id
     * @param $user_id 访客id
     */
    static function getById($id, $user_id=null) {
        if(!isset($user_id)) $user_id = 0;
        $sql = "SELECT q.id,q.content,q.createtime,u.id user_id,u.head user_head,u.nick user_nick,
                qp.id ques_img_id,qp.img,(SELECT COUNT(DISTINCT user_id) FROM tbl_ques_img_support WHERE ques_img_id=qp.id) img_supports,
                IF((SELECT id FROM tbl_ques_img_support WHERE ques_img_id=qp.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') img_support,
                IF((SELECT id FROM tbl_ques_follow WHERE ques_id=q.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_ques_support WHERE ques_id=q.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_ques_picture WHERE ques_id=q.id) total_imgs,
                (SELECT COUNT(1) FROM tbl_ques_comment WHERE ques_id=q.id) total_comments,
                (SELECT COUNT(1) FROM tbl_answer WHERE ques_id=q.id AND isdel='N') total_answers
                FROM tbl_question q JOIN tbl_ques_picture qp ON qp.ques_id=q.id AND q.id=:id
                JOIN tbl_user u ON u.id=q.user_id
                ORDER BY qp.id ASC";
        return DbUtils::query($sql, array(':user_id'=>$user_id, ':id'=>$id));
    }

    /** 按热度 */
    static function listAnswersByHot($ques_id, $user_id=null) {
        if(!isset($user_id)) $user_id = 0;
        $sql = "SELECT a.ques_id,a.id ans_id,a.content,a.happens,a.occurdate,a.place,a.createtime,
                IF((SELECT id FROM tbl_ans_follow af WHERE ans_id=:id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_ans_support ast WHERE ans_id=:id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_answer_comment WHERE ans_id=a.id) total_comments,
                (SELECT COUNT(1) FROM tbl_ans_follow WHERE ans_id=a.id) total_follows,
                (SELECT COUNT(1) FROM tbl_ans_support WHERE ans_id=a.id) total_supports,
                a.user_id,u.head user_head,u.nick user_nick,c.id celebrity_id,c.name celebrity_name,
                b.id brand_id,b.name brand_name,ad.clothes_type,ad.style
                FROM tbl_answer a
                JOIN tbl_answer_detail ad ON ad.ans_id=a.id AND a.ques_id=:id AND a.isdel='N'
                JOIN tbl_user u ON u.id=a.user_id
                JOIN tbl_brand  b ON ad.brand_id=b.id
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                ORDER BY a.score DESC,ad.id ASC";
        return DbUtils::query($sql, array(
            ':id'=>$ques_id,
            ':user_id'=>$user_id,
        ));
    }

    /** 添加评论  */
    static function saveComment($id, $content, $user_id) {
        $sql = "INSERT INTO tbl_ques_comment(ques_id,user_id,content,createtime) VALUES(:id,:user_id,:content,:createtime)";
        $rows = DbUtils::execute($sql, array(
            ':id'=>$id,
            ':user_id'=>$user_id,
            ':content'=>$content,
            ':createtime'=>time(),
        ));
        return $rows>0 ? true : false;
    }

    /** 评论-按时间 */
    static function countComments($id) {
        $sql = "SELECT count(1) total FROM tbl_ques_comment c WHERE c.ques_id=:id";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listCommentsByTime($id, $offset=0, $rows=20) {
        $sql = "SELECT c.id,c.content,c.createtime,u.id user_id,u.head user_head,u.nick user_nick
                FROM tbl_ques_comment c,tbl_user u WHERE c.user_id=u.id AND c.ques_id=:id ORDER BY c.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(
            ':id'=>$id,
        ));
    }

    /** 收藏 */
    static function follow($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已收藏
            $sql = "SELECT 1 FROM tbl_ques_follow WHERE ques_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            //关注
            $sql = "INSERT INTO tbl_ques_follow(ques_id,user_id) VALUES(:id,:user_id)";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);
            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 取消收藏 */
    static function unfollow($id,$user_id) {
        $sql = "DELETE FROM tbl_ques_follow WHERE ques_id=:id AND user_id=:user_id";
        $rows = DbUtils::execute($sql, array(
            ':id'=>$id,
            ':user_id'=>$user_id,
        ));
        return $rows>0 ? true : false;
    }

    /** 赞 */
    static function support($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已赞
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_ques_support WHERE ques_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_question WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'Y') throw new CDbException(Yii::t('code', '403009'), 403009);
            $create_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 赞
            $sql = "INSERT INTO tbl_ques_support(ques_id,user_id) VALUES(:id,:user_id)";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($create_time,$ups+1);
            $sql = "UPDATE tbl_question SET score=:score,ups=ups+1 WHERE id=:id";
            $rows = DbUtils::execute($sql, array(':id'=>$id,':score'=>$score));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 取消赞 */
    static function unsupport($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已赞
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_ques_support WHERE ques_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_question WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'N') throw new CDbException(Yii::t('code', '403009'), 403009);
            $create_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 取消赞
            $sql = "DELETE FROM tbl_ques_support WHERE ques_id=:id AND user_id=:user_id";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($create_time,$ups-1);
            $sql = "UPDATE tbl_question SET score=:score,ups=ups-1 WHERE id=:id";
            $rows = DbUtils::execute($sql, array(':id'=>$id,':score'=>$score));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 赞图片 */
    static function supportImg($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已赞过
            $sql = "SELECT 1 FROM tbl_ques_img_support WHERE ques_img_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 记录赞
            $sql = "INSERT INTO tbl_ques_img_support(ques_img_id,user_id) VALUES(:id,:user_id)";
            $rows = DbUtils::execute($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新赞数
            $sql = "UPDATE tbl_ques_picture SET ups=ups+1 WHERE id=:id";
            $rows = DbUtils::execute($sql,array(':id'=>$id));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 取消赞图片 */
    static function unsupportImg($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已赞过
            $sql = "SELECT 1 FROM tbl_ques_img_support WHERE ques_img_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 取消赞
            $sql = "DELETE FROM tbl_ques_img_support WHERE ques_img_id=:id and user_id=:user_id";
            $rows = DbUtils::execute($sql,array(':id'=>$id,':user_id'=>$user_id));
            if($rows == 0) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 更新赞数
            $sql = "UPDATE tbl_ques_picture SET ups=ups-1 WHERE id=:id";
            $rows = DbUtils::execute($sql,array(':id'=>$id));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }
}
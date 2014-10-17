<?php
class CompareService {

    /** 统计总数 */
    static function count() {
        $sql = "SELECT COUNT(1) total FROM tbl_compare WHERE isdel='N'";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }

    /** 按时间 */
    static function listByTime($offset=0, $rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT c.id,c.title,cd.img,c.createtime,u.id user_id,u.nick user_nick,u.head user_head,
                IF((SELECT id FROM tbl_compare_follow WHERE compare_id=c.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_compare_support WHERE compare_id=c.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_compare_follow WHERE compare_id=c.id) total_follows,
                (SELECT COUNT(1) FROM tbl_compare_support WHERE compare_id=c.id) total_ups,
                (SELECT COUNT(1) FROM tbl_compare_detail WHERE compare_id=c.id) total_imgs,
                (SELECT COUNT(1) FROM tbl_compare_comment WHERE compare_id=c.id) total_comments,
                ce.name celebrity,b.name brand,ad.clothes_type,ad.style
                FROM (
                    SELECT id,user_id,title,score,createtime FROM tbl_compare
                    WHERE state='PUBLISHED' AND isdel='N' ORDER BY id DESC LIMIT $offset, $rows
                ) c
                JOIN tbl_user u ON c.user_id=u.id
                JOIN tbl_compare_detail cd ON cd.compare_id=c.id
                LEFT JOIN (
                    SELECT id,ans_id,celebrity_id,brand_id,style,clothes_type FROM tbl_answer_detail t
                    WHERE 0=(SELECT COUNT(1) FROM tbl_answer_detail WHERE t.ans_id=ans_id AND t.id>id)
                ) ad ON ad.ans_id=cd.ans_id
                LEFT JOIN tbl_brand b ON b.id=ad.brand_id
                LEFT JOIN tbl_celebrity ce ON ce.id=ad.celebrity_id
                ORDER BY c.id DESC";
        return DbUtils::query($sql, array(
            'user_id'=>$user_id,
        ));
    }

    /** 按热度 */
    static function listByHot($offset=0, $rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT c.id,c.title,cd.img,c.createtime,u.id user_id,u.nick user_nick,u.head user_head,
                IF((SELECT id FROM tbl_compare_follow WHERE compare_id=c.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_compare_support WHERE compare_id=c.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_compare_follow WHERE compare_id=c.id) total_follows,
                (SELECT COUNT(1) FROM tbl_compare_support WHERE compare_id=c.id) total_ups,
                (SELECT COUNT(1) FROM tbl_compare_detail WHERE compare_id=c.id) total_imgs,
                (SELECT COUNT(1) FROM tbl_compare_comment WHERE compare_id=c.id) total_comments,
                ce.name celebrity,b.name brand,ad.clothes_type,ad.style
                FROM (
                    SELECT id,user_id,title,score,createtime FROM tbl_compare
                    WHERE state='PUBLISHED' AND isdel='N' ORDER BY score DESC,id DESC LIMIT $offset, $rows
                ) c
                JOIN tbl_user u ON c.user_id=u.id
                JOIN tbl_compare_detail cd ON cd.compare_id=c.id
                LEFT JOIN (
                    SELECT id,ans_id,celebrity_id,brand_id,style,clothes_type FROM tbl_answer_detail t
                    WHERE 0=(SELECT COUNT(1) FROM tbl_answer_detail WHERE t.ans_id=ans_id AND t.id>id)
                ) ad ON ad.ans_id=cd.ans_id
                LEFT JOIN tbl_brand b ON b.id=ad.brand_id
                LEFT JOIN tbl_celebrity ce ON ce.id=ad.celebrity_id
                ORDER BY c.score DESC,c.id DESC";
        return DbUtils::query($sql, array(
            'user_id'=>$user_id,
        ));
    }

    /** 按时间 */
    static function getSInfoByPos($id, $rows=10) {
        $sql = "SELECT c.id,c.title,cd.img,c.createtime,
                (SELECT COUNT(1) FROM tbl_compare_comment WHERE compare_id=c.id) total_comments
                FROM tbl_compare c
                JOIN tbl_compare_detail cd ON cd.compare_id=c.id AND c.state='PUBLISHED' AND c.isdel='N' AND c.id>:id
                ORDER BY id ASC LIMIT $rows";
        return DbUtils::query($sql, array(
            ':id'=>$id
        ));
    }

    /** 对比信息 */
    static function getById($id) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT c.id,c.title,cd.img,c.createtime,u.id user_id,u.nick user_nick,u.head user_head,
                IF((SELECT id FROM tbl_compare_follow WHERE compare_id=c.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_compare_support WHERE compare_id=c.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_compare_follow WHERE compare_id=c.id) total_follows,
                (SELECT COUNT(1) FROM tbl_compare_support WHERE compare_id=c.id) total_ups,
                (SELECT COUNT(1) FROM tbl_compare_detail WHERE compare_id=c.id) total_imgs,
                (SELECT COUNT(1) FROM tbl_compare_comment WHERE compare_id=c.id) total_comments,
                ce.name celebrity,b.name brand,ad.clothes_type,ad.style
                FROM tbl_compare c
                JOIN tbl_user u ON c.user_id=u.id AND c.id=:id AND c.isdel='N'
                JOIN tbl_compare_detail cd ON cd.compare_id=c.id
                LEFT JOIN (
                    SELECT id,ans_id,celebrity_id,brand_id,style,clothes_type FROM tbl_answer_detail t
                    WHERE 0=(SELECT COUNT(1) FROM tbl_answer_detail WHERE t.ans_id=ans_id AND t.id>id)
                ) ad ON ad.ans_id=cd.ans_id
                LEFT JOIN tbl_brand b ON b.id=ad.brand_id
                LEFT JOIN tbl_celebrity ce ON ce.id=ad.celebrity_id
                ORDER BY c.id DESC";
        return DbUtils::query($sql, array(
            ':id'=>$id,
            ':user_id'=>$user_id,
        ));
    }

    /** 添加评论  */
    static function saveComment($id, $content, $user_id) {
        $sql = "INSERT INTO tbl_compare_comment(compare_id,user_id,content,createtime) VALUES(:id,:user_id,:content,:createtime)";
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
        $sql = "SELECT count(1) total FROM tbl_compare_comment c WHERE c.compare_id=:id";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listCommentsByTime($id, $offset=0, $rows=20) {
        $sql = "SELECT c.id,c.content,c.createtime,u.id user_id,u.head user_head,u.nick user_nick
                FROM tbl_compare_comment c,tbl_user u WHERE c.user_id=u.id AND c.compare_id=:id ORDER BY c .id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(
            ':id'=>$id,
        ));
    }

    /** 草稿箱 */
    static public function listDraft() {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT cd.img FROM tbl_compare c JOIN tbl_compare_detail cd
                ON c.user_id=:user_id AND c.id=cd.compare_id AND c.state=:state
                ORDER BY cd.id ASC";
        return DbUtils::query($sql,array(':user_id'=>$user_id,':state'=>Compare::DRAFT));
    }

    /** 添加对比图片 */
    static function addImg($url, $ans_id=null) {
        if(Yii::app()->user->isGuest) return false;
        if(strpos($url, '?')) $url =  substr($url,0,strrpos($url,'?'));
        if(isset($ans_id) && intval($ans_id) == 0) $ans_id = null;
        $user_id = Yii::app()->user->id;
        $now = time();
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "SELECT id,(SELECT COUNT(1) FROM tbl_compare_detail WHERE compare_id=c.id) total_imgs FROM tbl_compare c WHERE user_id=:user_id AND state=:state";
            $rows = DbUtils::query($sql,array(':user_id'=>$user_id,':state'=>Compare::DRAFT));
            if(empty($rows)) {
                $sql = "INSERT INTO tbl_compare(user_id,state,createtime,updatetime) values(:user_id,:state,:now, :now)";
                $rows = DbUtils::execute($sql,array(
                    ':user_id'=>$user_id,
                    ':state'=>Compare::DRAFT,
                    ':now'=>$now,
                ));
                if($rows == 0) throw new CDbException('server error');
                $id = Yii::app()->db->getLastInsertID();
            } else {
                $id = $rows[0]['id'];
                //最多9张
                if(intval($rows[0]['total_imgs']) >=9) return false;
            }
            // 保存详情
            $sql = "INSERT INTO tbl_compare_detail(compare_id,img,ans_id) VALUES(:id, :img,:ans_id)";
            $rows = DbUtils::execute($sql,array(
                ':id'=>$id,
                ':img'=>$url,
                ':ans_id'=>$ans_id,
            ));
            if($rows == 0) throw new CDbException('server error');
            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 删除对比图片 */
    static function delImg($url) {
        if(Yii::app()->user->isGuest) return false;
        $user_id = Yii::app()->user->id;
        $sql = "DELETE cd FROM tbl_compare_detail cd,tbl_compare c
                    WHERE cd.compare_id=c.id AND c.state=:state AND c.user_id=:user_id AND cd.img=:img";
        $rows = DbUtils::execute($sql,array(
            ':state'=>Compare::DRAFT,
            ':user_id'=>$user_id,
            ':img'=>$url,
        ));
        return $rows == 0 ? false : true;
    }

    /** 应用对比 */
    static public function apply($title=null) {
        if(!isset($title) || empty($title)) return false;
        if(Yii::app()->user->isGuest) return false;
        $user_id = Yii::app()->user->id;
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "SELECT id,(SELECT COUNT(1) FROM tbl_compare_detail WHERE compare_id=c.id) total_imgs
                    FROM tbl_compare c WHERE user_id=:user_id AND state=:state";
            $rows = DbUtils::query($sql,array(':user_id'=>$user_id,':state'=>Compare::DRAFT));
            if(empty($rows)) throw new CDbException('server error');
            if(intval($rows[0]['total_imgs']) < 2) throw new CDbException('server error');
            $compare_id = $rows[0]['id'];

            $sql = "UPDATE tbl_compare SET title=:title,state=:state,updatetime=:now WHERE user_id=$user_id AND state=:state_draft";
            $rows = DbUtils::execute($sql, array(
                ':title'=>$title,
                ':state'=>Compare::PUBLISHED,
                ':now'=>time(),
                ':state_draft'=>Compare::DRAFT
            ));
            if($rows == 0) throw new CDbException('server error');
            $trans->commit();
            return $compare_id;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }

    /** 关注 */
    static function follow($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已收藏
            $sql = "SELECT 1 FROM tbl_compare_follow WHERE compare_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 收藏
            $sql = "INSERT INTO tbl_compare_follow(compare_id,user_id) VALUES(:id,:user_id)";
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

    /** 取消关注 */
    static function unfollow($id,$user_id) {
        $sql = "DELETE FROM tbl_compare_follow WHERE compare_id=:id AND user_id=:user_id";
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
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_compare_support WHERE compare_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_compare WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'Y') throw new CDbException(Yii::t('code', '403009'), 403009);
            $create_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 赞
            $sql = "INSERT INTO tbl_compare_support(compare_id,user_id) VALUES(:id,:user_id)";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($create_time,$ups+1);
            $sql = "UPDATE tbl_compare SET score=:score,ups=ups+1 WHERE id=:id";
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
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_compare_support WHERE compare_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_compare WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'N') throw new CDbException(Yii::t('code', '403009'), 403009);
            $create_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 取消赞
            $sql = "DELETE FROM tbl_compare_support WHERE compare_id=:id AND user_id=:user_id";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($create_time,$ups-1);
            $sql = "UPDATE tbl_compare SET score=:score,ups=ups-1 WHERE id=:id";
            $rows = DbUtils::execute($sql, array(':id'=>$id,':score'=>$score));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            $trans->commit();
            return true;
        } catch(CException $e) {
            $trans->rollBack();
            return false;
        }
    }


}
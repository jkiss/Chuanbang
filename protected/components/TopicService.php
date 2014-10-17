<?php
class TopicService {

    /** 按时间 */
    static function getSInfoByTime($offset=0, $rows=20) {
        $sql = "SELECT id,title,cover,(SELECT COUNT(1) FROM tbl_topic_follow WHERE topic_id=tp.id) total_fans FROM tbl_topic tp WHERE isdel='N' ORDER BY id DESC LIMIT $offset, $rows";
        return DbUtils::query($sql);
    }

    /** 按热度 */
    static function getSInfoByHot($offset=0, $rows=20) {
        $sql = "SELECT id,title,cover,(SELECT COUNT(1) FROM tbl_topic_follow WHERE topic_id=tp.id) total_fans FROM tbl_topic tp WHERE isdel='N' ORDER BY score DESC,id DESC LIMIT $offset, $rows";
        return DbUtils::query($sql);
    }

    /** 按时间 */
    static function getSInfoByPos($id, $rows=10) {
        $sql = "SELECT id,title,cover,(SELECT COUNT(1) FROM tbl_topic_follow WHERE topic_id=tp.id) total_fans
                FROM tbl_topic tp WHERE isdel='N' AND tp.id>:id ORDER BY id ASC LIMIT $rows";
        return DbUtils::query($sql, array(
            ':id'=>$id
        ));
    }

    /** 按热度 */
    static function listByHot($offset=0, $rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT tp.id,tp.title,tp.cover,
                IF((SELECT id FROM tbl_topic_follow WHERE topic_id=tp.id AND user_id=$user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_topic_support WHERE topic_id=tp.id AND user_id=$user_id LIMIT 1) IS NULL,'N','Y') support,
                tc.celebrity_id,tt.total total_cele,c.head FROM (
                    SELECT id,title,cover,score FROM tbl_topic tp WHERE isdel='N' AND EXISTS (SELECT id FROM tbl_ans_topic WHERE topic_id=tp.id AND isdel='N')
                    ORDER BY score DESC,id DESC LIMIT $offset,$rows
                ) tp
                LEFT JOIN (
                    -- 话题相关的最新6个名人
                    SELECT rownum,tc.topic_id,tc.celebrity_id FROM (
                        SELECT (@rownum:=@rownum+1) rownum,t.topic_id,t.celebrity_id FROM (
                            -- 话题关联的所有名人
                            SELECT DISTINCT ap.topic_id,ad.celebrity_id FROM tbl_answer a
                            JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND a.isdel='N'
                            JOIN tbl_ans_topic ap ON ap.ans_id=a.id
                            ORDER BY ad.id DESC
                        ) t,(SELECT @rownum:=0) rn
                    ) tc WHERE 6>(
                        SELECT COUNT(1) FROM (
                            SELECT (@rownum:=@rownum+1) rownum,t.topic_id,t.celebrity_id FROM (
                            -- 话题关联的所有名人
                                SELECT DISTINCT ap.topic_id,ad.celebrity_id FROM tbl_answer a
                                JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND a.isdel='N'
                                JOIN tbl_ans_topic ap ON ap.ans_id=a.id
                                ORDER BY ad.id DESC
                            ) t,(SELECT @rownum:=0) rn
                        ) tt WHERE topic_id=tc.topic_id AND rownum>tc.rownum
                    )
                ) tc ON tc.topic_id=tp.id
                LEFT JOIN (
                    -- 话题相关名人统计
                    SELECT topic_id,COUNT(celebrity_id) total FROM (
                        SELECT DISTINCT ap.topic_id,ad.celebrity_id FROM tbl_answer a
                        JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND a.isdel='N'
                        JOIN tbl_ans_topic ap ON ap.ans_id=a.id
                    ) t GROUP BY topic_id
                ) tt ON tp.id=tt.topic_id
                LEFT JOIN tbl_celebrity c ON tc.celebrity_id=c.id
                ORDER BY tp.score DESC,tp.id DESC";
        return DbUtils::query($sql, array(
            ':user_id'=>$user_id,
        ));
    }

    /** 资料 */
    static function getById($id) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT id,title,cover,description,
                IF((SELECT id FROM tbl_topic_follow WHERE topic_id=tp.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_topic_support WHERE topic_id=tp.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_topic_follow WHERE topic_id=tp.id) total_fans,
                (SELECT COUNT(1) FROM tbl_topic_support WHERE topic_id=tp.id) total_ups
                FROM tbl_topic tp WHERE isdel='N' AND id=:id";
        return DbUtils::query($sql, array(
            ':user_id'=>$user_id,
            ':id'=>$id,
        ));
    }

    /** 相关回答 */
    static function countAnswers($id) {
        $sql = "SELECT COUNT(DISTINCT a.id) total FROM tbl_answer a,tbl_ans_topic atp WHERE atp.topic_id=:id AND atp.ans_id=a.id AND a.isdel='N'";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listAnswersByTime($id,$offset=0,$rows=20) {
        $sql = "SELECT a.id ans_id,a.createtime ans_time,ad.id ans_detail_id,c.id celebrity_id,c.name celebrity_name,b.id brand_id,b.name brand_name,ad.style,ad.clothes_type,
                u.id user_id,u.nick user_nick,u.head user_head,ques.img
                FROM (
                    SELECT id,ques_id,createtime,user_id FROM tbl_answer a
                    WHERE isdel='N' AND EXISTS (SELECT 1 FROM tbl_ans_topic WHERE topic_id=:id AND ans_id=a.id)
                    ORDER BY id DESC LIMIT $offset,$rows
                ) a
                JOIN (
                    SELECT id,ans_id,celebrity_id,brand_id,style,clothes_type FROM tbl_answer_detail t
                    WHERE 0=(SELECT COUNT(1) FROM tbl_answer_detail WHERE t.ans_id=ans_id AND t.id>id)
                ) ad ON a.id=ad.ans_id
                JOIN tbl_user u ON a.user_id=u.id
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                JOIN tbl_brand b ON ad.brand_id=b.id
                JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,qp.id ques_img_id,img,qp.ups FROM tbl_ques_picture qp,tbl_question q
                    WHERE qp.id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1) AND qp.ques_id=q.id AND q.isdel='N'
                ) ques ON ques.ques_id=a.ques_id
                ORDER BY a.id DESC,ad.id ASC";
        return DbUtils::query($sql, array(
            ':id'=>$id,
        ));
    }

    /** 关注 */
    static function follow($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已关注
            $sql = "SELECT 1 FROM tbl_topic_follow WHERE topic_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 收藏
            $sql = "INSERT INTO tbl_topic_follow(topic_id,user_id) VALUES(:id,:user_id)";
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
        $sql = "DELETE FROM tbl_topic_follow WHERE topic_id=:id AND user_id=:user_id";
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
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_topic_support WHERE topic_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_topic WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'Y') throw new CDbException(Yii::t('code', '403009'), 403009);
            $topic_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 赞
            $sql = "INSERT INTO tbl_topic_support(topic_id,user_id) VALUES(:id,:user_id)";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($topic_time,$ups+1);
            $sql = "UPDATE tbl_topic SET score=:score,ups=ups+1 WHERE id=:id";
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
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_topic_support WHERE topic_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_topic WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'N') throw new CDbException(Yii::t('code', '403009'), 403009);
            $topic_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 取消赞
            $sql = "DELETE FROM tbl_topic_support WHERE topic_id=:id AND user_id=:user_id";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($topic_time,$ups-1);
            $sql = "UPDATE tbl_topic SET score=:score,ups=ups-1 WHERE id=:id";
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
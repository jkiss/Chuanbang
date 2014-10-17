<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午9:49
 */

class FavService {

    /** 提问 */
    static function countQuestionsByUserId($user_id) {
        $sql = "SELECT COUNT(q.id) total FROM tbl_question q JOIN tbl_ques_follow qf ON q.id=qf.ques_id AND qf.user_id=:user_id AND q.isdel='N'";
        $rows = DbUtils::query($sql,array(':user_id'=>$user_id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listQuestionsByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT q.id,qp.img,q.createtime ques_time,u.id user_id,u.nick user_nick,u.head user_head,
                (SELECT IF(COUNT(id) IS NULL, 0, COUNT(id)) FROM tbl_answer WHERE ques_id=q.id AND isdel='N') total_answers,
                (SELECT IF(COUNT(id) IS NULL, 0, COUNT(id)) FROM tbl_ques_comment WHERE ques_id=q.id) total_comments
                FROM (
                    SELECT q.id,q.createtime,q.user_id
                    FROM tbl_question q JOIN tbl_ques_follow qf ON q.id=qf.ques_id AND qf.user_id=:user_id AND q.isdel='N'
                    ORDER BY qf.id DESC limit $offset,$rows
                ) q
                JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) qp ON qp.ques_id=q.id
                JOIN tbl_user u ON q.user_id=u.id";
        return DbUtils::query($sql,array(':user_id'=>$user_id));
    }

    /** 回答 */
    static function countAnswersByUserId($user_id) {
        $sql = "SELECT COUNT(a.id) total FROM tbl_answer a JOIN tbl_ans_follow af ON a.isdel='N' AND a.id=af.ans_id AND af.user_id=:user_id";
        $rows = DbUtils::query($sql,array(':user_id'=>$user_id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listAnswersByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT a.id,a.createtime ans_time,a.happens,a.occurdate,a.place,a.content,
                (SELECT COUNT(1) FROM tbl_answer_comment WHERE ans_id=a.id) total_comments,
                IF(ha.id IS NULL, 'N', 'Y') hot,u.id user_id,u.nick user_nick,u.head user_head,ad.id ans_detail_id,
                b.id brand_id,b.name brand_name,c.id celebrity_id,c.name celebrity_name,ad.clothes_type,ad.style
                FROM (
                    SELECT a.id,a.createtime,a.happens,a.content,a.occurdate,a.place,a.user_id
                    FROM tbl_answer a JOIN tbl_ans_follow af ON af.user_id=:user_id AND af.ans_id=a.id AND a.isdel='N'
                    ORDER BY af.id DESC LIMIT $offset,$rows
                ) a
                JOIN tbl_user u ON a.user_id=u.id
                JOIN tbl_answer_detail ad ON ad.ans_id=a.id
                JOIN tbl_brand b ON ad.brand_id=b.id
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                LEFT JOIN (
                    -- 最热答案
                    SELECT id FROM tbl_answer a
                    WHERE isdel='N' AND id=(SELECT id FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' ORDER BY score DESC,id DESC LIMIT 1)
                ) ha ON a.id=ha.id";
        return DbUtils::query($sql,array(
            ':user_id'=>$user_id,
        ));
    }

    /** 对比 */
    static function countComparesByUserId($user_id) {
        $sql = "SELECT COUNT(c.id) total FROM tbl_compare c JOIN tbl_compare_follow cf ON c.id=cf.compare_id AND cf.user_id=:user_id AND c.isdel='N'";
        $rows = DbUtils::query($sql,array(':user_id'=>$user_id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listComparesByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT c.id,c.title,c.createtime,u.id uid,u.nick,u.head,cd.id compare_detail_id,cd.img,
                (SELECT COUNT(1) FROM tbl_compare_detail WHERE compare_id=c.id) total_imgs,
                (SELECT IF(COUNT(id) IS NULL, 0, COUNT(id)) FROM tbl_compare_comment WHERE compare_id=c.id) total_comments,
                ce.name celebrity,b.name brand,ad.clothes_type,ad.style
                FROM (
                    SELECT c.id,c.title,c.createtime,c.user_id
                    FROM tbl_compare c JOIN tbl_compare_follow cf ON cf.user_id=:user_id AND c.id=cf.compare_id AND c.isdel='N'
                    ORDER BY cf.id DESC LIMIT $offset,$rows
                ) c
                JOIN tbl_user u ON c.user_id=u.id
                JOIN tbl_compare_detail cd ON cd.compare_id=c.id
                LEFT JOIN (
                    SELECT id,ans_id,celebrity_id,brand_id,style,clothes_type FROM tbl_answer_detail t
                    WHERE 0=(SELECT COUNT(1) FROM tbl_answer_detail WHERE t.ans_id=ans_id AND t.id>id)
                ) ad ON ad.ans_id=cd.ans_id
                LEFT JOIN tbl_brand b ON b.id=ad.brand_id
                LEFT JOIN tbl_celebrity ce ON ce.id=ad.celebrity_id";
        return DbUtils::query($sql,array(':user_id'=>$user_id));
    }

    /** 话题 */
    static function countTopicsByUserId($user_id) {
        $sql = "SELECT COUNT(t.id) total FROM tbl_topic t JOIN tbl_topic_follow tf ON t.id=tf.topic_id AND tf.user_id=:user_id AND t.isdel='N'";
        $rows = DbUtils::query($sql,array(':user_id'=>$user_id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listTopicsByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT tp.id,tp.title,tp.cover,
                IF((SELECT id FROM tbl_topic_follow WHERE topic_id=tp.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_topic_support WHERE topic_id=tp.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                tc.celebrity_id,tt.total total_cele,c.head FROM (
                    SELECT tp.id,tp.title,tp.cover FROM tbl_topic tp JOIN tbl_topic_follow tf ON tp.id=tf.topic_id AND tf.user_id=:user_id AND tp.isdel='N'
                    ORDER BY tf.id DESC LIMIT $offset,$rows
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
                LEFT JOIN tbl_celebrity c ON tc.celebrity_id=c.id";
        return DbUtils::query($sql, array(':user_id'=>$user_id));
    }
}
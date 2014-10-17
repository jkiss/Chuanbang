<?php
/**
 * Created by PhpStorm.
 * User: gracier11
 * Date: 14-3-13
 * Time: 下午11:26
 */

class BrandService {

    /** 按时间 */
    static function getSInfoByTime($offset=0, $rows=20) {
        $sql = "SELECT id,name_cn,name_en,logo,(
                    SELECT COUNT(DISTINCT ans_id) FROM (
                        SELECT a.id ans_id,ad.brand_id FROM (
                            -- 最热答案
                            SELECT id FROM tbl_answer a WHERE isdel='N' AND 1>(SELECT COUNT(id) FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' AND score<a.score)
                        ) a JOIN tbl_answer_detail ad ON a.id=ad.ans_id
                    ) t WHERE brand_id=b.id
                ) total_qa
                FROM (
                    SELECT DISTINCT b.id,b.name_cn,b.name_en,b.name `name`,b.logo FROM tbl_brand b
                    JOIN tbl_answer_detail ad ON ad.brand_id=b.id AND b.isdel='N'
                    ORDER BY ad.id DESC LIMIT $offset, $rows
                ) b";
        return DbUtils::query($sql);
    }

    /** 按热度 */
    static function getSInfoByHot($offset=0, $rows=20) {
        $sql = "SELECT id,`name`,name_cn,name_en,logo,(
                    SELECT COUNT(DISTINCT ans_id) FROM (
                        SELECT a.id ans_id,ad.brand_id FROM (
                            -- 最热答案
                            SELECT id FROM tbl_answer a WHERE isdel='N' AND 1>(SELECT COUNT(id) FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' AND score<a.score)
                        ) a JOIN tbl_answer_detail ad ON a.id=ad.ans_id
                    ) t WHERE brand_id=b.id
                ) total_qa FROM tbl_brand b WHERE isdel='N' ORDER BY b.score DESC,b.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql);
    }

    /** 按热度 */
    static function listByHot($offset=0, $rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT b.id,b.name,b.name_en,b.name_cn,b.logo,ad.ans_id,ad.img,ad.ans_time,u.id user_id,u.nick user_nick,u.head user_head,c.id celebrity_id,c.name celebrity_name,(
                    -- 统计相关回答
                    SELECT COUNT(DISTINCT ans_id) FROM (
                        SELECT a.id ans_id,ad.brand_id FROM (
                            -- 最热答案
                            SELECT id FROM tbl_answer a WHERE isdel='N' AND 1>(SELECT COUNT(id) FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' AND score<a.score)
                        ) a JOIN tbl_answer_detail ad ON a.id=ad.ans_id
                    ) t WHERE brand_id=b.id
                ) total_qa,
                (SELECT COUNT(1) FROM tbl_brand_follow WHERE brand_id=b.id) total_fans,
                IF((SELECT 1 FROM tbl_brand_follow WHERE brand_id=b.id AND user_id=:user_id) IS NULL, 'N', 'Y') follow
                FROM (SELECT id,`name`,name_cn,name_en,logo,score FROM tbl_brand WHERE isdel='N' ORDER BY score DESC,id DESC LIMIT $offset,$rows) b
                JOIN (
                    -- 品牌相关的最热两个回答中的关联名人
                    SELECT ques.ques_id,ques.img,a.ans_id,a.ans_user_id,ans_time,a.celebrity_id,a.brand_id FROM (
                        SELECT ques_id,ans_id,ans_user_id,ans_time,celebrity_id,brand_id FROM (
                            SELECT a.ques_id,ad.ans_id,a.user_id ans_user_id,ans_time,ad.celebrity_id,ad.brand_id FROM (
                                SELECT id,ans_id,celebrity_id,brand_id FROM tbl_answer_detail t
                                WHERE 1>(SELECT COUNT(1) FROM tbl_answer_detail WHERE ans_id=t.ans_id AND brand_id=t.brand_id AND t.id>id)
                            ) ad
                            JOIN (
                                -- 最热答案
                                SELECT ques_id,id ans_id,user_id,createtime ans_time FROM tbl_answer a
                                WHERE isdel='N' AND 1>(SELECT COUNT(id) FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' AND score<a.score)
                            ) a ON ad.ans_id=a.ans_id
                        ) t WHERE 2>(SELECT COUNT(1) FROM (
                            SELECT a.ques_id,ad.ans_id,a.user_id ans_user_id,ans_time,ad.celebrity_id,ad.brand_id FROM (
                                SELECT id,ans_id,celebrity_id,brand_id FROM tbl_answer_detail t
                                WHERE 1>(SELECT COUNT(1) FROM tbl_answer_detail WHERE ans_id=t.ans_id AND brand_id=t.brand_id AND t.id>id)
                            ) ad
                            JOIN (
                                -- 最热答案
                                SELECT ques_id,id ans_id,user_id,createtime ans_time FROM tbl_answer a
                                WHERE isdel='N' AND 1>(SELECT COUNT(id) FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' AND score<a.score)
                            ) a ON ad.ans_id=a.ans_id
                        ) f WHERE brand_id=t.brand_id AND t.ans_id<f.ans_id)
                    ) a
                    JOIN (
                        -- 查询每个提问的最热图片
                        SELECT ques_id,qp.id ques_img_id,img,qp.ups FROM tbl_ques_picture qp,tbl_question q
                        WHERE qp.id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1) AND qp.ques_id=q.id AND q.isdel='N'
                    ) ques ON ques.ques_id=a.ques_id
                )  ad ON ad.brand_id=b.id
                JOIN tbl_user u ON ad.ans_user_id=u.id
                JOIN tbl_celebrity c ON c.id=ad.celebrity_id
                ORDER BY b.score DESC,ad.ans_id DESC";
        return DbUtils::query($sql, array(
            ':user_id'=>$user_id,
        ));
    }

    /** 品牌信息 */
    static function getById($id) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT id,name_en,name_cn,logo,description,
                IF((SELECT id FROM tbl_brand_follow WHERE brand_id=b.id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                (
                    SELECT COUNT(DISTINCT ans_id) FROM (
                        SELECT a.id ans_id,ad.brand_id FROM (
                            -- 最热答案
                            SELECT id FROM tbl_answer a WHERE isdel='N' AND id=(SELECT id FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' ORDER BY score DESC,id DESC LIMIT 1)
                        ) a JOIN tbl_answer_detail ad ON a.id=ad.ans_id
                    ) t WHERE brand_id=b.id
                ) total_qa,
                (
                    SELECT COUNT(DISTINCT atp.topic_id) FROM tbl_answer a
                    JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND ad.brand_id=:id AND a.isdel='N'
                    JOIN tbl_ans_topic atp ON atp.ans_id=a.id
                ) total_topics,
                (
                    SELECT COUNT(DISTINCT ad.celebrity_id) FROM tbl_answer a
                    JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND ad.brand_id=:id AND a.isdel='N'
                ) total_celes,
                (SELECT COUNT(DISTINCT user_id) FROM tbl_celebrity_follow WHERE celebrity_id=:id) total_fans
                FROM tbl_brand b WHERE id=:id";
        return DbUtils::query($sql, array(
            ':user_id'=>$user_id,
            ':id'=>$id,
        ));
    }

    /**获取制定品牌的第一个产品 */
    static function getFProductByTime($id) {
        $sql = "SELECT bp.id,bp.brand_id,bp.`name`,bp.description,bpi.url FROM (
                    SELECT id,brand_id,`name`,description FROM tbl_brand_product bp WHERE id=(SELECT id FROM tbl_brand_product WHERE brand_id=:id ORDER BY id DESC LIMIT 1)
                ) bp JOIN tbl_brand_product_img bpi ON bp.id=bpi.product_id ORDER BY bpi.id ASC";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    /**相关产品 */
    static function getProductsByTime($id) {
        $sql = "SELECT bp.id,bp.brand_id,bp.`name`,bp.description,bpi.url FROM tbl_brand_product bp
                JOIN tbl_brand_product_img bpi ON bp.id=bpi.product_id AND bp.brand_id=:id ORDER BY bp.id DESC,bpi.id ASC";
        return DbUtils::query($sql, array(':id'=>$id));
    }
    static function getProduct($id) {
        $sql = "SELECT bp.id,bp.brand_id,bp.`name`,bp.description,bpi.url FROM tbl_brand_product bp
                JOIN tbl_brand_product_img bpi ON bp.id=bpi.product_id AND bp.id=:id ORDER BY bpi.id ASC";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    /** 相关回答 */
    static function listSAnswersByTime($id,$offset=0,$rows=20) {
        $sql = "SELECT a.ques_id,a.ans_id,c.id celebrity_id,c.name celebrity_name,ques.img,ad.clothes_type,ad.style,
                (SELECT COUNT(1) FROM tbl_answer_comment WHERE ans_id=a.ans_id) total_comments
                FROM (
                    SELECT ans_id,id ans_detail_id,celebrity_id,clothes_type,style FROM tbl_answer_detail ad
                    WHERE id=(SELECT id FROM tbl_answer_detail WHERE ans_id=ad.ans_id AND brand_id=:id ORDER BY id ASC LIMIT 1)
                ) ad
                JOIN (
                    -- 最热答案
                    SELECT ques_id,id ans_id,occurdate ans_date,createtime ans_time FROM tbl_answer a
                    WHERE isdel='N' AND id=(SELECT id FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' ORDER BY score DESC,id DESC LIMIT 1)
                ) a ON ad.ans_id=a.ans_id
                JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,qp.id ques_img_id,img,qp.ups FROM tbl_ques_picture qp,tbl_question q
                    WHERE qp.id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1) AND qp.ques_id=q.id AND q.isdel='N'
                ) ques ON ques.ques_id=a.ques_id
                LEFT JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                ORDER BY a.ans_date DESC,a.ans_id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(
            ':id'=>$id,
        ));
    }
    /** 相关回答 */
    static function countAnswers($id) {
        $sql = "SELECT COUNT(DISTINCT ad.ans_id) total FROM tbl_answer a,tbl_answer_detail ad WHERE a.isdel='N' AND a.id=ad.ans_id AND ad.brand_id=:id";
        $rows = DbUtils::query($sql, array(
            ':id'=>$id,
        ));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listMAnswersByTime($id,$offset=0,$rows=20) {
        $sql = "SELECT a.ques_id,ques.img,a.id ans_id,a.happens,a.occurdate,a.place,a.createtime,ad.clothes_type,ad.style,
                c.id celebrity_id,c.name celebrity_name,c.name_en celebrity_name_en,u.id user_id,u.nick user_nick,u.head user_head,
                (SELECT COUNT(id) FROM tbl_ques_picture WHERE ques_id=a.ques_id) total_imgs,
                (SELECT COUNT(id) FROM tbl_answer_comment WHERE ans_id=a.id) total_comments,
                (SELECT COUNT(id) FROM tbl_answer WHERE ques_id=a.ques_id) total_answers
                FROM (
                    SELECT DISTINCT id,ques_id,user_id,happens,occurdate,place,createtime FROM tbl_answer a
                    WHERE a.isdel='N' AND EXISTS (SELECT 1 FROM tbl_answer_detail WHERE ans_id=a.id AND brand_id=:id)
                    ORDER BY a.id DESC LIMIT $offset,$rows
                ) a
                JOIN tbl_answer_detail ad ON ad.ans_id=a.id AND ad.brand_id=:id
                JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,qp.id ques_img_id,img,qp.ups FROM tbl_ques_picture qp,tbl_question q
                    WHERE qp.id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1) AND qp.ques_id=q.id AND q.isdel='N'
                ) ques ON ques.ques_id=a.ques_id
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                JOIN tbl_user u ON a.user_id=u.id ORDER BY a.occurdate DESC,a.id DESC";
        return DbUtils::query($sql, array(
            ':id'=>$id,
        ));
    }

    /**相关话题 **/
    static function countTopics($id) {
        $sql = "SELECT COUNT(DISTINCT tp.id) total FROM tbl_answer a
                JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND ad.brand_id=:id AND a.isdel='N'
                JOIN tbl_ans_topic atp ON atp.ans_id=a.id
                JOIN tbl_topic tp ON atp.topic_id=tp.id AND tp.isdel='N'";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listTopicsByTime($id, $offset=0,$rows=20) {
        $sql = "SELECT DISTINCT tp.id,tp.title,tp.description,tp.cover FROM tbl_answer a
                JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND ad.brand_id=:id AND a.isdel='N'
                JOIN tbl_ans_topic atp ON atp.ans_id=a.id
                JOIN tbl_topic tp ON atp.topic_id=tp.id AND tp.isdel='N'
                ORDER BY a.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    /**相关设计师 **/
    static function listDesignersByTime($id, $offset=0,$rows=20) {
        $sql = "SELECT id,name_en,name_cn,avatar,description FROM tbl_brand_designer bd WHERE brand_id=:id ORDER BY id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    /**相关名人 **/
    static function countCelebrities($id) {
        $sql = "SELECT COUNT(DISTINCT c.id) total FROM tbl_answer a
                JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND ad.brand_id=:id AND a.isdel='N'
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id AND c.isdel='N'";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listCelebritiesByTime($id, $offset=0,$rows=20) {
        $sql = "SELECT id,name_cn,name_en,head,
                (SELECT COUNT(DISTINCT ans_id) FROM tbl_answer_detail WHERE brand_id=t.id) total_qa,
                (SELECT COUNT(DISTINCT user_id) FROM tbl_brand_follow WHERE brand_id=t.id) total_fans
                FROM (
                    SELECT DISTINCT c.id,c.name_cn,c.name_en,c.head FROM tbl_answer a
                    JOIN tbl_answer_detail ad ON a.id=ad.ans_id AND ad.brand_id=:id AND a.isdel='N'
                    JOIN tbl_celebrity c ON ad.celebrity_id=c.id AND c.isdel='N'
                    ORDER BY a.id DESC LIMIT $offset,$rows
                ) t";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    /**粉丝 **/
    static function countFans($id) {
        $sql = "SELECT COUNT(user_id) total FROM tbl_brand_follow WHERE brand=:id";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listFansByTime($id, $offset=0,$rows=20) {
        $sql = "SELECT u.id,u.head,u.nick,(SELECT COUNT(1) FROM tbl_answer WHERE user_id=u.id AND isdel='N') total_qa
                FROM tbl_brand_follow bf
                JOIN tbl_user u ON bf.brand_id=:id AND bf.user_id=u.id
                ORDER BY bf.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    /**粉丝 **/
    static function listMFansByTime($id, $offset=0,$rows=20) {
        $user_id = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
        $sql = "SELECT u.id,u.head,u.nick,u.gender,region,city,u.signature,
                (SELECT COUNT(1) FROM tbl_question WHERE user_id=u.id AND isdel='N') total_q,
                (SELECT COUNT(1) FROM tbl_answer WHERE user_id=u.id AND isdel='N') total_qa,
                IF((SELECT id FROM tbl_user_follow WHERE user_id=u.id AND follower_id=:user_id LIMIT 1) IS NULL,'N','Y') follow
                FROM tbl_brand_follow bf
                JOIN tbl_user u ON bf.brand_id=:id AND bf.user_id=u.id
                ORDER BY bf.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(
            ':id'=>$id,
            ':user_id'=>$user_id
        ));
    }

    /** 关注 */
    static function follow($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已关注
            $sql = "SELECT 1 FROM tbl_brand_follow WHERE brand_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 收藏
            $sql = "INSERT INTO tbl_brand_follow(brand_id,user_id) VALUES(:id,:user_id)";
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
        $sql = "DELETE FROM tbl_brand_follow WHERE brand_id=:id AND user_id=:user_id";
        $rows = DbUtils::execute($sql, array(
            ':id'=>$id,
            ':user_id'=>$user_id,
        ));
        return $rows>0 ? true : false;
    }

    /** 增加热度 */
    static function incrHot($id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已赞
            $sql = "SELECT (SELECT COUNT(DISTINCT ans_id) FROM tbl_answer_detail WHERE brand_id=b.id) ups,createtime FROM tbl_brand b WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
            ));
            if(empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);
            $time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 更新话题热度
            $score = AppUtils::scoreHot($time,$ups+1);
            $sql = "UPDATE tbl_brand SET score=:score WHERE id=:id";
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
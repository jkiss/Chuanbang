<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午10:02
 */

class UserService {

    /** 用户详情 */
    static function getById($id, $follower_id=0) {
        $sql = "SELECT u.id,u.type,u.nick,u.head,u.gender,u.region,u.city,u.signature,u.description,
                (SELECT COUNT(DISTINCT follower_id) FROM tbl_user_follow WHERE user_id=u.id) fans,
                (SELECT COUNT(DISTINCT user_id) FROM tbl_user_follow WHERE follower_id=u.id) idols,
                IF((SELECT id FROM tbl_user_follow WHERE user_id=u.id AND follower_id=:follower_id) IS NULL, 'N', 'Y') follow
                FROM tbl_user u WHERE id=:id";
        return DbUtils::query($sql, array(
            ':id'=>$id,
            ':follower_id'=>$follower_id,
        ));
    }

    /** 登录信息 */
    static function getLoginName($user_id, $type) {
        if($type == User::RESERVE) {
            $login = AdminUser::model()->findByAttributes(array('user_id'=>$user_id));
            return $login->username;
        } else if($type == User::EMAIL) {
            $login = Login::model()->findByAttributes(array('user_id'=>$user_id));
            return $login->email;
        } else if($type == User::WEIBO) {
            $login = WeiboUser::model()->findByAttributes(array('user_id'=>$user_id));
            return $login->name;
        } else if($type == User::QQ) {
            $login = QQUser::model()->findByAttributes(array('user_id'=>$user_id));
            return $login->nickname;
        } else {
            return '';
        }
    }

    /** 用户相关问题 */
    static function countQuestionsByAuthor($user_id) {
        $sql = "SELECT count(1) total FROM tbl_question WHERE user_id=$user_id AND isdel='N'";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listQuestionsByAuthor($user_id, $offset=0, $rows=20) {
        $sql = "SELECT q.id ques_id,q.createtime ques_time,qi.ques_img_id,qi.img_ups,qi.img,ans.celebrity_id,ans.celebrity_name,ans.brand_id,ans.brand_name,
                (SELECT IF(COUNT(id) IS NULL, 0, COUNT(id)) FROM tbl_answer WHERE ques_id=q.id AND isdel='N') total_answers,
                (SELECT IF(COUNT(id) IS NULL, 0, COUNT(id)) FROM tbl_ques_comment WHERE ques_id=q.id) total_comments
                FROM (
                    -- 查询用户关联的所有提问
                    SELECT id,score,createtime FROM tbl_question WHERE user_id=$user_id AND isdel='N' ORDER BY id DESC LIMIT $offset, $rows
                ) q LEFT JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups img_ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) qi ON q.id=qi.ques_id
                LEFT JOIN (
                    -- 查询每个提问最新的回答相关的第一个名人和 品牌
                    SELECT a.ques_id,ad.ans_id,ad.ans_detail_id,s.id celebrity_id,s.name celebrity_name,b.id brand_id,b.name brand_name FROM (
                        -- 查询每个提问的最热答案id
                        SELECT a.ques_id,a.id ans_id FROM tbl_question q,tbl_answer a
                        WHERE a.ques_id=q.id AND a.isdel='N' AND q.isdel='N' AND a.id=(SELECT id FROM tbl_answer WHERE ques_id=a.ques_id ORDER BY score DESC,id DESC LIMIT 1)
                    ) a JOIN (
                        -- 查询每个回答的第一个品牌和名人id
                        SELECT ans_id,id ans_detail_id,brand_id,celebrity_id FROM tbl_answer_detail ad
                        WHERE id=(SELECT id FROM tbl_answer_detail WHERE ans_id=ad.ans_id ORDER BY id ASC LIMIT 1) ORDER BY ans_id DESC,id ASC
                    ) ad ON ad.ans_id=a.ans_id
                    JOIN tbl_celebrity s ON ad.celebrity_id=s.id
                    JOIN tbl_brand b ON ad.brand_id=b.id
                ) ans ON q.id=ans.ques_id
                ORDER BY q.id DESC";
        return DbUtils::query($sql);
    }

    /** 用户相关回答 */
    static function countAnswersByAuthor($user_id) {
        $sql = "SELECT count(1) total FROM tbl_answer WHERE user_id=$user_id AND isdel='N'";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listAnswersByAuthor($user_id, $offset=0, $rows=20) {
        $sql = "SELECT a.ques_id,a.id ans_id,ad.id ans_detail_id,b.id brand_id,b.name brand_name,c.id celebrity_id,c.name celebrity_name,q.img,
                (SELECT IF(COUNT(id),0,COUNT(id)) FROM tbl_answer_comment WHERE ans_id=a.id) total_comments
                FROM (
                    -- 查询用户回答关联的回答
                    SELECT ques_id,id FROM tbl_answer WHERE user_id=$user_id AND isdel='N' ORDER BY id DESC LIMIT $offset, $rows
                ) a
                LEFT JOIN (SELECT * FROM tbl_answer_detail t WHERE id=(SELECT id FROM tbl_answer_detail WHERE ans_id=t.ans_id ORDER BY id ASC LIMIT 1)) ad ON ad.ans_id=a.id
                LEFT JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups img_ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) q ON a.ques_id=q.ques_id
                LEFT JOIN tbl_brand b ON ad.brand_id=b.id
                LEFT JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                ORDER BY a.id DESC";
        return DbUtils::query($sql);
    }

    /** 粉丝 */
    static function countFans($user_id) {
        $sql = "SELECT count(1) total FROM tbl_user u,tbl_user_follow uf WHERE uf.follower_id=u.id AND uf.user_id=$user_id";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listFans($user_id, $offset=0, $rows=20) {
        $sql = "SELECT u.id,u.head,u.nick,(SELECT IF(COUNT(id) IS NULL, 0, COUNT(id)) FROM tbl_answer WHERE user_id=follower_id AND isdel='N') total_answer
                FROM tbl_user u,tbl_user_follow uf WHERE uf.follower_id=u.id AND uf.user_id=$user_id ORDER BY uf.id DESC LIMIT $offset, $rows";
        return DbUtils::query($sql);
    }

    /** 关注 */
    static function follow($id,$follower_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已关注
            $sql = "SELECT 1 FROM tbl_user_follow WHERE user_id=:id AND follower_id=:follower_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':follower_id'=>$follower_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            // 收藏
            $sql = "INSERT INTO tbl_user_follow(user_id,follower_id) VALUES(:id,:follower_id)";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':follower_id'=>$follower_id,
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
    static function unfollow($id,$follower_id) {
        $sql = "DELETE FROM tbl_user_follow WHERE user_id=:id AND follower_id=:follower_id";
        $rows = DbUtils::execute($sql, array(
            ':id'=>$id,
            ':follower_id'=>$follower_id,
        ));
        return $rows>0 ? true : false;
    }

}
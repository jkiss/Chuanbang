<?php
/**
 * Created by PhpStorm.
 * User: gracier11
 * Date: 14-3-13
 * Time: 下午11:26
 */

class AnswerService {

    /** 总条数 */
    static function count($ques_id=0) {
        $sql = "SELECT COUNT(1) total FROM tbl_answer WHERE isdel='N' ";
        if(isset($ques_id) && intval($ques_id) > 0) $sql .= " AND ques_id=$ques_id";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }

    /** 按时间 */
    static function listByTime($offset=0, $rows=20, $ques_id=0) {
        $cond = isset($ques_id) && intval($ques_id) > 0 ? " AND ques_id=$ques_id " : "";
        $sql = "SELECT a.ques_id,a.id ans_id,a.createtime ans_time,ad.id ans_detail_id,
                b.id brand_id,b.name_en brand_name,c.id celebrity_id,c.name celebrity_name,ques.img,u.id user_id,u.nick user_nick,u.head user_head
                FROM (
                    SELECT ques_id,id,user_id,createtime FROM tbl_answer WHERE isdel='N' $cond ORDER BY id DESC LIMIT $offset,$rows
                ) a
                JOIN tbl_user u ON a.user_id=u.id
                JOIN (SELECT * FROM tbl_answer_detail t WHERE id=(SELECT id FROM tbl_answer_detail WHERE ans_id=t.ans_id ORDER BY id ASC LIMIT 1)) ad ON ad.ans_id=a.id
                JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) ques ON ques.ques_id=a.ques_id
                JOIN tbl_brand b ON ad.brand_id=b.id
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                ORDER BY a.id DESC,ad.id ASC";
        return DbUtils::query($sql);
    }

    /** 按热度 */
    static function listByHot($offset=0, $rows=20, $ques_id=0) {
        $cond = isset($ques_id) && intval($ques_id) > 0 ? " AND ques_id=$ques_id " : "";
        $sql = "SELECT a.ques_id,a.id ans_id,ad.id ans_detail_id,b.id brand_id,b.name brand_name,c.id celebrity_id,c.name celebrity_name,ques.img
                FROM (
                    SELECT ques_id,id,score FROM tbl_answer WHERE isdel='N' $cond ORDER BY score DESC,id DESC LIMIT $offset,$rows
                ) a
                LEFT JOIN (SELECT * FROM tbl_answer_detail t WHERE id=(SELECT id FROM tbl_answer_detail WHERE ans_id=t.ans_id ORDER BY id ASC LIMIT 1)) ad ON ad.ans_id=a.id
                LEFT JOIN (
                    -- 查询每个提问的最热图片
                    SELECT ques_id,id ques_img_id,img,ups FROM tbl_ques_picture qp
                    WHERE id=(SELECT id FROM tbl_ques_picture WHERE ques_id=qp.ques_id ORDER BY ups DESC,id ASC LIMIT 1)
                ) ques ON ques.ques_id=a.ques_id
                LEFT JOIN tbl_brand b ON ad.brand_id=b.id
                LEFT JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                ORDER BY a.score DESC,a.id DESC,ad.id ASC";
        return DbUtils::query($sql);
    }

    /**
     * 回答详情
     * @param $id 回答id
     * @param $uid 访客id
     */
    static function queryById($id, $user_id=null) {
        if(!isset($user_id)) $user_id = 0;
        $sql = "SELECT a.ques_id,a.id ans_id,a.content,a.happens,a.occurdate,a.place,a.createtime,
                IF((SELECT IF(MAX(score) IS NULL,0,MAX(score)) FROM tbl_answer aw WHERE ques_id=a.ques_id)=a.score AND a.score IS NOT NULL,'Y','N') hot,
                IF((SELECT id FROM tbl_ans_follow af WHERE ans_id=:id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') follow,
                IF((SELECT id FROM tbl_ans_support ast WHERE ans_id=:id AND user_id=:user_id LIMIT 1) IS NULL,'N','Y') support,
                (SELECT COUNT(1) FROM tbl_answer_comment WHERE ans_id=a.id) total_comments,
                (SELECT COUNT(1) FROM tbl_ans_follow WHERE ans_id=a.id) total_follows,
                (SELECT COUNT(1) FROM tbl_ans_support WHERE ans_id=a.id) total_supports,
                a.user_id,u.head user_head,u.nick user_nick,
                c.id celebrity_id,c.name celebrity_name,c.name_cn celebrity_name_cn,c.name_en celebrity_name_en,c.head celebrity_head,
                b.id brand_id,b.name brand_name,b.name_cn brand_name_cn,b.name_en brand_name_en,b.logo brand_logo,(SELECT COUNT(1) FROM tbl_brand_follow WHERE brand_id=b.id) brand_follows,
                ad.clothes_type,ad.style
                FROM tbl_answer a
                JOIN tbl_answer_detail ad ON ad.ans_id=a.id AND a.id=:id AND a.isdel='N'
                JOIN tbl_user u ON u.id=a.user_id
                JOIN tbl_brand  b ON ad.brand_id=b.id
                JOIN tbl_celebrity c ON ad.celebrity_id=c.id
                ORDER BY ad.id ASC";
        return DbUtils::query($sql, array(
            ':id'=>$id,
            ':user_id'=>$user_id,
        ));
    }

    /** 相关图片 */
    static function listPictures($answer_id) {
        $sql = "SELECT img FROM tbl_ans_picture WHERE ans_id=$answer_id ORDER BY id ASC";
        return DbUtils::query($sql);
    }

    /** 先关话题 */
    static function getTopics($id) {
        $sql = "SELECT tp.id,tp.title,tp.cover FROM tbl_topic tp,tbl_ans_topic atp WHERE atp.ans_id=:id AND atp.topic_id=tp.id ORDER BY atp.id DESC";
        return DbUtils::query($sql, array(':id'=>$id));
    }

    // 保存
    static function save($form_answer, $form_details, $images=null) {
        $answer = new Answer();
        $answer->attributes = $form_answer->attributes;

        // 相关名人
        $celes = array();
        // 相关品牌
        $brands = array();

        $trans = Yii::app()->db->beginTransaction();
        try {
            // 保存
            $sql = "INSERT INTO tbl_answer(ques_id,user_id,happens,occurdate,place,content,createtime,updatetime) values(:ques_id,:user_id,:happens,:occurdate,:place,:content,:now,:now)";
            $rows = DbUtils::execute($sql,array(
                ':ques_id'=>$answer->ques_id,
                ':user_id'=>$answer->user_id,
                ':happens'=>$answer->happens,
                ':occurdate'=>$answer->occurdate,
                ':place'=>$answer->place,
                ':content'=>$answer->content,
                ':now'=>time(),
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);
            $ans_id = Yii::app()->db->getLastInsertID();

            // 回答详情
            $answerDetails = array();
            foreach($form_details as $fd) {
                $answerDetail = new AnswerDetail();
                $answerDetail->ans_id = $ans_id;
                // 名人
                if(isset($fd->celebrity_more) && !empty($fd->celebrity_more)) {
                    list($id, $name) = explode(',',$fd->celebrity_more,2);
                    if($name ==$fd->celebrity && intval($id) > 0) $answerDetail->celebrity_id = intval($id);
                }
                if(!isset($answerDetail->celebrity_id)) {
                    if(empty($fd->celebrity)) throw new CDbException(Yii::t('code', '500000'), 500000);
                    $sql = "INSERT INTO tbl_celebrity(`name`,name_cn,name_en,createtime,updatetime) VALUES(:name,:name,:name,:now,:now)";
                    $rows = DbUtils::execute($sql,array(':name'=>$fd->celebrity,':now'=>time()));
                    if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);
                    $answerDetail->celebrity_id = Yii::app()->db->getLastInsertID();
                }

                // 品牌
                if(isset($fd->brand_more) && !empty($fd->brand_more)) {
                    list($id, $name) = explode(',',$fd->brand_more,2);
                    if($name ==$fd->brand && intval($id) > 0) $answerDetail->brand_id = intval($id);
                }
                if(!isset($answerDetail->brand_id)) {
                    if(empty($fd->brand)) throw new CDbException(Yii::t('code', '500000'), 500000);
                    $sql = "INSERT INTO tbl_brand(`name`,name_cn,name_en,createtime,updatetime) VALUES(:name,:name,:name,:now,:now)";
                    $rows = DbUtils::execute($sql,array(':name'=>$fd->brand,':now'=>time()));
                    if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);
                    $answerDetail->brand_id = Yii::app()->db->getLastInsertID();
                }

                $answerDetail->clothes_type = $fd->clothes_type;
                $answerDetail->style = $fd->style;
                array_push($answerDetails, $answerDetail);
            }

            // 保存回答详情
            $sql = "INSERT INTO tbl_answer_detail(ans_id,celebrity_id,brand_id,clothes_type,style) VALUES";
            $parts = array();
            foreach($answerDetails as $detail) {
                $parts[] = "($ans_id,".$detail->celebrity_id.",".$detail->brand_id.",'".$detail->clothes_type."','".$detail->style."')";
                if(!in_array($detail->celebrity_id, $celes)) array_push($celes, $detail->celebrity_id);
                if(!in_array($detail->brand_id, $brands)) array_push($brands, $detail->brand_id);
            }
            $sql .= implode(',', $parts);
            $rows = DbUtils::execute($sql);
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            if(isset($images) && !empty($images)) {
                // 保存回答图片
                $sql = "INSERT INTO tbl_ans_picture(ans_id,img) VALUES";
                $parts = array();
                foreach($images as $img) {
                    $parts[] = "($ans_id,'".$img."')";
                }
                $sql .= implode(',', $parts);
                $rows = DbUtils::execute($sql);
                if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);
            }

            $trans->commit();

            // 更新名人+品牌热度
            foreach($celes as $cele) CelebrityService::incrHot($cele);
            foreach($brands as $brand) BrandService::incrHot($brand);

            return $ans_id;
        } catch(CException $e) {
            $trans->rollBack();
            return 0;
        }
    }

    /** 添加评论  */
    static function saveComment($id, $content, $user_id) {
        $sql = "INSERT INTO tbl_answer_comment(ans_id,user_id,content,createtime) VALUES(:id,:user_id,:content,:createtime)";
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
        $sql = "SELECT count(1) total FROM tbl_answer_comment c WHERE c.ans_id=:id";
        $rows = DbUtils::query($sql, array(':id'=>$id));
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listCommentsByTime($id, $offset=0, $rows=20) {
        $sql = "SELECT c.id,c.content,c.createtime,u.id user_id,u.head user_head,u.nick user_nick
                FROM tbl_answer_comment c,tbl_user u WHERE c.user_id=u.id AND c.ans_id=:id ORDER BY c.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql, array(
            ':id'=>$id,
        ));
    }

    /** 收藏 */
    static function follow($id,$user_id) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            // 是否已收藏
            $sql = "SELECT 1 FROM tbl_ans_follow WHERE ans_id=:id AND user_id=:user_id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(!empty($rows)) throw new CDbException(Yii::t('code', '403009'), 403009);

            //关注
            $sql = "INSERT INTO tbl_ans_follow(ans_id,user_id) VALUES(:id,:user_id)";
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
        $sql = "DELETE FROM tbl_ans_follow WHERE ans_id=:id AND user_id=:user_id";
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
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_ans_support WHERE ans_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_answer WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'Y') throw new CDbException(Yii::t('code', '403009'), 403009);
            $create_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 赞
            $sql = "INSERT INTO tbl_ans_support(ans_id,user_id) VALUES(:id,:user_id)";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新答案热度
            $score = AppUtils::scoreHot($create_time,$ups+1);
            $sql = "UPDATE tbl_answer SET score=:score,ups=ups+1 WHERE id=:id";
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
            $sql = "SELECT ups,createtime,IF((SELECT id FROM tbl_ans_support WHERE ans_id=:id AND user_id=:user_id) IS NULL,'N','Y') support FROM tbl_answer WHERE id=:id";
            $rows = DbUtils::query($sql,array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if(empty($rows) || $rows[0]['support'] == 'N') throw new CDbException(Yii::t('code', '403009'), 403009);
            $create_time = $rows[0]['createtime'];
            $ups = intval($rows[0]['ups']);

            // 取消赞
            $sql = "DELETE FROM tbl_ans_support WHERE ans_id=:id AND user_id=:user_id";
            $rows = DbUtils::execute($sql, array(
                ':id'=>$id,
                ':user_id'=>$user_id,
            ));
            if($rows == 0) throw new CDbException(Yii::t('code', '500000'), 500000);

            // 更新话题热度
            $score = AppUtils::scoreHot($create_time,$ups-1);
            $sql = "UPDATE tbl_answer SET score=:score,ups=ups-1 WHERE id=:id";
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
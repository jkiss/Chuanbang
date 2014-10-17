<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 * Time: 上午9:49
 */

class FollowService {

    /** 名人 */
    static function countCelebritiesByUserId($user_id) {
        $sql = "SELECT COUNT(c.id) total FROM tbl_celebrity c JOIN tbl_celebrity_follow cf ON c.id=cf.celebrity_id AND cf.user_id=$user_id";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listCelebritiesByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT c.id,c.name_cn,c.name_en,c.head,
                (SELECT IF(COUNT(id) IS NULL,0,COUNT(id)) FROM tbl_celebrity_follow WHERE celebrity_id=c.id) total_fans,
                (SELECT IF(COUNT(ans_id) IS NULL,0,COUNT(ans_id)) FROM (
                    SELECT DISTINCT a.id ans_id,celebrity_id FROM (
                        -- 最热答案
                        SELECT id FROM tbl_answer a
                        WHERE isdel='N' AND id=(SELECT id FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' ORDER BY score DESC,id DESC LIMIT 1)
                    ) a JOIN tbl_answer_detail ad ON ad.ans_id=a.id
                ) t WHERE celebrity_id=c.id) total_wears
                FROM tbl_celebrity c
                JOIN tbl_celebrity_follow cf ON c.id=cf.celebrity_id AND cf.user_id=$user_id
                ORDER BY cf.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql);
    }

    /** 品牌 */
    static function countBrandsByUserId($user_id) {
        $sql = "SELECT COUNT(b.id) total FROM tbl_brand b JOIN tbl_brand_follow bf ON b.id=bf.brand_id AND bf.user_id=$user_id";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listBrandsByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT b.id,b.name_cn,b.name_en,b.logo,
                (SELECT IF(COUNT(id) IS NULL,0,COUNT(id)) FROM tbl_brand_follow WHERE brand_id=b.id) total_fans,
                (SELECT IF(COUNT(ans_id) IS NULL,0,COUNT(ans_id)) FROM (
                    SELECT DISTINCT a.id ans_id,brand_id FROM (
                        -- 最热答案
                        SELECT id FROM tbl_answer a
                        WHERE isdel='N' AND id=(SELECT id FROM tbl_answer WHERE ques_id=a.ques_id AND isdel='N' ORDER BY score DESC,id DESC LIMIT 1)
                    ) a JOIN tbl_answer_detail ad ON ad.ans_id=a.id
                ) t WHERE brand_id=b.id) total_wears
                FROM tbl_brand b
                JOIN tbl_brand_follow bf ON b.id=bf.brand_id AND bf.user_id=$user_id
                ORDER BY bf.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql);
    }

    /** 偶像 */
    static function countIdolsByUserId($user_id) {
        $sql = "SELECT COUNT(u.id) total FROM tbl_user u JOIN tbl_user_follow uf ON u.id=uf.user_id AND uf.follower_id=$user_id";
        $rows = DbUtils::query($sql);
        return empty($rows) ? 0 : intval($rows[0]['total']);
    }
    static function listIdolsByUserId($user_id, $offset=0, $rows=20) {
        $sql = "SELECT u.id,u.nick,u.head,
                (SELECT IF(COUNT(id) IS NULL,0,COUNT(id)) FROM tbl_question WHERE user_id=u.id) total_questions,
                (SELECT IF(COUNT(id) IS NULL,0,COUNT(id)) FROM tbl_answer WHERE user_id=u.id) total_answers
                FROM tbl_user u
                JOIN tbl_user_follow uf ON u.id=uf.user_id AND uf.follower_id=$user_id
                ORDER BY uf.id DESC LIMIT $offset,$rows";
        return DbUtils::query($sql);
    }
}
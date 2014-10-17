<?php
class SearchService {

    /** 总条数 */
    static function countAll($word) {
        $count_sql = "SELECT 'celebrity' tag,COUNT(1) total FROM tbl_celebrity WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'
                    UNION ALL
                    SELECT 'brand',COUNT(1) total FROM tbl_brand WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'
                    UNION ALL
                    SELECT 'topic',COUNT(1) total FROM tbl_topic WHERE title LIKE '%$word%'
                    UNION ALL
                    SELECT 'compare',COUNT(1) total FROM tbl_compare WHERE title LIKE '%$word%'
                    UNION ALL
                    SELECT 'user',COUNT(1) total FROM tbl_user WHERE nick LIKE '%$word%'";
        return DbUtils::query($count_sql);
    }

    /** 所有 */
    static function findAll($word, $star_count=14, $brand_count=14, $topic_count=8, $compare_count=8, $user_count=24) {
        $sql = "(SELECT s.id,'celebrity' tag,s.name,s.head img FROM (
                SELECT s.id,COUNT(sf.id) FROM (
                    SELECT id FROM tbl_celebrity WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'
                ) s
                LEFT JOIN tbl_celebrity_follow sf ON sf.celebrity_id=s.id
                GROUP BY s.id ORDER BY COUNT(sf.id) DESC LIMIT $star_count
            ) t JOIN tbl_celebrity s ON t.id=s.id)
            UNION ALL
            (SELECT b.id,'brand',b.name,b.logo FROM (
                    SELECT b.id,COUNT(bf.id) FROM (
                        SELECT id FROM tbl_brand WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'
                    ) b
                    LEFT JOIN tbl_brand_follow bf ON bf.brand_id=b.id
                    GROUP BY b.id ORDER BY COUNT(bf.id) DESC LIMIT $brand_count
                ) t JOIN tbl_brand b ON t.id=b.id)
            UNION ALL
            (SELECT tp.id,'topic',tp.title,tp.cover FROM (
                    SELECT t.id,COUNT(tf.id) FROM (
                        SELECT id FROM tbl_topic WHERE title LIKE '%$word%'
                    ) t
                    LEFT JOIN tbl_topic_follow tf ON tf.topic_id=t.id
                    GROUP BY t.id ORDER BY COUNT(tf.id) DESC LIMIT $topic_count
                ) t JOIN tbl_topic tp ON t.id=tp.id)
            UNION ALL
            (SELECT c.id,'compare',c.title,'' img FROM (
                    SELECT c.id,COUNT(cs.id) FROM (
                        SELECT id FROM tbl_compare WHERE title LIKE '%$word%'
                    ) c
                    LEFT JOIN tbl_compare_support cs ON cs.compare_id=c.id
                    GROUP BY c.id ORDER BY COUNT(cs.id) DESC LIMIT $compare_count
                ) t JOIN tbl_compare c ON t.id=c.id)
            UNION ALL
            (SELECT u.id,'user',u.nick,u.head FROM (
                    SELECT u.id,COUNT(uf.id) FROM (
                    SELECT id FROM tbl_user WHERE nick LIKE '%$word%'
                    ) u
                    LEFT JOIN tbl_user_follow uf ON uf.user_id=u.id
                    GROUP BY u.id ORDER BY COUNT(uf.id) DESC LIMIT $user_count
                ) t JOIN tbl_user u ON t.id=u.id)";
        return DbUtils::query($sql);
    }

    /** 名人总条数 */
    static function countStar($word) {
        $count_sql = "SELECT COUNT(1) total FROM tbl_celebrity WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'";
        $rows = DbUtils::query($count_sql);
        return empty($rows) ? 0 : $rows[0]['total'];
    }

    /** 名人 */
    static function listStars($word, $offset=0, $rows=20) {
        $sql = "SELECT s.id,s.name,s.head FROM (
                    SELECT s.id,COUNT(sf.id) FROM (
                      SELECT id FROM tbl_celebrity WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'
                    ) s
                    LEFT JOIN tbl_celebrity_follow sf ON sf.celebrity_id=s.id
                    GROUP BY s.id ORDER BY COUNT(sf.id) DESC LIMIT $offset,$rows
                ) t JOIN tbl_celebrity s ON t.id=s.id";
        return DbUtils::query($sql);
    }

    /** 品牌总条数 */
    static function countBrand($word) {
        $count_sql = "SELECT COUNT(1) total FROM tbl_brand WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'";
        $rows = DbUtils::query($count_sql);
        return empty($rows) ? 0 : $rows[0]['total'];
    }

    /** 品牌 */
    static function listBrands($word, $offset=0, $rows=20) {
        $sql = "SELECT b.id,b.name,b.logo FROM (
                    SELECT b.id,COUNT(bf.id) FROM (
                        SELECT id FROM tbl_brand WHERE name_cn LIKE '%$word%' OR name_en LIKE '%$word%'
                    ) b
                    LEFT JOIN tbl_brand_follow bf ON bf.brand_id=b.id
                    GROUP BY b.id ORDER BY COUNT(bf.id) DESC LIMIT $offset,$rows
                ) t JOIN tbl_brand b ON t.id=b.id";
        return DbUtils::query($sql);
    }

    /** 话题总条数 */
    static function countTopic($word) {
        $count_sql = "SELECT COUNT(1) total FROM tbl_topic WHERE title LIKE '%$word%'";
        $rows = DbUtils::query($count_sql);
        return empty($rows) ? 0 : $rows[0]['total'];
    }

    /** 话题 */
    static function listTopics($word, $offset=0, $rows=20) {
        $sql = "SELECT tp.id,tp.title,tp.cover FROM (
                    SELECT t.id,COUNT(tf.id) FROM (
                        SELECT id FROM tbl_topic WHERE title LIKE '%$word%'
                    ) t
                    LEFT JOIN tbl_topic_follow tf ON tf.topic_id=t.id
                    GROUP BY t.id ORDER BY COUNT(tf.id) DESC LIMIT $offset,$rows
                ) t JOIN tbl_topic tp ON t.id=tp.id";
        return DbUtils::query($sql);
    }

    /** 对比总条数 */
    static function countCompare($word) {
        $count_sql = "SELECT COUNT(1) total FROM tbl_compare WHERE title LIKE '%$word%'";
        $rows = DbUtils::query($count_sql);
        return empty($rows) ? 0 : $rows[0]['total'];
    }

    /** 对比 */
    static function listCompares($word, $offset=0, $rows=20) {
        $sql = "SELECT c.id,c.title FROM (
                    SELECT c.id,COUNT(cs.id) FROM (
                        SELECT id FROM tbl_compare WHERE title LIKE '%$word%'
                    ) c
                    LEFT JOIN tbl_compare_support cs ON cs.compare_id=c.id
                    GROUP BY c.id ORDER BY COUNT(cs.id) DESC LIMIT $offset,$rows
                ) t JOIN tbl_compare c ON t.id=c.id";
        return DbUtils::query($sql);
    }

    /** 用户总条数 */
    static function countUser($word) {
        $count_sql = "SELECT COUNT(1) total FROM tbl_user WHERE nick LIKE '%$word%'";
        $rows = DbUtils::query($count_sql);
        return empty($rows) ? 0 : $rows[0]['total'];
    }

    /** 用户 */
    static function listUsers($word, $offset=0, $rows=20) {
        $sql = "SELECT u.id,u.nick,u.head FROM (
                    SELECT u.id,COUNT(uf.id) FROM (
                    SELECT id FROM tbl_user WHERE nick LIKE '%$word%'
                    ) u
                    LEFT JOIN tbl_user_follow uf ON uf.user_id=u.id
                    GROUP BY u.id ORDER BY COUNT(uf.id) DESC LIMIT $offset,$rows
                ) t JOIN tbl_user u ON t.id=u.id";
        return DbUtils::query($sql);
    }

}
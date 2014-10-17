<?php
/**
 * Created by PhpStorm.
 * User: gracier11
 * Date: 14-3-13
 * Time: 下午11:26
 */

class LocationService {

    /** 获取省份列表 */
    static function getRegions() {
        $cache = Yii::app()->cache;
        $key = AppUtils::serial('_REGION_');
        $data = $cache->get($key);
        if($data == false) {
            $sql = 'select id,name from s_province order by id asc';
            $rows = DbUtils::query($sql);
            foreach($rows as $row) {
                $data[$row['id']] = array(
                    'id'=>$row['id'],
                    'name'=>$row['name'],
                );
            }
            // 永不过期
            $cache->set($key, $data, 0);
        }
        return $data;
    }

    /** 获取城市列表 */
    static function getCities($region) {
        $cache = Yii::app()->cache;
        $key = AppUtils::serial('_CITY_'.$region.'_');
        $data = $cache->get($key);
        if($data == false) {
            $data = array();
            if(AppUtils::is_number($region)) {
                $sql = 'select c.id,c.name,p.id region_id,p.name region from s_city c,s_province p where c.province_id=p.id and p.id=:region order by p.id asc,c.id asc';
            } else {
                $sql = "select c.id,c.name,p.id region_id,p.name region from s_city c,s_province p where c.province_id=p.id and p.name=:region order by p.id asc,c.id asc";
            }
            $rows = DbUtils::query($sql, array(':region'=>$region));
            if(!empty($rows)) {
                $key_id = false;
                $key_name = false;
                foreach($rows as $row) {
                    if($key_id === false) $key_id = AppUtils::serial('_CITY_'.$row['region_id'].'_');
                    if($key_name === false) $key_name = AppUtils::serial('_CITY_'.$row['region'].'_');
                    $data[$row['id']] = array(
                        'id'=>$row['id'],
                        'name'=>$row['name'],
                        'region_id'=>$row['region_id'],
                        'region'=>$row['region'],
                    );
                }
                // 永不过期
                if($cache->get($key_id) == false) $cache->set($key_id, $data, 0);
                if($cache->get($key_name) == false) $cache->set($key_name, $data, 0);
            }
        }
        return $data;
    }

    /** 查询县/区列表 */
    static function getCounties($region, $city) {
        $cache = Yii::app()->cache;
        $key = AppUtils::serial('_COUNTY_'.$region.'_'.$city.'_');
        $data = $cache->get($key);
        if($data == false) {
            $data = array();
            $sql = 'select d.id,d.name,c.id city_id,c.name city,p.id region_id,p.name region from s_district d,s_city c,s_province p where d.city_id=c.id and c.province_id=p.id ';
            if(AppUtils::is_number($city)) {
                $sql .= ' and c.id=:city ';
            } else {
                $sql .= ' and c.name=:city ';
            }
            if(AppUtils::is_number($region)) {
                $sql .= ' and p.id=:region ';
            } else {
                $sql .= ' and p.name=:region ';
            }
            $sql .= ' order by d.id asc,p.id asc,c.id asc';
            $rows = DbUtils::query($sql, array(
                ':city'=>$city,
                ':region'=>$region,
            ));
            if(!empty($rows)) {
                $key_id_id = false;
                $key_id_name = false;
                $key_name_id = false;
                $key_name_name = false;
                foreach($rows as $row) {
                    if($key_id_id === false) $key_id_id = AppUtils::serial('_COUNTY_'.$row['region_id'].'_'.$row['city_id'].'_');
                    if($key_id_name === false) $key_id_name = AppUtils::serial('_COUNTY_'.$row['region_id'].'_'.$row['city'].'_');
                    if($key_name_id === false) $key_name_id = AppUtils::serial('_COUNTY_'.$row['region'].'_'.$row['city_id'].'_');
                    if($key_name_name === false) $key_name_name = AppUtils::serial('_COUNTY_'.$row['region'].'_'.$row['city'].'_');
                    $data[$row['id']] = array(
                        'id'=>$row['id'],
                        'name'=>$row['name'],
                        'city_id'=>$row['city_id'],
                        'city'=>$row['city'],
                        'region_id'=>$row['region_id'],
                        'region'=>$row['region'],
                    );
                }
                // 永不过期
                $cache->set($key_id_id, $data, 0);
                $cache->set($key_id_name, $data, 0);
                $cache->set($key_name_id, $data, 0);
                $cache->set($key_name_name, $data, 0);
            }
        }
        return $data;
    }

}
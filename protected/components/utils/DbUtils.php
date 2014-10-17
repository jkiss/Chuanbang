<?php
class DbUtils {
    public static function execute($sql, $params=array()) {
        return Yii::app()->db->createCommand($sql)->execute($params);
    }

    public static function query($sql, $params=array()) {
        $result = array();
        $dataReader = Yii::app()->db->createCommand($sql)->query($params);
        while(($row=$dataReader->read())!==false) {
            array_push($result, $row);
        }
        return $result;
    }
}
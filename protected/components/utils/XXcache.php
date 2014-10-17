<?php
class XXcache {

    /**
     * 取缓存
     *
     * @param $id
     */
    public static function get( $id , $default = false) {
        $value = Yii::app()->cache->get( $id );
        if ( $value === false )
            return $default;
         else
            return $value;
    }

    /**
     * 设置缓存
     */
    public static function set( $id = '', $data = '', $expires = 3600 ) {
        return Yii::app()->cache->set( $id, $data, $expires );
    }

}

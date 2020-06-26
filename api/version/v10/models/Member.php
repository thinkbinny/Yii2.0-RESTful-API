<?php
namespace api\version\v10\models;
use common\models\Member as Common;
class Member extends Common{

    public static function getUserInfo($uid){
        $info = self::find()
            ->where('uid=:uid')
            ->addParams([':uid'=>$uid])
            ->asArray()
            ->one();
        return $info;
    }

}

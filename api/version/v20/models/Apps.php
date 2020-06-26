<?php
namespace api\modules\v10\models;
use common\models\Apps as common;
class Apps extends common{

    /*public $app_id;
    public $method;
    public $format;
    public $sign_method;
    public $timestamp;
    public $v;
    public $sign;

    public function rules() {
        return [
            ['app_id', 'required', 'message'=>'1110'],
            ['method', 'required', 'message'=>'1101'],
            ['format', 'in', 'range' => ['json','xml'],'message'=>'1103'],
            ['sign_method', 'required', 'message'=>'1104'],
            ['sign_method', 'in', 'range' => ['md5','hmac'],'message'=>'1105'],
            ['timestamp', 'required', 'message'=>'1112'],
            ['timestamp', 'getTimestamp', 'message'=>'1113'],//非法的时间戳参数 时间相差不到10分
            ['v', 'required', 'message'=>'1114'],
            ['v', 'in', 'range' => ['1.0','1.1'], 'message'=>'1115'],//非法的版本参数  1114 不支持的版本号
            ['sign', 'required','message'=>'1107'],
            [['app_id','method','format','sign_method','timestamp','v','sign'], 'string', 'message' => '1118'],

        ];
    }

    public function getTimestamp($attribute)
    {

        $newtime    = time();
        $pasttime   = $newtime - 600;
        $futuretime = $newtime + 600;
        $timestamp  = strtotime($this->timestamp);
        if(empty($timestamp)){
            $this->addError($attribute, '1113');
        }elseif($timestamp<$pasttime || $timestamp>$futuretime){
            $this->addError($attribute, '1113');
        }
    }*/


    public function fields()
    {
        $fields =  parent::fields();

        return $fields;
    }

}

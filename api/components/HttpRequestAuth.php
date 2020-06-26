<?php
namespace api\components;
use Yii;

class HttpRequestAuth extends \yii\base\Model{
    public $modelClass;
    public $params;//全部参数
    private $app_secret;
    public $app_id,$sign_method,$timestamp,$format,$sign;
    public function attributeLabels() {
        return [
            'app_id'        =>'App Id',
            'sign_method'   =>'签名方法',
            'format'        =>'数据格式',
            'timestamp'     =>'时间戳',
            'sign'          =>'签名参数',
        ];
    }

    /**
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/5 0:22
     */
    public function rules() {
        return [
            ['app_id','required','message'=>'1110'],
            ['sign_method','required','message'=>'1104'],
            ['timestamp','required','message'=>'1112'],
            ['sign','required','message'=>'1106'],
            ['app_id','integer','message'=>'1111'],//必须是数字
            ['timestamp','datetime','message'=>'1113'],
            ['sign_method','in', 'range' => ['md5','hmac'],'message'=>'1105'],
            ['format','in', 'range' => ['json','xml'],'message'=>'1103'],//json xml
            ['sign','string', 'length' => [4, 50],'message'=>'1107'],
            ['timestamp','checkTimestamp'],
            [['app_id','format','sign_method','timestamp','sign'], 'string', 'message' => '1118'],
            ['app_id','checkAppId'],//验证app_id
            ['sign','checkSign'],//验证sign
        ];
    }

    /**
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/5 13:12
     */
    public function checkTimestamp($attribute){
        $newtime    = time();
        $pasttime   = $newtime - 600;
        $futuretime = $newtime + 600;
        $timestamp  = strtotime($this->timestamp);
        if(empty($timestamp)){
            $this->addError($attribute, '1113');
        }elseif($timestamp<$pasttime || $timestamp>$futuretime){
            $this->addError($attribute, '1113');
        }
    }

    /**
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/5 15:16
     */
    public function checkAppId($attribute){
        $app_secret = Yii::$app->cache->get('getAppsSecret');
        if($app_secret === false) {
            $model = $this->modelClass;
            $data = $model::find()
                ->where("app_id=:app_id AND status=:status")
                ->addParams([':app_id' => $this->app_id, ':status' => 1])
                ->select('app_secret')
                ->one();
            if (empty($data)) {
                $this->addError($attribute, '1111');
                return false;
            }
            Yii::$app->cache->set('getAppsSecret', $data['app_secret'], 300);//五分钟
            $this->app_secret = $data['app_secret'];
        }else{
            $this->app_secret   = $app_secret;
        }
    }

    /**
     * @param $attribute
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/5 15:41
     */
    public function checkSign($attribute){
        $sign = $this->generateSign($this->params);
        if($sign != $this->sign){
            $this->addError($attribute, '1107');
        }
    }
    /**
     * 生成签名
     * @param  array $params 待校验签名参数
     * @return string|false
     */
    private function generateSign($params){
        unset($params['sign']);
        ksort($params);
        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }
        $string = $this->app_secret . implode('', $tmps) . $this->app_secret;
        $string = $this->EncryptionSign($string); //加密方式
        return strtoupper($string);
    }
    /**
     * 加密方式方式签名
     * @param  $string 待签名参数
     * @return string
     */
    private function EncryptionSign($string)
    {
        if ($this->sign_method == 'md5'){
            return md5($string);
        }elseif ($this->sign_method == 'hmac'){
            return $this->hmac($string,$this->app_id);
        }
    }
    /**
     * 基于md5的加密算法hmac
     *
     * md5已经不是那么安全了，多折腾几下吧
     *
     * @param String $data 预加密数据
     * @param String $key  密钥
     * @return String
     */
    private function hmac($data, $key='yii2'){
        if (function_exists('hash_hmac')) {
            return hash_hmac('md5', $data, $key);
        }
        $key = (strlen($key) > 64) ? pack('H32', 'md5') : str_pad($key, 64, chr(0));
        $ipad = substr($key,0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($key,0, 64) ^ str_repeat(chr(0x5C), 64);
        return md5($opad.pack('H32', md5($ipad.$data)));
    }
}
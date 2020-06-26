<?php
namespace api\common\models;
use yii\filters\RateLimitInterface;
use common\models\User as common;
use Yii;
class User extends common implements RateLimitInterface
{
    //为了减少服务器负担用户 redis 缓存 的 哈希函数
    //限流
    private $redisCache = 'RateLimitRedisCache';
    private $limit      = 30;//次数
    private $seconds    = 60;//多少秒

    /**
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/9 0:21
     */
    public function getRateLimit($request, $action)
    {
        return [$this->limit, $this->seconds]; // $rateLimit requests per second
        //多少次，多少秒
    }

    /**
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/9 0:21
     */
    public function loadAllowance($request, $action)
    {
        //print_r($action);exit;
        $data = Yii::$app->redis->hmget($this->redisCache,$this->id);
        $value = $data[0];
        if(empty($value)){
            $allowance  = 0;
            $time       = 0;
        }else{
            $value = json_decode($value,true);
            $allowance  = $value['allowance'];
            $time       = $value['time'];
        }
        return [$allowance,$time];
    }

    /**
     * @param \yii\web\Request $request
     * @param \yii\base\Action $action
     * @param int $allowance
     * @param int $timestamp
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/9 0:21
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {

        $data = [
          'allowance' =>  $allowance,
          'time'      =>  $timestamp
        ];
        $data = json_encode($data);
        Yii::$app->redis->hmset($this->redisCache,$this->id,$data);
    }
    //End 限流

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*return static::find()
            ->where(['access_token' => $token,'status'=>self::STATUS_ACTIVE])
            ->select("id,username,mobile,email,reg_ip,created_at,updated_at")
            ->one();*/
        return static::findOne(['access_token' => $token,'status'=>self::STATUS_ACTIVE]);
    }


}
<?php
namespace api\components;
/**
 * Class Error
 * @package api\components
 * @Author 七秒记忆 <274397981@qq.com>
 * @Date 2020/6/3 23:56
 */
class Error{

    public static $errCodes = [
        '401' => ['name'=>'Insufficient Jurisdiction','message'=>'无此权限'],
        // 系统码
        '1001' => ['name'=>'User Call Limited','message'=>'调用次数超限'],
        '1002' => ['name'=>'Session Call Limited','message'=>'会话调用次数超限'],
        '1003' => ['name'=>'App Call Limited','message'=>'调用次数超限'],
        '1004' => ['name'=>'App Call Exceeds LimitedFrequency','message'=>'调用频率超限'],

        '1110' => ['name'=>'Missing App Id','message'=>'缺少App Id参数'],
        '1111' => ['name'=>'Invalid App Id','message'=>'非法的App Id参数'],
        '1112' => ['name'=>'Missing Timestamp','message'=>'缺少时间戳参数'],
        '1113' => ['name'=>'Invalid Timestamp','message'=>'非法的时间戳参数'],
        '1114' => ['name'=>'Missing Version','message'=>'缺少版本参数'],
        '1115' => ['name'=>'Invalid Version','message'=>'非法的版本参数'],
        '1116' => ['name'=>'Unsupported Version','message'=>'不支持的版本号'],
        '1117' => ['name'=>'Missing Required Arguments','message'=>'缺少必选参数'],
        '1118' => ['name'=>'Invalid Arguments','message'=>'非法的参数'],

        '1103' => ['name'=>'Invalid Format','message'=>'非法数据格式'],
        '1104' => ['name'=>'Missing Sign Method','message'=>'缺少签名方法参数'],
        '1105' => ['name'=>'Invalid Sign Method','message'=>'非法签名方法参数'],
        '1106' => ['name'=>'Missing Signature','message'=>'缺少签名参数'],
        '1107' => ['name'=>'Invalid Signature','message'=>'非法签名'],


        /*'200' => ['name'=>'Success','message'=>'成功'],
        '400' => ['name'=>'Unknown Error','message'=>'未知错误'],

        '500' => ['name'=>'Unusual Server','message'=>'服务器异常'],*/

        // 公共错误码
        /*
        '1005' => ['name'=>'Http Action Not Allowed','message'=>'HTTP方法被禁止'],
        '1006' => ['name'=>'Service Currently Unavailable','message'=>'服务不可用'],
        '1007' => ['name'=>'Insufficient ISV Permissions','message'=>'开发者权限不足'],
        '1008' => ['name'=>'Insufficient User Permissions','message'=>'用户权限不足'],
        '1009' => ['name'=>'Remote Service Error','message'=>'远程服务出错'],
        '1101' => ['name'=>'Missing Method','message'=>'缺少方法名参数'],
        '1102' => ['name'=>'Invalid Method','message'=>'不存在的方法名'],


        '1108' => ['name'=>'Missing Session','message'=>'缺少SessionKey参数'],
        '1109' => ['name'=>'Invalid Session','message'=>'无效的SessionKey参数'],
        '1119' => ['name'=>'Forbidden Request','message'=>'请求被禁止'],
        '1120' => ['name'=>'Parameter Error','message'=>'参数错误'],*/
    ];
    /**
     * Error constructor.
     */
    private function __construct(){

    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/3 23:56
     */
    public static function getError($code=400){
        if(empty($code) || !isset(self::$errCodes[$code])){
            throw new HttpException(400, $code, 400);
        }
        $error = self::$errCodes[$code];
        throw new HttpException(401, $error['message'], $code,$error['name']);
    }

}

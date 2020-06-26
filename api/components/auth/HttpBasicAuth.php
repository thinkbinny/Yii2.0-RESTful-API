<?php
namespace api\components\auth;

use api\components\Error;

class HttpBasicAuth extends \yii\filters\auth\HttpBasicAuth
{



    /**
     * @param \yii\web\Response $response
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/8 1:05
     */
    public function handleFailure($response)
    {

        Error::getError(401);
        //throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
    }
}
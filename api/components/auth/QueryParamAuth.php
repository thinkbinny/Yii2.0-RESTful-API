<?php
namespace api\components\auth;

use api\components\Error;

class QueryParamAuth extends \yii\filters\auth\QueryParamAuth
{

    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->getQueryParam($this->tokenParam);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }

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
<?php

namespace api\components\auth;

use api\components\Error;

class CompositeAuth extends \yii\filters\auth\CompositeAuth
{

    public function handleFailure($response)
    {

        Error::getError(401);
        //throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
    }
}

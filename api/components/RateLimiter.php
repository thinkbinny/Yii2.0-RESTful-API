<?php
namespace api\components;
use yii\web\TooManyRequestsHttpException;
use Yii;
class RateLimiter extends \yii\filters\RateLimiter
{
    public $errorMessage = 'Rate limit exceeded.';

    public function checkRateLimit($user, $request, $response, $action)
    {
        list($limit, $window) = $user->getRateLimit($request, $action);

        list($allowance, $timestamp) = $user->loadAllowance($request, $action);

        $current = time();

        $allowance += (int) (($current - $timestamp) * $limit / $window);
        if ($allowance > $limit) {
            $allowance = $limit;
        }

        if ($allowance < 1) {
            $user->saveAllowance($request, $action, 0, $current);
            $this->addRateLimitHeaders($response, $limit, 0, $window);
            //Error::getError(1004);
            throw new TooManyRequestsHttpException($this->errorMessage);
        }

        $user->saveAllowance($request, $action, $allowance - 1, $current);
        $this->addRateLimitHeaders($response, $limit, $allowance - 1, (int) (($limit - $allowance + 1) * $window / $limit));
    }
}
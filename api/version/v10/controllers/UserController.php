<?php
namespace api\version\v10\controllers;
use api\version\v10\models\Member;
use api\version\v10\models\LoginForm;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;

/**
 * Site controller
 */
class UserController extends BaseController
{
    public $modelClass = 'api\version\v10\models\LoginForm';



    /**
     * 登陆
     * @return LoginForm|array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/1 23:01
     */
    public function actionLogin(){
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $token = $model->login()) {
            $user = $model->getUser();
            $member = Member::getUserInfo($user['id']);
            return [
                'nickname'=>$member['nickname'],
                'headimgurl'=>$member['headimgurl'],
                'access_token' => $token,
            ];
        } else {
            return $model;
        }


    }
}

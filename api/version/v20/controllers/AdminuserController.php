<?php
namespace api\controllers;
use api\models\LoginForm;
use yii\rest\ActiveController;


/**
 * Site controller
 */
class Adminuser extends ActiveController
{
    public $modelClass = 'api\models\LoginForm';

    public function actionLogin(){
        $model = new LoginForm();

        if($model->login()){
            return [
                'access_token' => '',
            ];
        }else{
            $model->validate();
            return $model;
        }
    }
}

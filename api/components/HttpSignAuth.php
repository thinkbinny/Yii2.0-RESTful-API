<?php
namespace api\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

/**
 * sign 验证类
 */
class HttpSignAuth extends Behavior {

    public $modelClass;
    public function events() {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    public function beforeAction($event) {
        if(Yii::$app->request->isPost){
            $params = Yii::$app->request->getBodyParams();
        }else{
            $params = Yii::$app->request->getQueryParams();
        }

        $model = new HttpRequestAuth();
        $model -> modelClass = $this->modelClass;
        $model -> params     = $params;
        $model->setAttributes($params);

        //验证基本信息
        if(!$model->validate()){
            $code = current( $model->getFirstErrors() );
            Error::getError($code);
        }

        return true;
    }


}

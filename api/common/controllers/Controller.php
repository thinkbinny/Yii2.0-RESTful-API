<?php
namespace api\common\controllers;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use api\components\HttpSignAuth;
use api\components\auth\CompositeAuth;
use api\components\auth\HttpBasicAuth;
use api\components\auth\HttpBearerAuth;
use api\components\auth\QueryParamAuth;

use api\components\RateLimiter;
use yii\web\Response;
use Yii;
/**
 * Controller
 */
class Controller extends \yii\rest\Controller
{
    /*public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];*/

    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;
    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
        if(Yii::$app->request->isPost){
            $format = Yii::$app->request->post('format','json');
        }else{
            $format = Yii::$app->request->get('format','json');
        }

        if($format == 'xml'){
            Yii::$app->response->format = Response::FORMAT_XML;
        }else{
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
    }

    /**
     * 重写 behaviors
     * @return array
     * @Author 七秒记忆 <274397981@qq.com>
     * @Date 2020/6/3 23:46
     */
    public function behaviors()
    {
        return [

            //增加新的接口验证类，参数加密的sign
            'tokenValidate'     => [
                //参数加密的sign所有接口都需要验证
                'class'     => HttpSignAuth::className(),
                'modelClass'=> \api\common\models\Apps::className(),//APPS表名
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::className(),
                //'class' => QueryParamAuth::className(),
                //'tokenParam'=>'token',
                /*'class' => CompositeAuth::className(),
                'authMethods' => [
                     //HttpBasicAuth::className(),
                     HttpBearerAuth::className(),
                     //QueryParamAuth::className(),
                ],*/
                'optional'  => ['register', 'login'],
            ],
            //限流
            'rateLimiter' => [
                'class' => RateLimiter::className(),
                'errorMessage'=>'您操作过于频繁，请稍后在试。',//超出速率限制
                'enableRateLimitHeaders' => false,
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function verbs()
    {
        /*return [
            'index'     => ['GET', 'HEAD'],
            'view'      => ['GET', 'HEAD'],
            'create'    => ['POST'],
            'update'    => ['PUT', 'PATCH'],
            'delete'    => ['DELETE'],
        ];*/
        return [
            'index'     => ['GET', 'POST'],
            'view'      => ['GET', 'POST'],
            'create'    => ['POST'],
            'update'    => ['POST'],
            'delete'    => ['POST'],
        ];
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
    }


}

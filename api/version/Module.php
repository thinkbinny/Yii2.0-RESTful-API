<?php

namespace api\version;
use api\components\Error;
use Yii;
use yii\base\Module as Modules;

use yii\web\Response;

/**
 * Admin module definition class
 */
class Module extends Modules
{
    /**
     * @inheritdoc
     */
    //public $controllerNamespace = 'api\version\v10\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $default         = 'v10';
        $versions        = ['1.0','2.0'];//未加V

        if(Yii::$app->request->isPost){
            $version = Yii::$app->request->getBodyParam('v',null);
        }else{
            $version = Yii::$app->request->getQueryParam('v',null);
        }
        if(is_null($version)){
            $version         = Yii::$app->getRequest()->getHeaders()->get('version',null);
        }
        if(is_null($version)){
            Error::getError(1114);
            //$version = $default;

        }else{
            //判断是否版本支持
            if(in_array($version,$versions)){
                $version = 'v'.str_replace(".","",$version);
            }else{
                Error::getError(1115);
                //$version = $default;
            }
        }
        $this->controllerNamespace = 'api\version\\'.$version.'\controllers';
        parent::init();

    }
}

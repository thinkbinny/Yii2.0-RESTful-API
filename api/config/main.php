<?php
use yii\web\Response;
$params = array_merge(
    require(YII_DIR . '/common/config/params.php'),
    require(__DIR__ . '/params.php')
);
$version = require(__DIR__ . '/version.php');
return [
    'id' => 'app-api',
    'homeUrl'=>'/api',
    'basePath' => dirname(__DIR__),
    //'bootstrap' => ['log'],
    'bootstrap' => [
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'application/xml' => Response::FORMAT_XML,
            ],
            'languages' => [
                'zh-CN',
                //'en',
                //'de',
            ],
        ]
    ],
    'modules' => [
        'version' => [
            'class' => 'api\version\Module',
        ],
        /*'v10' => [
            'class' => 'api\version\v10\Module',
        ],*/
    ],
    //'controllerNamespace' => 'api\version\\'.$version.'\controllers',
    //'controllerNamespace' => 'api\version\\'.$version.'\controllers',
    //'defaultRoute' => 'index/index',   //默认路由
    //'requestedRoute'=>'v10',
    'components' => [
        'response' => [

            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],

            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {

          /*      $response = $event->sender;
                if ($response->data !== null && !empty(Yii::$app->request->get('suppress_response_code'))) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }*/

                $response = $event->sender;

                if ($response->isSuccessful ) {
                    //成功200
                    $response->data = [
                        'status' => $response->isSuccessful,
                        'code' => $response->getStatusCode(),
                        'results' => $response->data,
                    ];
                    //$response->statusCode = 200;
                }else{
                    $statusCode = $response->getStatusCode();
                    if( $statusCode == 422){
                        if(empty($response->data)){
                            $message = '未知出错';
                            $name    = 'Unknown error';
                            $code    = 404;
                        }else{
                            $data = current($response->data);
                            $field     = $data['field'];
                            $message   = $data['message'];
                            $name      = $field.' field error';
                            $code      = 442;
                        }
                    }else{
                        $message = $response->data['message'];
                        $name    = $response->data['name'];
                        $code    = $response->data['code'];
                    }

                    $response->data = [
                        'status'    => $response->isSuccessful,
                        //'code'      => $response->getStatusCode(),
                        //'code_msg' => $response->statusText,
                        'code'      => $code,
                        'name'  => $name,
                        'msg'   => $message,//中文提示
                        //'results'   => $response->data,
                    ];
                }
                $response->statusCode = 200;
            },
        ],
        'request' => [
            'baseUrl' => '/api',
            //'csrfParam' => '_csrf-api',
            'cookieValidationKey' => 'u7M0VqWHmVBi5UL0v2qE9ABkli0MHh9E',
            //'class' => 'yii\web\Request',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass'     => 'api\common\models\User',
            'enableAutoLogin'   =>  true,
            'enableSession'     =>  false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => require(__DIR__ . '/rules.php'),

    ],
    'params' => $params,
];

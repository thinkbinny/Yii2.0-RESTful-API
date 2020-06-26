<?php
/**
 * https://www.yiichina.com/doc/guide/2.0/rest-routing
    GET /users：逐页列出所有用户；
    HEAD /users：显示用户列表的概要信息；
    POST /users：创建一个新用户；
    GET /users/123：返回用户为 123 的详细信息；
    HEAD /users/123：显示用户 123 的概述信息；
    PATCH /users/123 和 PUT /users/123：更新用户 123；
    DELETE /users/123：删除用户 123；
    OPTIONS /users：显示关于末端 /users 支持的动词；
    OPTIONS /users/123：显示有关末端 /users/123 支持的动词。
 *
 */
//index 路由 indices

/**
 * 为了符合大部分应该接口。下面定义只支持 GET与POST
 * 如：支付宝小程序只支持GET与POST
 */

return [
    // 默认不启用。但实际使用中，特别是产品环境，一般都会启用。
    'enablePrettyUrl' => true,
    // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
    // 否则认为是无效路由。
    // 这个选项仅在 enablePrettyUrl 启用后才有效。
    'enableStrictParsing' => true,
    'showScriptName'=>false,
    //'suffix'=>'.shtml',
    'rules'=>[
        'GET,POST /<controller>/<action:(index|view)>'=>'/version/<controller>/<action>',
        'POST /<controller>/<action:(create|update|delete)>'=>'/version/<controller>/<action>',
        'POST /<controller>/<action>'=>'/version/<controller>/<action>',
        //'GET,POST /v10/<controller>/<action>'=>'/v10/<controller>/<action>',

        //'GET,POST /<controller>/<action>'=>'/<controller>/<action>', //全部页面只支持
        //您可以通过配置 only 和 except 选项来明确列出哪些行为支持， 哪些行为禁用。例如，
        /*[
            'class' => 'yii\rest\UrlRule',//支持 GET HEAD POST GET HEAD PATCH DELETE OPTIONS OPTIONS
            'controller' => 'user',
            'except' => ['delete', 'create', 'update'],//禁用
            //'only'=>['index']
        ],*/
        /*[
            'class'         => 'yii\rest\UrlRule',
            'controller' => [ 'user'],
            //'controller'    =>'user',
            //'pluralize' => false,
            'patterns'      =>[
                'POST,GET login'=>'login',
            ],
            'extraPatterns' =>[
             //   'POST login'=>'login',
            ],
        ],*/
       /* [
            'class'         => 'yii\rest\UrlRule',//api\components
            'controller' => 'info',
            'patterns'=>[
                'GET index' => 'index',

            ],
            'extraPatterns' =>[
                //'GET index'=>'index',
               // 'POST create'=>'create',
                //'pluralize' => true,
            ],
            //'except'=>['index','view'],

        ],*/
        //'GET,POST /<controller>/<action>'=>'/version/<controller>/<action>',
    ]
];


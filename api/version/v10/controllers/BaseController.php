<?php
namespace api\version\v10\controllers;
use api\common\controllers\Controller;
use Yii;
/**
 * Site controller
 */
class BaseController extends Controller
{
    public $serializer = [
        'class' => 'api\components\Serializer',
        'linksEnvelope'=>'',
        'collectionEnvelope' => 'items',
        'metaEnvelope'=>'pagination'
    ];


}

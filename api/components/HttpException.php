<?php

namespace api\components;
use yii\web\Response;
use Yii;


class HttpException extends \yii\web\HttpException{
    public $name = null;

    public function __construct($status, $message = null, $code = 0, $name=null, \Exception $previous = null)
    {
        $this->statusCode = $status;
        if($name!==null){
            $this->name = $name;
        }
        parent::__construct($status, $message, $code, $previous);
    }
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {

        if($this->name !== null){
            return $this->name;
        }elseif (isset(Response::$httpStatuses[$this->statusCode])) {
            return Response::$httpStatuses[$this->statusCode];
        }

        return 'Error';
    }
}

<?php
namespace api\version\v10\controllers;
use Yii;
use api\version\v10\models\MemberRelation;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use api\common\models\Member;
/**
 * Site controller
 */
class ContactsController extends BaseController
{
    public $modelClass = 'api\version\v10\models\MemberRelation';

    public function actions()
    {
        $actions =  parent::actions(); // TODO: Change the autogenerated stub
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex(){

        //Yii::$app->websocket->send(['channel' => 'push-message', 'message' => '用户 xxx 送了一台飞机！']);
        //exit;
        $uid = Yii::$app->user->identity->id;
        $model = new MemberRelation();
        $query0= $model->find()
            ->where("user_id=:user_id")
            ->addParams([':user_id'=>$uid])
            ->select("friend_id AS friends,friend_alias as alias,created_at");
        $query1=$model->find()
            ->where("friend_id=:friend_id")
            ->addParams([':friend_id'=>$uid])
            ->select("user_id AS friends,user_alias as alias,created_at");
        $queryAll = $query0->union($query1,true);
        $query  = (new Query())->from([$queryAll])
            ->select('friends,alias,created_at,headimgurl,nickname')
            ->leftJoin(Member::tableName().' m','m.uid=friends')
            ->orderBy(['created_at'=>SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);







    }

}

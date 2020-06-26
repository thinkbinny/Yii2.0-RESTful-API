<?php
namespace api\version\v10\models;
use api\common\models\Messages as BaseMessages;
class Messages extends BaseMessages{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_user_id','to_user_id','type','content'],'required'],
            [['from_user_id','to_user_id','type','status','created_at'], 'integer'],
            [['content'], 'string', 'max' => 2000],
            ['status', 'default', 'value' =>0],
            ['type', 'default', 'value' =>1],
            ['created_at', 'default', 'value' =>time()],
        ];
    }



    public function fields()
    {
        $fields =  parent::fields();

        return $fields;
    }

}

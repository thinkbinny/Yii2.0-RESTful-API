<?php
namespace api\models;
use common\models\LoginForm as common;
use Yii;
class LoginForm extends common
{

    public $mobile;
    public $password;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile', 'password'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '手机号或密码不正确。');
            }
        }
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            //登陆成功返回数据
            return true;
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }

    public function getData(){
        $user   = $this->_user;
        $uid    = $user->id;
        $member = Member::find()
            ->where("uid=:uid")
            ->addParams([':uid'=>$uid])
            ->select("nickname,headimgurl")
            ->one();
        return [
            'uid'=>$uid,
            'mobile'=>$user->mobile,
            'nickname'=>$member->nickname,
            'headimgurl'=>$member->headimgurl,
        ];
    }
}
<?php
namespace api\common\models;
use api\common\models\User;
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
            //$uid = $this->_user['id'];
            $access_token = Yii::$app->security->generateRandomString().time();
            $access_token = hash('sha256',$access_token);
            $this->_user -> access_token = $access_token;
            $this->_user ->save();
            return $access_token;
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }


}
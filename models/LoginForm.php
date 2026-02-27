<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username = '';
    public $password = '';
    public $rememberMe = true;

    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user) {
                return Yii::$app->user->login(
                    $user,
                    $this->rememberMe ? 3600 * 24 * 30 : 0
                );
            }
        }
        return false;
    }
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $passwordToCheck = $this->password ?? '';
            if (!$user || !$user->validatePassword($passwordToCheck)) {
                $this->addError($attribute, 'Falscher Benutzername oder Passwort.');
            }
        }
    }

    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username ?? '');
        }
        return $this->_user;
    }
}
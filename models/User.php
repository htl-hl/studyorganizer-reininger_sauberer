<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $rememberMe = false;

    public static function tableName()
    {
        return 'Users';
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'on' => 'register'],
            [['username'], 'required', 'on' => 'login'],
            [['firstname'], 'required', 'on' => 'register'],
            [['lastname'], 'required', 'on' => 'register'],
            ['rememberMe', 'boolean'],
            [['username'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 255],
            [['firstname'], 'string', 'max' => 255],
            [['lastname'], 'string', 'max' => 255]
        ];
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}

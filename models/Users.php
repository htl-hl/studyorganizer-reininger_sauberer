<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 *
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'firstname', 'lastname'], 'required'],
            [['username', 'password', 'firstname', 'lastname'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Benutzername'),
            'password' => Yii::t('app', 'Passwort'),
            'firstname' => Yii::t('app', 'Vorname'),
            'lastname' => Yii::t('app', 'Nachname'),
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Homework".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $due_date
 * @property string|null $status
 * @property int $user_id
 * @property int $subject_id
 *
 * @property Subjects $subject
 * @property Users $user
 */
class Homework extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Homework';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'due_date', 'status'], 'default', 'value' => null],
            [['title', 'user_id', 'subject_id'], 'required'],
            [['description'], 'string'],
            [['due_date'], 'safe'],
            [['user_id', 'subject_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::class, 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'due_date' => 'Due Date',
            'status' => 'Status',
            'user_id' => 'User ID',
            'subject_id' => 'Subject ID',
        ];
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subjects::class, ['id' => 'subject_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

}

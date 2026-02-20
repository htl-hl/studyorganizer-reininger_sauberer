<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Subjects".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 *
 * @property Homework[] $homeworks
 * @property SubjectHasTeacher[] $subjectHasTeachers
 * @property Teachers[] $teachers
 */
class Subjects extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Subjects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Homeworks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworks()
    {
        return $this->hasMany(Homework::class, ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[SubjectHasTeachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectHasTeachers()
    {
        return $this->hasMany(SubjectHasTeacher::class, ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teachers::class, ['id' => 'teacher_id'])->viaTable('Subject_Has_Teacher', ['subject_id' => 'id']);
    }

}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Teachers".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property int $status
 *
 * @property SubjectHasTeacher[] $subjectHasTeachers
 * @property Subjects[] $subjects
 */
class Teachers extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Teachers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'status'], 'required'],
            [['status'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[SubjectHasTeachers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjectHasTeachers()
    {
        return $this->hasMany(SubjectHasTeacher::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subjects::class, ['id' => 'subject_id'])->viaTable('Subject_Has_Teacher', ['teacher_id' => 'id']);
    }

}

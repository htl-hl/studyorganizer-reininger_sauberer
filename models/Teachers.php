<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Teachers extends \yii\db\ActiveRecord
{
    // Virtuelles Attribut für die Mehrfachauswahl im Formular
    public $subject_ids = [];

    public static function tableName()
    {
        return 'Teachers';
    }

    public function rules()
    {
        return [
            [['firstname', 'lastname', 'status'], 'required'],
            [['status'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 50],
            [['subject_ids'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Vorname',
            'lastname' => 'Nachname',
            'status' => 'Status',
            'subject_ids' => 'Fächer',
        ];
    }

    /**
     * Holt alle verknüpften Fächer über die Zwischentabelle
     */
    public function getSubjects()
    {
        return $this->hasMany(Subjects::class, ['id' => 'subject_id'])
            ->viaTable('Subject_Has_Teacher', ['teacher_id' => 'id']);
    }

    /**
     * Speichert die Fächer-Verknüpfungen nach dem Speichern des Lehrers
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Zuerst alle alten Verknüpfungen löschen
        Yii::$app->db->createCommand()
            ->delete('Subject_Has_Teacher', ['teacher_id' => $this->id])
            ->execute();

        // Neue Verknüpfungen einfügen, wenn welche ausgewählt wurden
        if (!empty($this->subject_ids) && is_array($this->subject_ids)) {
            $rows = [];
            foreach ($this->subject_ids as $s_id) {
                $rows[] = [$s_id, $this->id];
            }
            Yii::$app->db->createCommand()
                ->batchInsert('Subject_Has_Teacher', ['subject_id', 'teacher_id'], $rows)
                ->execute();
        }
    }
}
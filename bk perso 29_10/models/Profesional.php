<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profesional".
 *
 * @property int $id
 * @property string $nombre_profesional
 * @property string $cargo_profesional
 */
class Profesional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profesional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_profesional', 'cargo_profesional'], 'required'],
            [['nombre_profesional', 'cargo_profesional'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_profesional' => 'Nombre Profesional',
            'cargo_profesional' => 'Cargo Profesional',
        ];
    }
}

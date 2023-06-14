<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motivo_certificado".
 *
 * @property int $id_motivo
 * @property string $nombre_motivo
 * @property string $descripcion_motivo
 */
class MotivoCertificado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'motivo_certificado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_motivo', 'descripcion_motivo'], 'required'],
            [['descripcion_motivo'], 'string'],
            [['nombre_motivo'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_motivo' => 'Id Motivo',
            'nombre_motivo' => 'Nombre Motivo',
            'descripcion_motivo' => 'Descripcion Motivo',
        ];
    }
}

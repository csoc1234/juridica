<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_regimen}}".
 *
 * @property int $id_tipoRegimen
 * @property string $nombre_tipoRegimen
 */
class TipoRegimen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipo_regimen}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_tipoRegimen'], 'required'],
            [['nombre_tipoRegimen'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipoRegimen' => 'Id Tipo Regimen',
            'nombre_tipoRegimen' => 'Nombre Tipo Regimen',
        ];
    }
}

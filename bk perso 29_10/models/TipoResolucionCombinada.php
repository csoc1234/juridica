<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_resolucion_combinada}}".
 *
 * @property int $id_tipo_resolucion_combinada
 * @property string $nombre_tipo_resolucion_combinada
 */
class TipoResolucionCombinada extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipo_resolucion_combinada}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_tipo_resolucion_combinada'], 'required'],
            [['nombre_tipo_resolucion_combinada'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_resolucion_combinada' => 'Id Tipo Resolucion Combinada',
            'nombre_tipo_resolucion_combinada' => 'Nombre Tipo Resolucion Combinada',
        ];
    }
}

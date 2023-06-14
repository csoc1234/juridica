<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ubicacion_entidad}}".
 *
 * @property string $id_ubicacion
 * @property string $descripcion
 * @property string $codigo
 */
class UbicacionEntidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ubicacion_entidad}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion', 'codigo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_ubicacion' => 'Id Ubicacion',
            'descripcion' => 'Descripcion',
            'codigo' => 'Codigo',
        ];
    }
}

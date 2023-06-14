<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_registro_libro}}".
 *
 * @property string $id_tipo_registro_libro
 * @property string $nombre_tipo_registro_libro
 */
class TipoRegistroLibro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipo_registro_libro}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_tipo_registro_libro'], 'required'],
            [['nombre_tipo_registro_libro'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_registro_libro' => 'Id Tipo Registro Libro',
            'nombre_tipo_registro_libro' => 'Nombre Tipo Registro Libro',
        ];
    }
}

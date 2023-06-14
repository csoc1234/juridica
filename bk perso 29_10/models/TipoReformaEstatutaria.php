<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_reforma_estatutaria}}".
 *
 * @property string $id_tipo_reforma_estatutaria
 * @property string $nombre_tipo_reforma_estatutaria
 */
class TipoReformaEstatutaria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipo_reforma_estatutaria}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_tipo_reforma_estatutaria'], 'required'],
            [['nombre_tipo_reforma_estatutaria'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_reforma_estatutaria' => 'Id Tipo Reforma Estatutaria',
            'nombre_tipo_reforma_estatutaria' => 'Nombre Tipo Reforma Estatutaria',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_entidadcg}}".
 *
 * @property int $id_entidad
 * @property string $nombre
 */
class TipoEntidadcg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_entidadcg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_entidadcg' => 'Id Entidad',
            'nombre' => 'Nombre',
        ];
    }

    public function getRadicados()
    {
        return $this->hasMany(Radicados::className(), ['id_entidadcg' => 'id_entidadcg']);
    }
}

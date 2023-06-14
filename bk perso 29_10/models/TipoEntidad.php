<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_entidad".
 *
 * @property int $id_tipo_entidad
 * @property string $tipo_entidad
 * @property string $codigo_trd
 *
 * @property Entidades[] $entidades
 */
class TipoEntidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_entidad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_entidad', 'codigo_trd'], 'required'],
            [['codigo_trd','revision','gaceta'], 'string'],
            [['tipo_entidad'], 'string', 'max' => 100],
            [['activo'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_entidad' => 'Id Tipo Entidad',
            'tipo_entidad' => 'Tipo Entidad',
            'codigo_trd' => 'Codigo Trd',
            'revision' => 'Revision(resoluciones)',
            'gaceta' => 'gaceta(resoluciones)',
            'activo' => '¿se encuentra activa?'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidades()
    {
        return $this->hasMany(Entidades::className(), ['id_tipo_entidad' => 'id_tipo_entidad']);
    }
}

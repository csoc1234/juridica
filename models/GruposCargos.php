<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupos_cargos".
 *
 * @property integer $id_grupo_cargos
 * @property string $nombre_grupo_cargo
 *
 * @property Dignatarios[] $dignatarios
 */
class GruposCargos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grupos_cargos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_grupo_cargo'], 'required'],
            [['nombre_grupo_cargo'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_grupo_cargos' => 'Id Grupo Cargos',
            'nombre_grupo_cargo' => 'Nombre Grupo Cargo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDignatarios()
    {
        return $this->hasMany(Dignatarios::className(), ['id_grupo_cargos' => 'id_grupo_cargos']);
    }
}

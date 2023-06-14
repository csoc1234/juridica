<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cargos".
 *
 * @property integer $id_cargo
 * @property string $nombre_cargo
 *
 * @property Dignatarios[] $dignatarios
 */
class Cargos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cargos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_cargo'], 'required'],
            [['nombre_cargo'], 'string', 'max' => 50],
            [['nombre_cargo'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_cargo' => 'Id Cargo',
            'nombre_cargo' => 'Nombre Cargo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDignatarios()
    {
        return $this->hasMany(Dignatarios::className(), ['id_cargo' => 'id_cargo']);
    }
}

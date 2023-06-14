<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_tramite".
 *
 * @property integer $id_tipo_tramite
 * @property string $descripcion
 *
 * @property Radicados[] $radicados
 */
class TipoTramite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_tramite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_tramite' => 'Id Tipo Tramite',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRadicados()
    {
        return $this->hasMany(Radicados::className(), ['id_tipo_tramite' => 'id_tipo_tramite']);
    }
}

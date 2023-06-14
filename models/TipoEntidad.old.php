<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_entidad".
 *
 * @property integer $id_tipo_entidad
 * @property string $tipo_entidad
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
            [['tipo_entidad'], 'required'],
            [['tipo_entidad'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_entidad' => Yii::t('app', 'Id Tipo Entidad'),
            'tipo_entidad' => Yii::t('app', 'Tipo Entidad'),
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

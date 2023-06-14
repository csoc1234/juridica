<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_resolucion".
 *
 * @property integer $id_tipo_resolucion
 * @property string $nombre_tipo_resolucion
 *
 * @property Resoluciones[] $resoluciones
 */
class TipoResolucion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_resolucion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_resolucion', 'nombre_tipo_resolucion'], 'required'],
            [['id_tipo_resolucion'], 'integer'],
            [['nombre_tipo_resolucion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_resolucion' => 'Id Tipo Resolucion',
            'nombre_tipo_resolucion' => 'Nombre Tipo Resolucion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResoluciones()
    {
        return $this->hasMany(Resoluciones::className(), ['id_tipo_resolucion' => 'id_tipo_resolucion']);
    }
}

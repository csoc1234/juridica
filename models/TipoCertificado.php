<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipo_certificado}}".
 *
 * @property int $id_tipo_certificado
 * @property string $nombre_tipo_certificado
 */
class TipoCertificado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipo_certificado}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_certificado', 'nombre_tipo_certificado'], 'required'],
            [['id_tipo_certificado'], 'integer'],
            [['nombre_tipo_certificado'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_certificado' => 'Id Tipo Certificado',
            'nombre_tipo_certificado' => 'Nombre Tipo Certificado',
        ];
    }
    public function getResoluciones()
    {
        return $this->hasMany(Resoluciones::className(), ['id_tipo_certificado' => 'id_tipo_certificado']);
    }
}

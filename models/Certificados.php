<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%certificados}}".
 *
 * @property int $id_certificado
 * @property int $ano_certificado
 * @property string $fecha_creacion
 * @property int $id_tipo_certificado
 * @property int $id_radicado
 * @property int $id_entidad
 */
class Certificados extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificados}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ano_certificado', 'fecha_creacion', 'id_tipo_certificado', 'id_radicado'], 'required'],
            [['ano_certificado', 'id_tipo_certificado', 'id_radicado', 'id_entidad','id_entidad_camara'], 'integer'],
            [['fecha_creacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_certificado' => 'Id Certificado',
            'ano_certificado' => 'Ano Certificado',
            'fecha_creacion' => 'Fecha Creacion',
            'id_tipo_certificado' => 'Id Tipo Certificado',
            'id_radicado' => 'Id Radicado',
            'id_entidad' => 'Id Entidad',
            'id_entidad_camara' => 'Id Entidad Cámara'
           
        ];
    }
}

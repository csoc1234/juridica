<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%entidad_camara_comercio}}".
 *
 * @property int $id_entidad_camara
 * @property int $id_municipio
 * @property int $id_municipio_camara
 * @property string $nombre_entidad_camara
 * @property string $direccion_entidad
 * @property string $id_municipio_camara_comercio
 * @property string $nit_entidad
 * @property string $nombre_representante_entidad
 * @property string $cedula_representante
 */
class EntidadCamaraComercio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%entidad_camara_comercio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'id_municipio',  'id_municipio_camara', 'nombre_entidad_camara', 'direccion_entidad', 'nombre_representante', 'cedula_representante', 'nit_entidad'], 'required'],
            [[ 'id_municipio',  'id_municipio_camara', 'cedula_representante', 'nit_entidad'], 'integer'],
            [['nombre_entidad_camara', 'direccion_entidad', 'nombre_representante'], 'string', 'max' => 255],
            [['nombre_entidad_camara'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_entidad_camara' => 'Id Entidad Camara',
            'id_municipio' => 'Municipio de la entidad',
            'id_municipio_camara' => 'Municipio de la Camara',
            'nombre_entidad_camara' => 'Nombre de la Entidad',
            'direccion_entidad' => 'Direccion de la Entidad',
            'nombre_representante' => 'Nombre del Representante legal',
            'cedula_representante' => 'Cedula',
            'nit_entidad' => 'NIT',
        ];
    }

    public function Municipios()
    {
        $data = Municipios::findOne($this->id_municipio);
        $car = $data['municipio'];
        return $car;
    }
}

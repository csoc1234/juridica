<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%entidad_camara_comercio}}".
 *
 * @property string $id_entidad_camara_comercio
 * @property string $nombre_entidad
 * @property int $id_municipio_entidad
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
            [['nombre_entidad', 'id_municipio_entidad', 'direccion_entidad', 'id_municipio_camara_comercio', 'nit_entidad', 'nombre_representante_entidad', 'cedula_representante'], 'required'],
            [['id_municipio_entidad', 'id_municipio_camara_comercio'], 'integer'],
            [['nombre_entidad', 'direccion_entidad'], 'string', 'max' => 200],
            [['nit_entidad'], 'string', 'max' => 25],
            [['nombre_representante_entidad'], 'string', 'max' => 100],
            [['cedula_representante'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_entidad_camara_comercio' => 'Id Entidad Camara Comercio',
            'nombre_entidad' => 'Nombre Entidad',
            'id_municipio_entidad' => 'Municipio Entidad',
            'direccion_entidad' => 'Direccion Entidad',
            'id_municipio_camara_comercio' => 'Municipio Camara Comercio',
            'nit_entidad' => 'Nit Entidad',
            'nombre_representante_entidad' => 'Nombre Representante Legal',
            'cedula_representante' => 'Cedula Representante Legal',
        ];
    }
}

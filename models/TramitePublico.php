<?php

namespace app\models;

use Yii;

class TramitePublico extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tramite_publico}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_tramitePublico', 'dirigido_tramitePublico', 'nombre_solicitante_tramitePublico', 'cedula_tramitePublico', 'direccion_tramitePublico', 'telefono_tramitePublico', 'email_tramitePublico', 'nombre_entidad_tramitePublico', 'direccion_entidad_tramitePublico', 'telefono_entidad_tramitePublico', 'email_entidad_tramitePublico', 'nombre_represeLegal_tramitePublico', 'motivo_solicitud_tramitePublico', 'clase_solicitud_tramitePublico', 'tipocertificado_tramite_publico', 'cantidad_tipocert_tramite_publico'], 'required'],
            [['fecha_tramitePublico'], 'safe'],
            [['cedula_tramitePublico', 'telefono_tramitePublico', 'telefono_entidad_tramitePublico', 'cantidad_tipocert_tramite_publico'], 'integer'],
            [['dirigido_tramitePublico', 'nombre_solicitante_tramitePublico', 'direccion_tramitePublico', 'email_tramitePublico', 'nombre_entidad_tramitePublico', 'direccion_entidad_tramitePublico', 'email_entidad_tramitePublico', 'nombre_represeLegal_tramitePublico', 'motivo_solicitud_tramitePublico','otrosMotivoCert_tramite_publico', 'clase_solicitud_tramitePublico', 'tipocertificado_tramite_publico'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tramite_publico' => 'Id Tramite Publico',
            'fecha_tramitePublico' => 'Fecha Tramite Publico',
            'dirigido_tramitePublico' => 'Dirigido A',
            'nombre_solicitante_tramitePublico' => 'Nombre Solicitante',
            'cedula_tramitePublico' => 'Cedula del Solicitante',
            'direccion_tramitePublico' => 'Direccion del Solicitante',
            'telefono_tramitePublico' => 'Telefono del Solicitante',
            'email_tramitePublico' => 'Email del Solicitante',
            'nombre_entidad_tramitePublico' => 'Nombre de la entidad a certificar',
            'direccion_entidad_tramitePublico' => 'Direccion Entidad',
            'telefono_entidad_tramitePublico' => 'Telefono Entidad',
            'email_entidad_tramitePublico' => 'Email Entidad',
            'nombre_represeLegal_tramitePublico' => 'Nombre Representante Legal',
            'motivo_solicitud_tramitePublico' => 'Motivo Solicitud',
            'otrosMotivoCert_tramite_publico' => '¿Cuál?',
            'clase_solicitud_tramitePublico' => 'Clase Solicitud',
            'tipocertificado_tramite_publico' => 'Tipo certificado', 
            'cantidad_tipocert_tramite_publico' => 'Cantidad'
            
        ];
    }
}

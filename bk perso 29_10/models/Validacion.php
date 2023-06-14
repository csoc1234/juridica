<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "registro_validacion".
 *
 * @property int $id_validacion
 * @property int $id_radicado
 * @property int $estado
 * @property int $IDT_tramite
 * @property string $fecha_creacion
 * @property string $fecha_sistem
 * @property string $codigo_h
 * @property string $codigo_cons
 * @property resource $archivo
 *
 * @property Radicados $radicado
 * @property Radicados $fechaCreacion
 * @property Radicados $estado0
 * @property Radicados $tTramite
 */
class Validacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registro_validacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_radicado', 'estado', 'IDT_tramite', 'fecha_creacion','numero_resolucion','fecha_resolucion'], 'required'],
            [['id_radicado', 'estado', 'IDT_tramite','numero_resolucion'], 'integer'],
            [['fecha_creacion', 'fecha_sistem','fecha_resolucion'], 'safe'],
            [['archivo'], 'file','skipOnEmpty' => true,'extensions' => 'pdf', 'maxSize'=> '10485760'],
            [['codigo_h', 'codigo_cons'], 'string', 'max' => 255],
            [['id_radicado'], 'exist', 'skipOnError' => true, 'targetClass' => Radicados::className(), 'targetAttribute' => ['id_radicado' => 'id_radicado']],
            [['fecha_creacion'], 'exist', 'skipOnError' => true, 'targetClass' => Radicados::className(), 'targetAttribute' => ['fecha_creacion' => 'fecha_creacion']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Radicados::className(), 'targetAttribute' => ['estado' => 'estado']],
            [['IDT_tramite'], 'exist', 'skipOnError' => true, 'targetClass' => Radicados::className(), 'targetAttribute' => ['IDT_tramite' => 'id_tipo_tramite']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_validacion' => 'Id Validacion',
            'id_radicado' => 'N° de Radicado',
            'estado' => 'Estado',
            'IDT_tramite' => 'Tipo de Tramite',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_sistem' => 'Fecha Sistem',
            'codigo_h' => 'Codigo de seguridad',
            'codigo_cons' => 'Codigo consulta',
            'archivo' => 'Archivo',
            'numero_resolucion' => 'N° de resolucion',
            'fecha_resolucion' => 'Fecha de la resolucion',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRadicado()
    {
        return $this->hasOne(Radicados::className(), ['id_radicado' => 'id_radicado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFechaCreacion()
    {
        return $this->hasOne(Radicados::className(), ['fecha_creacion' => 'fecha_creacion']);
    }
    
    public function getTipoTramite(){
        $tramite = TipoTramite::findOne($this->IDT_tramite);
        return $tramite['descripcion'];
      }
  

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(Radicados::className(), ['estado' => 'estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTramite()
    {
        return $this->hasOne(Radicados::className(), ['id_tipo_tramite' => 'IDT_tramite']);
    }
}

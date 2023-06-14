<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resoluciones".
 *
 * @property integer $id_resolucion
 * @property integer $ano_resolucion
 * @property integer $numero_resolucion
 * @property string $fecha_creacion
 * @property integer $id_tipo_resolucion
 * @property string $nombre_entidad
 * @property integer $id_entidad
 * @property integer $id_historial
 *
 * @property Historial $idHistorial
 * @property Entidades $idEntidad
 * @property TipoResolucion $idTipoResolucion
 */
class Resoluciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resoluciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ano_resolucion', 'numero_resolucion', 'fecha_creacion', 'id_tipo_resolucion', 'nombre_entidad', 'id_entidad', 'id_historial'], 'required'],
            [['ano_resolucion', 'numero_resolucion', 'id_tipo_resolucion', 'id_entidad', 'id_historial','id_radicado'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['nombre_entidad'], 'string'],
            [['id_historial'], 'exist', 'skipOnError' => true, 'targetClass' => Historial::className(), 'targetAttribute' => ['id_historial' => 'id_historial']],
            [['id_entidad'], 'exist', 'skipOnError' => true, 'targetClass' => Entidades::className(), 'targetAttribute' => ['id_entidad' => 'id_entidad']],
            [['id_tipo_resolucion'], 'exist', 'skipOnError' => true, 'targetClass' => TipoResolucion::className(), 'targetAttribute' => ['id_tipo_resolucion' => 'id_tipo_resolucion']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_resolucion' => 'Id Resolucion',
            'ano_resolucion' => 'Año Resolucion',
            'numero_resolucion' => 'Numero Resolucion',
            'fecha_creacion' => 'Fecha Creacion',
            'id_tipo_resolucion' => 'Tipo Resolucion',
            'nombre_entidad' => 'Nombre Entidad',
            'id_entidad' => 'Entidad',
            'id_historial' => 'Historial',
            'id_radicado'=> 'Radicado N°'
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdHistorial()
    {
        return $this->hasOne(Historial::className(), ['id_historial' => 'id_historial']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEntidad()
    {
        return $this->hasOne(Entidades::className(), ['id_entidad' => 'id_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoResolucion()
    {
        return $this->hasOne(TipoResolucion::className(), ['id_tipo_resolucion' => 'id_tipo_resolucion']);
    }
}

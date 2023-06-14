<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historial".
 *
 * @property integer $id_historial
 * @property string $nombre_evento
 * @property integer $id_tabla_modificada
 * @property string $fecha_modificacion
 * @property string $nombre_campo_modificado
 * @property string $valor_anterior_campo
 * @property string $valor_nuevo_campo
 * @property integer $id_usuario_modifica
 * @property string $tabla_modificada
 *
 * @property User $idUsuarioModifica
 * @property Resoluciones[] $resoluciones
 */
class Historial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'historial';
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['nombre_evento', 'fecha_modificacion', 'id_usuario_modifica', 'tabla_modificada'], 'required'],
            [['id_tabla_modificada', 'id_usuario_modifica','id_resolucion'], 'integer'],
            [['fecha_modificacion'], 'safe'],
            [['valor_anterior_campo', 'valor_nuevo_campo'], 'string'],
            [['nombre_evento', 'nombre_campo_modificado'], 'string', 'max' => 500],
            [['tabla_modificada'], 'string', 'max' => 20],
            [['id_usuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_usuario_modifica' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_historial' => 'Id Historial',
            'nombre_evento' => 'Nombre Evento',
            'id_tabla_modificada' => 'Id Tabla Modificada',
            'fecha_modificacion' => 'Fecha Modificacion',
            'nombre_campo_modificado' => 'Nombre Campo Modificado',
            'valor_anterior_campo' => 'Valor Anterior Campo',
            'valor_nuevo_campo' => 'Valor Nuevo Campo',
            'id_usuario_modifica' => 'Id Usuario Modifica',
            'tabla_modificada' => 'Tabla Modificada',
            'id_resolucion' => ''
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuarioModifica()
    {
        return $this->hasOne(User::className(), ['id' => 'id_usuario_modifica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResoluciones()
    {
        return $this->hasMany(Resoluciones::className(), ['id_historial' => 'id_historial']);
    }

    public function user(){
      $data = User::findOne($this->id_usuario_modifica);
      return  $data['nombre_funcionario'];
    }
}

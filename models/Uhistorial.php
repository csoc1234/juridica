<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uhistorial".
 *
 * @property string $id_Uhistorial
 * @property string $U_id_usuario_modifica
 * @property string $U_fecha_modificacion
 * @property string $U_nombre_eliminado
 * @property string $U_nombre_usuario_modifica
 */
class Uhistorial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uhistorial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['U_id_usuario_modifica', 'U_fecha_modificacion', 'U_nombre_eliminado', 'U_nombre_usuario_modifica'], 'required'],
            [['U_id_usuario_modifica'], 'integer'],
            [['U_fecha_modificacion'], 'safe'],
            [['U_nombre_eliminado', 'U_nombre_usuario_modifica'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_Uhistorial' => 'Id Uhistorial',
            'U_id_usuario_modifica' => 'ID Usuario elimina',
            'U_fecha_modificacion' => 'Fecha Modificacion',
            'U_nombre_eliminado' => 'Nombre Eliminado',
            'U_nombre_usuario_modifica' => 'Usuario elimina',
        ];
    }
    public function getIdUsuarioModifica()
    {
        return $this->hasOne(User::className(), ['id' => 'id_usuario_modifica']);
    }

    public function user(){
      $data = User::findOne($this->U_id_usuario_modifica);
      return  $data['nombre_funcionario'];
    }
}

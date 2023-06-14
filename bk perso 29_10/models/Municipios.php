<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "municipios".
 *
 * @property integer $id_municipio
 * @property string $municipio
 * @property integer $estado
 * @property integer $departamento_id
 *
 * @property Departamentos $departamentos
 * @property Dignatarios[] $dignatarios
 * @property Entidades[] $entidades
 */
class Municipios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'municipios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estado', 'departamento_id'], 'required'],
            [['estado', 'departamento_id'], 'integer'],
            [['municipio'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_municipio' => Yii::t('app', 'Id Municipio'),
            'municipio' => Yii::t('app', 'Municipio'),
            'estado' => Yii::t('app', 'Estado'),
            'departamento_id' => Yii::t('app', 'Departamento ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamentos()
    {
        return $this->hasOne(Departamentos::className(), ['id_departamento' => 'departamento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDignatarios()
    {
        return $this->hasMany(Dignatarios::className(), ['id_municipio_expedicion' => 'id_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidades()
    {
        return $this->hasMany(Entidades::className(), ['municipio_entidad' => 'id_municipio']);
    }

    public static function getNombreDepartamento($id){
      $departamento = Departamentos::findOne($id);
      return  $departamento['departamento'];
    }
}

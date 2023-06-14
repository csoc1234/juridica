<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clase_entidad".
 *
 * @property integer $id_clase_entidad
 * @property string $clase_entidad
 *
 * @property Entidades[] $entidades
 */
class ClaseEntidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clase_entidad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clase_entidad'], 'required'],
            [['clase_entidad'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_clase_entidad' => Yii::t('app', 'Id Clase Entidad'),
            'clase_entidad' => Yii::t('app', 'Clase Entidad'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidades()
    {
        return $this->hasMany(Entidades::className(), ['id_clase_entidad' => 'id_clase_entidad']);
    }
}

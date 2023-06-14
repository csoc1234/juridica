<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%clasesolic_tramitepublico}}".
 *
 * @property int $id_clase_tramite_publico
 * @property string $nombreClase_tramite_publico
 */
class ClaseSolicTramitePublico extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clasesolic_tramitepublico}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombreClase_tramite_publico'], 'required'],
            [['nombreClase_tramite_publico'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_clase_tramite_publico' => 'Id Clase Tramite Publico',
            'nombreClase_tramite_publico' => 'Nombre Clase Tramite Publico',
        ];
    }
}

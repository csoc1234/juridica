<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipomotivosolic_tramitepublico}}".
 *
 * @property int $id_motivo_tramite_publico
 * @property string $nombreMotivo_tramite_publico
 */
class TipomotivosolicTramitepublico extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipomotivosolic_tramitepublico}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombreMotivo_tramite_publico'], 'required'],
            [['nombreMotivo_tramite_publico'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_motivo_tramite_publico' => 'Id Motivo Tramite Publico',
            'nombreMotivo_tramite_publico' => 'Nombre Motivo Tramite Publico',
        ];
    }
}

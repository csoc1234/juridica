<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tramite_devolucion}}".
 *
 * @property string $id_tramite_devolucion
 * @property string $id_radicado
 */
class TramiteDevolucion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tramite_devolucion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_radicado'], 'required'],
            [['id_radicado','id_entidad'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tramite_devolucion' => '',
            'id_radicado' => '',
            'id_entidad' => '',
        ];
    }
}

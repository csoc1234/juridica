<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "precios_tramites".
 *
 * @property integer $id_precio_tramite
 * @property string $tramite
 * @property integer $precio
 */
class PreciosTramites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'precios_tramites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tramite', 'precio'], 'required'],
            [['tramite'], 'string'],
            [['precio'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_precio_tramite' => 'Id Precio Tramite',
            'tramite' => 'Tramite',
            'precio' => 'Precio',
        ];
    }
}

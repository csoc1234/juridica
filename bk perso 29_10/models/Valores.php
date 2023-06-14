<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "valores".
 *
 * @property integer $id_valores
 * @property string $Descripcion_valor
 * @property double $valor
 */
class Valores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'valores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Descripcion_valor', 'valor'], 'required'],
            [['valor'], 'number'],
            [['Descripcion_valor'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_valores' => 'Id Valores',
            'Descripcion_valor' => 'Descripcion Valor',
            'valor' => 'Valor',
        ];
    }
}

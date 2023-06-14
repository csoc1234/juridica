<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%privilegio}}".
 *
 * @property int $id_privilegio
 * @property string $nombre_privilegio
 */
class Privilegio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%privilegio}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_privilegio'], 'required'],
            [['nombre_privilegio'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_privilegio' => 'Id Privilegio',
            'nombre_privilegio' => 'Nombre Privilegio',
        ];
    }
}

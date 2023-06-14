<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tipocert_tramitepublico}}".
 *
 * @property int $id_tipocertificado_tramite_publico
 * @property string $nombreCert_tramite_publico
 */
class TipocertTramitepublico extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tipocert_tramitepublico}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombreCert_tramite_publico'], 'required'],
            [['nombreCert_tramite_publico'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipocertificado_tramite_publico' => 'Id Tipocertificado Tramite Publico',
            'nombreCert_tramite_publico' => 'Nombre Cert Tramite Publico',
        ];
    }
}

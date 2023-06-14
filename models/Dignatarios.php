<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dignatarios".
 *
 * @property integer $id_dignatario
 * @property string $cedula_dignatario
 * @property string $nombre_dignatario
 * @property boolean $estado
 * @property integer $id_municipio_expedicion
 * @property string $fecha_ingreso
 * @property integer $id_entidad
 * @property integer $id_cargo
 * @property integer $id_grupo_cargos
 * @property string $tarjeta_profesiona
 * @property string $inicio_periodo
 * @property string $fin_periodo
 *
 * @property GruposCargos $idGrupoCargos
 * @property Cargos $idCargo
 * @property Entidades $idEntidad
 * @property Municipios $idMunicipioExpedicion
 */
class Dignatarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dignatarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cedula_dignatario', 'nombre_dignatario', 'fecha_ingreso', 'id_entidad', 'inicio_periodo', 'fin_periodo'], 'required'],
            [['estado'], 'boolean'],
          //  [['cedula_dignatario'], 'unique'],
            [['id_municipio_expedicion', 'id_entidad', 'id_cargo', 'id_grupo_cargos','representante_legal'], 'integer'],
            [['fecha_ingreso', 'inicio_periodo','fin_periodo'], 'safe'],
            [['cedula_dignatario'], 'string', 'max' => 20],
            [['nombre_dignatario', 'tarjeta_profesiona'], 'string', 'max' => 100],
            [['id_grupo_cargos'], 'exist', 'skipOnError' => true, 'targetClass' => GruposCargos::className(), 'targetAttribute' => ['id_grupo_cargos' => 'id_grupo_cargos']],
            [['id_cargo'], 'exist', 'skipOnError' => true, 'targetClass' => Cargos::className(), 'targetAttribute' => ['id_cargo' => 'id_cargo']],
            [['id_entidad'], 'exist', 'skipOnError' => true, 'targetClass' => Entidades::className(), 'targetAttribute' => ['id_entidad' => 'id_entidad']],
            [['id_municipio_expedicion'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['id_municipio_expedicion' => 'id_municipio']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_dignatario' => 'Id Dignatario',
            'cedula_dignatario' => 'Cedula Dignatario',
            'nombre_dignatario' => 'Nombre Dignatario',
            'estado' => 'Estado',
            'id_municipio_expedicion' => 'Lugar Expedicion Cedula (Opcional)',
            'fecha_ingreso' => 'Fecha Ingreso',
            'id_entidad' => 'Entidad',
            'id_cargo' => 'Cargo',
            'id_grupo_cargos' => 'Organos de Administración',
            'tarjeta_profesiona' => 'N° Tarjeta Profesional (Opcional)',
            'inicio_periodo' => 'Fecha inicio periodo',
            'fin_periodo' => 'Fecha fin periodo',
            'representante_legal' => '¿Es el representante legal de la entidad?',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGrupoCargos()
    {
        return $this->hasOne(GruposCargos::className(), ['id_grupo_cargos' => 'id_grupo_cargos']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCargo()
    {
        return $this->hasOne(Cargos::className(), ['id_cargo' => 'id_cargo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEntidad()
    {
        return $this->hasOne(Entidades::className(), ['id_entidad' => 'id_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMunicipioExpedicion()
    {
        return $this->hasOne(Municipios::className(), ['id_municipio' => 'id_municipio_expedicion']);
    }

    public function NombreCargo()
    {
        $data = Cargos::findOne($this->id_cargo);
        $car = $data['nombre_cargo'];
        return $car;
    }

    public function NombreGrupoCargo()
    {
        $data = GruposCargos::findOne($this->id_grupo_cargos);
        $car = $data['nombre_grupo_cargo'];
        return $car;
    }
}

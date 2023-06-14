<?php

namespace app\models;
use yii\web\UploadedFile;
use Yii;
use Carbon\Carbon;
/**
 * This is the model class for table "entidades".
 *
 * @property integer $id_entidad
 * @property integer $personeria_year
 * @property integer $personeria_n
 * @property string $nombre_entidad
 * @property string $fecha_reconocimiento
 * @property integer $municipio_entidad
 * @property string $direccion_entidad
 * @property string $telefono_entidad
 * @property string $email_entidad
 * @property integer $id_tipo_entidad
 * @property integer $id_clase_entidad
 * @property string $ubicacion_archivos_entidad
 * @property resource $datos_digitales
 * @property integer $estado_entidad
 *
 * @property Dignatarios[] $dignatarios
 * @property TipoEntidad $idTipoEntidad
 * @property ClaseEntidad $idClaseEntidad
 * @property Municipios $municipioEntidad
 * @property Resoluciones[] $resoluciones
 */
class Entidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entidades';
    }
     public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personeria_year', 'nombre_entidad', 'telefono_entidad', 'id_tipo_entidad', 'id_clase_entidad', 'municipio_entidad', 'estado_entidad','fecha_estatutos'], 'required'],
            [['personeria_year', 'personeria_n', 'municipio_entidad', 'id_tipo_entidad', 'id_clase_entidad', 'estado_entidad', 'id_tipoRegimen'], 'integer'],
            [['fecha_reconocimiento','fecha_estatutos'], 'safe'],
            ['email_entidad','email'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'doc,pdf,docx', 'maxSize'=> '10485760'], //10485760 = 10 * 1024 * 1024
            [['datos_digitales','observaciones'], 'string'],
            [['nombre_entidad', 'direccion_entidad'], 'string', 'max' => 500, 'min' => 4],
            [['nombre_entidad'], 'unique'],
            [['personeria_n'], 'validar'],
            [['telefono_entidad', 'ubicacion_archivos_entidad'], 'string', 'max' => 20],
            [['id_tipo_entidad'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEntidad::className(), 'targetAttribute' => ['id_tipo_entidad' => 'id_tipo_entidad']],
            [['id_clase_entidad'], 'exist', 'skipOnError' => true, 'targetClass' => ClaseEntidad::className(), 'targetAttribute' => ['id_clase_entidad' => 'id_clase_entidad']],
            [['municipio_entidad'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['municipio_entidad' => 'id_municipio']],
        ];
    }

    public function validar($attribute, $params){
        if(isset(Yii::$app->user->identity->id)){
          $fecha1 = Carbon::now();
          $fecha1= $fecha1->year;
          $entidad =  Entidades::find()->where(['and',['personeria_year'=>$fecha1],['personeria_n'=>$this->personeria_n]])->one();
          $enti = Entidades::findOne($this->id_entidad);
		if($enti && $this->id_entidad == $enti->id_entidad){
		}else{ 	
		 if(isset($entidad->id_entidad) && $entidad->id_entidad > 0){
                	$this->addError($attribute, 'N° personeria repetida en este año');
            		}
		}
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_entidad' => 'ID',
            'personeria_year' => 'Año Personeria',
            'personeria_n' => 'Número Personeria (Opcional)',
            'nombre_entidad' => 'Nombre Entidad',
            'fecha_reconocimiento' => 'Fecha de personeria (Opcional)',
            'municipio_entidad' => 'Municipio Entidad',
            'direccion_entidad' => 'Direccion Entidad',
            'telefono_entidad' => 'Telefono Entidad',
            'email_entidad' => 'Email Entidad',
            'id_tipo_entidad' => 'Tipo Entidad',
            'id_clase_entidad' => 'Clase Entidad',
            'ubicacion_archivos_entidad' => 'Ubicacion Archivos Entidad (Sugerido)',
            'datos_digitales' => 'Datos Digitales',
            'file' => 'archivo',
            'estado_entidad' => 'Estado Entidad',
            'id_tipoRegimen' => 'Tipo Régimen (Opcional)',
            'fecha_estatutos' => 'Fecha Estatutos',
            'observaciones' => 'Observaciones'
           
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDignatarios()
    {
        return $this->hasMany(Dignatarios::className(), ['id_entidad' => 'id_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoEntidad()
    {
        return $this->hasOne(TipoEntidad::className(), ['id_tipo_entidad' => 'id_tipo_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClaseEntidad()
    {
        return $this->hasOne(ClaseEntidad::className(), ['id_clase_entidad' => 'id_clase_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMunicipioEntidad()
    {
        return $this->hasOne(Municipios::className(), ['id_municipio' => 'municipio_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResoluciones()
    {
        return $this->hasMany(Resoluciones::className(), ['id_entidad' => 'id_entidad']);
    }

    public function NombreTipoEntidad()
    {
        $data = TipoEntidad::findOne($this->id_tipo_entidad);

        return $data['tipo_entidad'];
    }

    public function NombreClaseEntidad()
    {
      $data = ClaseEntidad::findOne($this->id_clase_entidad);
      return $data['clase_entidad'];
    }

    public function getDatosdigitales(){
      return $this->datos_digitales;
    }
}

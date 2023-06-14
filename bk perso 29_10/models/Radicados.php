<?php

namespace app\models;
use yii\web\UploadedFile;
//use app\models\MotivoCertificado;
use Yii;

/**
 * This is the model class for table "radicados".
 *
 * @property int $id_radicado
 * @property string $descripcion
 * @property int $id_tipo_tramite
 * @property int $estado
 * @property int $id_entidad
 * @property int $id_usuario_tramita
 * @property int $id_usuario_creacion
 * @property int $id_motivo
 * @property int $sade
 * @property string $fecha_creacion
 *
 * @property TipoTramite $tipoTramite
 * @property Entidades $entidadRadicado
 * @property User $usuarioCreacion
 * @property User $usuarioTramita
 */
class Radicados extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'radicados';
    }
    public $file;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion', 'id_tipo_tramite', 'estado', 'id_usuario_creacion', 'id_motivo', 'sade', 'fecha_creacion'], 'required'],
            [['descripcion','n_radicado_interno','id_tipo_resolucion','id_tipo_certificado', 'id_tipo_resolucion_combinada','id_tipo_reforma_estatutaria','id_tipo_registro_libro','cantidad_libros'], 'string'],
            [['id_tipo_tramite', 'estado', 'id_entidad', 'id_usuario_tramita', 'id_usuario_creacion', 'id_motivo', 'sade','id_dignatario_tramite','id_entidadcg','id_entidad_camara'], 'integer'],
            [['fecha_creacion','fecha_gaceta'], 'safe'],
            [['n_radicado_interno'], 'string', 'max' => 100],
            [['ubicacion'], 'string', 'max' => 100],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'doc,pdf,docx','maxFiles' => 5, 'maxSize'=> '10485760'], //10485760 = 10 * 1024 * 1024
            [['id_tipo_tramite'], 'exist', 'skipOnError' => true, 'targetClass' => TipoTramite::className(), 'targetAttribute' => ['id_tipo_tramite' => 'id_tipo_tramite']],
            [['id_entidad'], 'exist', 'skipOnError' => true, 'targetClass' => Entidades::className(), 'targetAttribute' => ['id_entidad' => 'id_entidad']],
            [['id_usuario_creacion'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_usuario_creacion' => 'id']],
            [['id_usuario_tramita'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_usuario_tramita' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
     public function attributeLabels()
     {
         return [
             'id_radicado' => 'N°Radicado',
             'descripcion' => 'Descripcion',
             'id_tipo_tramite' => 'Tipo de Trámite',
             'estado' => 'Estado',
             'id_entidad' => 'Entidad',
             'id_usuario_tramita' => 'Usuario asignado para tramitarlo',
             'id_usuario_creacion' => 'Usuario que creo el radicado',
             'sade' => 'Sade',
             'fecha_creacion' => 'Fecha y Hora de creación',
             'file' => 'Archivo',
             'id_motivo' => 'Motivo de uso del Certificado',
             'n_radicado_interno' => 'N°Interno',
             'id_tipo_resolucion'  => 'Tipo Resolucion',
             'id_tipo_certificado'  => 'Tipo Certificado',
             'id_tipo_resolucion_combinada' => 'Seleccione los tipos de resolución',
             'id_dignatario_tramite' => 'Dignatario Tramite',
             'id_tipo_reforma_estatutaria' => 'Seleccione los tipos de reforma a tramitar',
             'id_tipo_registro_libro' => 'Seleccione los tipos de libros a registrar',
             'id_entidadcg' => 'Seleccione el tipo de entidad',
             'id_entidad_camara'=> 'Entidad Cámara de Comercio',
             'fecha_gaceta' => 'Fecha gaceta'

         ];
     }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidadRadicado()
    {
        return $this->hasOne(Entidades::className(), ['id_entidad' => 'id_entidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCreacion()
    {
        return $this->hasOne(User::className(), ['id' => 'id_usuario_creacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioTramita()
    {
        return $this->hasOne(User::className(), ['id' => 'id_usuario_tramita']);
    }


        public function getIdTipoTramite()
        {
            return $this->hasOne(TipoTramite::className(), ['id_tipo_tramite' => 'id_tipo_tramite']);
        }

        public function getResoluciones()
    {
        return $this->hasMany(Resoluciones::className(), ['id_tipo_resolucion' => 'id_tipo_resolucion']);
    }

        public function getTipoTramite(){
          $tramite = TipoTramite::findOne($this->id_tipo_tramite);
          return $tramite['descripcion'];
        }

        
        public function getUser(){
          $user = User::findOne($this->id_usuario_tramita);
          return $user['nombre_funcionario'];
        }
        public function getUserr(){
          $user = User::findOne($this->id_usuario_creacion);
          return $user['nombre_funcionario'];
        }

        public function getMotivo(){
          $motivo = MotivoCertificado::findOne($this->id_motivo);
          return $motivo['descripcion_motivo'];
        }

        public function getEntidad(){
          $entidad = Entidades::findOne($this->id_entidad);
          $resultado = $entidad['nombre_entidad'].' - '.$entidad['personeria_year'].'-'.$entidad['personeria_n'];
          return $resultado;
        }
}

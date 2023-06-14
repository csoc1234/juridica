<?php

namespace app\controllers;

use Yii;
use app\models\Entidades;
use app\models\EntidadesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Carbon\Carbon;
use app\models\User;
use app\models\Historial;
use yii\filters\AccessControl;
use app\models\Dignatarios;
use app\models\DignatariosSearch;
use app\models\Resoluciones;
use yii\web\UploadedFile;
use app\models\Radicados;
use app\models\TipoEntidad;
use app\models\ClaseEntidad;
use Html;
use yii\web\Response;
use yii\widgets\ActiveForm;
use DateTime;
use mPDF;
use yii\data\ActiveDataProvider;
/**
 * EntidadesController implements the CRUD actions for Entidades model.
 */
class EntidadesController extends Controller
{

    /**
     * @inheritdoc
     */
     public function behaviors()
   {
   //Aqui se agregan los sitios que tendran restricción de acceso
   $only = [ 'create', 'update'];
   return [
         'access' => [
             'class' => AccessControl::className(),
             'only' => $only,
             'rules' => [
                 [
                     'actions' => [ 'create', 'update', 'metodoexcel'],
                     'allow' => true,
                     'roles' => ['@'],
         'matchCallback' => function ($rule, $action) {
           $valid_roles = [User::ROL_USER,User::ROL_SUPERUSER];
           return User::roleInArray($valid_roles) && User::isActive();
                   }
                 ],
             ],
         ],
   //End sitios

         'verbs' => [
             'class' => VerbFilter::className(),
             'actions' => [
                 'delete' => ['POST'],
             ],
         ],
     ];
   }

    /**
     * Lists all Entidades models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EntidadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['id_entidad'=>SORT_DESC]);
        $session = Yii::$app->session;
        $session->set('editar',false);
        $session->set('id_radicado',null);
        $session->set('id_entidad',null);
        $session->set('query', $dataProvider->query);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDignatario($id)
    {
        $this->redirect(Yii::$app->request->baseUrl."?r=dignatarios%2Findex1&id=".$id);
    }

    public function crearDignatario()
    {
        $this->redirect(Yii::$app->request->baseUrl."?r=dignatarios%2Fcreate");
    }

    public function actionHistorial($id)
    {
        $this->redirect(Yii::$app->request->baseUrl."?r=historial%2Findex1&id=".$id);
    }
    /**
     * Displays a single Entidades model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $session = Yii::$app->session;
        $session->set('id_entidad',$id);
        //$session->set('id_radicado',null);
        $msg = $session->get('msg');
        $session->set('msg',null);
   
        return $this->render('view', [
            'model' => $this->findModel($id),
            'msg'=> $msg,
        ]);
    }

    public function actionRegistrolibros(){
      $model = new Entidades();
      return $this->render('registrolibros',[
            'model' => $model,
        ]);
    }
    /**
     * Creates a new Entidades model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate()
     {
       $session = Yii::$app->session;
       $idRadicado = $session->get('id_radicado');
       $radicado = Radicados::findOne($idRadicado);
       if((Yii::$app->user->identity->id_rol == 1 || Yii::$app->user->identity->id_rol == 2) && $radicado ){
         $model = new Entidades();
         $model->estado_entidad = 1;
         $tiempo = Carbon::now('America/Bogota');
         $año = $tiempo->year;
         //$año = 2018; // cambiar el año para probar si funciona lo del incrementable de las resoluciones
         $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
         $numero_resolucion = 1;


       if($ultima_resolucion['ano_resolucion'] == $año){
             $numero_resolucion = $ultima_resolucion['numero_resolucion'];
             $numero_resolucion = $numero_resolucion + 1;
             $model->personeria_year = $año;
             $model->personeria_n = $numero_resolucion;
         }else{
             $model->personeria_year = $año;
             $model->personeria_n = $numero_resolucion;
         }

       if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
           Yii::$app->response->format = 'json';
           return ActiveForm::validate($model);
         }


         if($radicado){
            if(Yii::$app->user->identity->id_rol == 1){
               $fecha1 = Carbon::now();
               $fecha1= new Datetime($fecha1->toDateString());
            }else{
                $fecha1 = new DateTime($radicado->fecha_creacion);
            }
         }
           if ($model->load(Yii::$app->request->post())  ) {
            if(Yii::$app->user->identity->id_rol == 1){
               $fecha1 = Carbon::now();
                $fecha1= new Datetime($fecha1->toDateString());
            }
             $fecha2 = new Datetime($model->fecha_reconocimiento);
             $fecha3 = new DateTime($model->fecha_estatutos);
             $interval1 = $fecha1->diff($fecha2); // la fecha de reconocimiento debe ser mayor o igual a la fecha de radicacion
             $interval2 = $fecha3->diff($fecha1); // la fecha de estatutos debe ser menor o igual a la fecha de radicacion
             $a = $interval1->format('%R%a');
             $b = $interval2->format('%R%a');
             if($a >= 0 && $b >= 0){
              $model->save();
             $model->file = UploadedFile::getInstance($model, 'file');

             if($model->file == null){
               $model->datos_digitales = "";

             }else{

               if (!file_exists($model->id_entidad)){
                 mkdir($model->id_entidad);
               }
               $model->file->saveAs($model->id_entidad.'/' . $model->file->baseName. '.' . $model->file->extension);
               //$model->datos_digitales = 'uploads/' . $model->nombre_entidad . '.' . $model->file->extension;
               $archivos = scandir($model->id_entidad);
               unset($archivos[0],$archivos[1]);
               $nombres = "";
               foreach ($archivos as $key => $value) {
                 $nombres = $nombres.$value."\n";
               }
               $model->datos_digitales =  $nombres;
             }

             $model->file = null;

           // Guarda en la tabla historial la creacion de la entidad
             $historial = new Historial();
             $tiempo = Carbon::now('America/Bogota');
             $historial->nombre_evento = "CREACIÓN DE ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);
             // crea la resolucion de reconimiento de personeria juridica para esta entidad
             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 1; // inserto el id correspondiente a reconocimiento de personería jurídica
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->id_radicado = $radicado->id_radicado;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->fecha_creacion = $tiempo;
             $resolucion->save(false);

             $session = Yii::$app->session;
             $session->set('id_entidad',$model->id_entidad);
             //return $this->redirect(['view', 'id' => $model->id_entidad]);
             return $this->crearDignatario();
           }else{
             return $this->render('create', [
                 'model' => $model,
                 'msg' => "LA FECHA DE RECONOCIMIENTO DEBE SER SUPERIOR A LA FECHA $radicado->fecha_creacion Y LA FECHA DE LOS ESTATUTOS DEBE SER INFERIOR A $radicado->fecha_creacion",
                 'update' => false,
             ]);
           }
         } else {
             return $this->render('create', [
                 'model' => $model,
                  'msg' => null,
                  'update'=> false,
             ]);
         }
       }else{
         $searchModel = new EntidadesSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->query->orderBy(['id_entidad'=>SORT_DESC]);
         $session = Yii::$app->session;
         $session->set('editar',false);
         return $this->render('index', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
             'msg' => "No puede realizar esta operación sin un radicado correspondiente",
         ]);

       }
     }

    /**
     * Updates an existing Entidades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
     public function actionUpdate($id)
 {
   $session = Yii::$app->session;
   $idRadicado = $session->get('id_radicado');
   $radicado = Radicados::findOne($idRadicado);
   $model = $this->findModel($id);
   if((Yii::$app->user->identity->id_rol == 1 && $radicado) || (Yii::$app->user->identity->id_rol == 2 && $radicado) ){
     //$model = $this->findModel($id);
     $oldModel = Entidades::findOne($id);
     $tiempo = Carbon::now('America/Bogota');
     $año = $tiempo->year;
     //$año = 2018; // cambiar el año para probar si funciona lo del incrementable de las resoluciones


     if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
       Yii::$app->response->format = Response::FORMAT_JSON;
       return ActiveForm::validate($model);
     }
      if ($model->load(Yii::$app->request->post()) &&  $model->save()) {
       $model->file = UploadedFile::getInstance($model, 'file');
       if($model->file == null){
         $model->datos_digitales = "";
       }else{

         if (!file_exists($model->id_entidad)){
           mkdir($model->id_entidad);
         }
         if ($model->file->saveAs($model->id_entidad.'/' . $model->file->baseName . '.' . $model->file->extension)){
             $historial = new Historial();
             $historial->nombre_evento = "SUBIDA DE ARCHIVO ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "datos_digitales";
             $historial->valor_nuevo_campo = $model->file->baseName;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

            
         }
         //$model->datos_digitales = 'uploads/' . $model->nombre_entidad . '.' . $model->file->extension;
         $archivos = scandir($model->id_entidad);
         unset($archivos[0],$archivos[1]);
         $nombres = "";
         foreach ($archivos as $key => $value) {
           $nombres = $nombres.$model->id_entidad.'/'.$value."\n";
         }
         $model->datos_digitales =  $nombres;
       }
       $model->file = null;//$model->save();

           if($model->nombre_entidad != $oldModel->nombre_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE NOMBRE ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "nombre_entidad";
             $historial->valor_anterior_campo = $oldModel->nombre_entidad;
             $historial->valor_nuevo_campo = $model->nombre_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


           if($ultima_resolucion['ano_resolucion'] == $año){
                 $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                 $numero_resolucion = $numero_resolucion + 1;
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }else{
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }

             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 2; // inserto el id correspondiente a cambio de razon social
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->id_radicado = $radicado->id_radicado;
             $resolucion->fecha_creacion = $tiempo->toDateString();
             $resolucion->save(false);
           }


           if ($model->fecha_reconocimiento != $oldModel->fecha_reconocimiento){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE FECHA RECONOCIMIENTO ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "fecha_reconocimiento";
             $historial->valor_anterior_campo = $oldModel->fecha_reconocimiento;
             $historial->valor_nuevo_campo = $model->fecha_reconocimiento;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);
           }

           if ($model->municipio_entidad != $oldModel->municipio_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE MUNICIPIO ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "municipio_entidad";
             $historial->valor_anterior_campo = $oldModel->municipio_entidad;
             $historial->valor_nuevo_campo = $model->municipio_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);


             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


             if($ultima_resolucion['ano_resolucion'] == $año){
                   $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                   $numero_resolucion = $numero_resolucion + 1;
                   $model->personeria_year = $año;
                   $model->personeria_n = $numero_resolucion;
               }else{
                   $model->personeria_year = $año;
                   $model->personeria_n = $numero_resolucion;
               }

               $resolucion = new Resoluciones();
               $resolucion->id_tipo_resolucion = 3; // inserto el id correspondiente a cambio de domicilio
               $resolucion->nombre_entidad = $model->nombre_entidad;
               $resolucion->id_entidad = $model->id_entidad;
               $resolucion->id_historial = $historial->id_historial;
               $resolucion->numero_resolucion = $numero_resolucion;
               $resolucion->ano_resolucion = $año;
               $resolucion->id_radicado = $radicado->id_radicado;
               $resolucion->fecha_creacion = $tiempo->toDateString();
               $resolucion->save(false);
           }

           if ($model->direccion_entidad != $oldModel->direccion_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE DIRECCIÓN ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "direccion_entidad";
             $historial->valor_anterior_campo = $oldModel->direccion_entidad;
             $historial->valor_nuevo_campo = $model->direccion_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


             if($ultima_resolucion['ano_resolucion'] == $año){
                 $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                 $numero_resolucion = $numero_resolucion + 1;
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }else{
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }

             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 3; // inserto el id correspondiente a cambio de domicilio
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->id_radicado = $radicado->id_radicado;
             $resolucion->fecha_creacion = $tiempo->toDateString();
             $resolucion->save(false);
           }

           if ($model->id_tipo_entidad != $oldModel->id_tipo_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE TIPO ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "id_tipo_entidad";
             $historial->valor_anterior_campo = $oldModel->id_tipo_entidad;
             $historial->valor_nuevo_campo = $model->id_tipo_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


             if($ultima_resolucion['ano_resolucion'] == $año){
                 $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                 $numero_resolucion = $numero_resolucion + 1;
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }else{
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }

             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 8; // inserto el id correspondiente a cambio de tipo
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->id_radicado = $radicado->id_radicado;
             $resolucion->fecha_creacion = $tiempo->toDateString();
             $resolucion->save(false);
           }


           if ($model->id_clase_entidad != $oldModel->id_clase_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE CLASE ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "id_clase_entidad";
             $historial->valor_anterior_campo = $oldModel->id_clase_entidad;
             $historial->valor_nuevo_campo = $model->id_clase_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


             if($ultima_resolucion['ano_resolucion'] == $año){
                 $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                 $numero_resolucion = $numero_resolucion + 1;
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }else{
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }

             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 7; // inserto el id correspondiente a cambio de clase
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->id_radicado = $radicado->id_radicado;
             $resolucion->fecha_creacion = $tiempo->toDateString();
             $resolucion->save(false);
           }

           if ($model->fecha_gaceta != $oldModel->fecha_gaceta){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE FECHA GACETA ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "fecha_gaceta";
             $historial->valor_anterior_campo = $oldModel->fecha_gaceta;
             $historial->valor_nuevo_campo = $model->fecha_gaceta;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);
           }

           if ($model->estado_entidad != $oldModel->estado_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE ESTADO ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "estado_entidad";
             $historial->valor_anterior_campo = $oldModel->estado_entidad;
             $historial->valor_nuevo_campo = $model->estado_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


             if($ultima_resolucion['ano_resolucion'] == $año){
                 $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                 $numero_resolucion = $numero_resolucion + 1;
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }else{
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }

             if($model->estado_entidad == 2){
             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 5; // inserto el id correspondiente a cancelacion de personería jurídica
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->fecha_creacion = $tiempo->toDateString();
             $resolucion->save(false);
             }
           }

           if ($model->objetivos_entidad != $oldModel->objetivos_entidad){
             $historial = new Historial();
             $historial->nombre_evento = "CAMBIO DE OBJETIVOS ENTIDAD";
             $historial->id_tabla_modificada = $model->id_entidad;
             $historial->fecha_modificacion = $tiempo->toDateTimeString();
             $historial->nombre_campo_modificado = "objetivos_entidad";
             $historial->valor_anterior_campo = $oldModel->objetivos_entidad;
             $historial->valor_nuevo_campo = $model->objetivos_entidad;
             $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
             $historial->tabla_modificada = "ENTIDADES";
             $historial->save(false);

             $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
             $numero_resolucion = 1;


             if($ultima_resolucion['ano_resolucion'] == $año){
                 $numero_resolucion = $ultima_resolucion['numero_resolucion'];
                 $numero_resolucion = $numero_resolucion + 1;
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }else{
                 $model->personeria_year = $año;
                 $model->personeria_n = $numero_resolucion;
             }

             $resolucion = new Resoluciones();
             $resolucion->id_tipo_resolucion = 6; // inserto el id correspondiente a cambio de objetivos
             $resolucion->nombre_entidad = $model->nombre_entidad;
             $resolucion->id_entidad = $model->id_entidad;
             $resolucion->id_historial = $historial->id_historial;
             $resolucion->numero_resolucion = $numero_resolucion;
             $resolucion->ano_resolucion = $año;
             $resolucion->id_radicado = $radicado->id_radicado;
             $resolucion->fecha_creacion = $tiempo->toDateString();
             $resolucion->save(false);
           }

         return $this->redirect(['view', 'id' => $model->id_entidad]);
     } else {
         return $this->render('update', [
             'model' => $model,
             'update'=> true,
             'file' => false,
         ]);
     }
   }else{

    return $this->render('update', [
             'model' => $model,
             'update'=> true,
             'file' => true,
         ]);
    /*
     $searchModel = new EntidadesSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     $dataProvider->query->orderBy(['id_entidad'=>SORT_DESC]);
     $session = Yii::$app->session;
     $session->set('editar',false);
     return $this->render('index', [
         'searchModel' => $searchModel,
         'dataProvider' => $dataProvider,
         'msg' => "No puede realizar esta operación sin un radicado correspondiente",
     ]);
    */
   }
 }


    /**
     * Finds the Entidades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Entidades the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Entidades::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function downloadFile($dir, $file, $extensions=[])
    {
     //Si el directorio existe
     if (is_dir($dir))
     {
      //Ruta absoluta del archivo
      $path = $dir.$file;

      //Si el archivo existe
      if (is_file($path))
      {
       //Obtener información del archivo
       $file_info = pathinfo($path);
       //Obtener la extensión del archivo
       $extension = $file_info["extension"];

       if (is_array($extensions))
       {
        //Si el argumento $extensions es un array
        //Comprobar las extensiones permitidas
        foreach($extensions as $e)
        {
         //Si la extension es correcta
         if ($e === $extension)
         {
          //Procedemos a descargar el archivo
          // Definir headers
          $size = filesize($path);
          header("Content-Type: application/force-download");
          header("Content-Disposition: attachment; filename=$file");
          header("Content-Transfer-Encoding: binary");
          header("Content-Length: " . $size);
          // Descargar archivo
          readfile($path);
          //Correcto
          return true;
         }
        }
       }

      }
     }
     //Ha ocurrido un error al descargar el archivo
     return false;
    }

    public function actionDownload()
    {
      $session = Yii::$app->session;
      $id = $session->get('id_entidad');
      $entidad = Entidades::findOne($id);
     if (Yii::$app->request->get("file"))
     {
      //Si el archivo no se ha podido descargar
      //downloadFile($dir, $file, $extensions=[])


        if (!$this->downloadFile($entidad['id_entidad']."/", Yii::$app->request->get('file'), ["pdf", "docx","doc"]) )
        {
         //Mensaje flash para mostrar el error

         Yii::$app->session->setFlash("error");
        }



     }

     return $this->render('view', [
         'model' => $this->findModel($id),
     ]);
    }


    public function actionRe($id){
        $resolucion = Resoluciones::find()->where(['and',['id_entidad' => $id],['id_tipo_resolucion' => 1]])->one();
        ResolucionesController::actionView($resolucion->id_resolucion);
    }

    public function actionRes($id){
      //$reso = new ResolucionesController
       // ResolucionesController::actionView($id);
        $this->redirect(Yii::$app->request->baseUrl."?r=resoluciones%2Fview&id=".$id);
    }
    public function actionCreateMPDF(){
            //$mpdf = new mPDF(['format' => 'Legal']);
            $mpdf = new mPDF();

            $mpdf-> writeHTML($this->renderPartial('mpdf'));
            $mpdf->Output();
            exit;
        }

    public function actionSamplepdf() {

            $mpdf = new mPDF();

            //$mpdf = new mPDF;
            $mpdf->setHeader('<div style="width: 100%; height: 80px;">

            <img src="img/logo2.png" width = "200px" >

              </div>');
            $mpdf->setFooter('Página {PAGENO}'. '   Generado por software personería juridíca');

            //Marca de agua
            //$mpdf->SetWatermarkText('ÉSTE DOCUMENTO NO TIENE VALIDEZ LEGAL');
            //$mpdf->showWatermarkText = true;
            $mpdf->SetWatermarkImage('img/escudovalle.png');
            //$mpdf->SetWatermarkImage('../img/logo.png');
            //$mpdf->SetWatermarkImage('https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Escudo_del_Valle_del_Cauca.svg/240px-Escudo_del_Valle_del_Cauca.svg.png');
            $mpdf->showWatermarkImage = true;


            $mpdf->SetTitle('Entidades'); //Título

            $mpdf->AddPageByArray(array(
                'sheet-size' => 'Letter',
                'resetpagenum' => '1',
                'pagenumstyle' => '1',
            ));
            //Texto, aqui se escriben las páginas
            $session = Yii::$app->session;
            $consulta = $session->get('query');
            $provider = new ActiveDataProvider([
                'query' => $consulta,
                'pagination' => [
                    'pageSize' => 0,
                ],
            ]);

            $entidades = $provider->getModels();

            //$entidades = Entidades::find()->asArray()->all();

            $html = "

        <style type="."text/css".">
            body {
              position: relative;
              width: 21cm;
              height: 29.7cm;
              margin: 0 auto;
              color: #001028;
              background: #FFFFFF;
              font-family: Arial, sans-serif;
              font-size: 16px;
              font-family: Arial;
            }

            table {
              width: 100%;
              border-collapse: collapse;
              border-spacing: 0;
              margin-bottom: 20px;
            }

            table tr:nth-child(2n-1) td {
              background: #F5F5F5;
            }

            table th,
            table td {
              text-align: center;
            }

            table th {
              padding-top: 80px;
              color: #5D6975;
              border-bottom: 1px solid #C1CED9;
              white-space: nowrap;
              font-weight: normal;}
            }

            table td {
              padding: 20px;
              text-align: center;
            }


            table td.unit,
            table td.qty,
            table td.total {
              font-size: 1.2em;
            }

        </style>

            <h1>ENTIDADES</h1>


              <table style="."width:100%".">
                   <thead>
                      <tr>
                          <th width='50'>".'ID ENTIDAD'."</th>
                          <th width='150'>".'AÑO PERSONERIA'."</th>
                          <th width='50'>".'N° PERSONERIA'."</th>
                          <th width='250'>".'NOMBRE'."</th>
                          <th width='150'>".'TIPO'."</th>
                          <th width='150'>".'CLASE'."</th>
                      </tr>
                    </thead>";


              for($i =0; $i < sizeof($entidades);$i++){
                $tipo = TipoEntidad::findOne($entidades[$i]['id_tipo_entidad']);
                $clase = ClaseEntidad::findOne($entidades[$i]['id_clase_entidad']);
                $html =$html."
                <tbody>
                <tr>
                    <td width='50'>".$entidades[$i]['id_entidad']."</td>
                    <td width='150'>".$entidades[$i]['personeria_year']."</td>
                    <td width='50'>".$entidades[$i]['personeria_n']."</td>
                    <td width='250'>".$entidades[$i]['nombre_entidad']."</td>
                    <td width='150'>".$tipo['tipo_entidad']."</td>
                    <td width='150'>".$clase['clase_entidad']."</td>
                </tr>
                </tbody>
                ";

            }
            $html = $html."</table>";
            $mpdf->WriteHTML($html);

            /*Fin de las páginas*/

            /*Se da la salida del PDF*/
            //$mpdf->Output();
            $mpdf->Output('Reporte Entidades.pdf','D'); //Para que descargue automaticamente
            exit;
        }

        public function actionMetodoexcel(){
            $session = Yii::$app->session;
            $consulta = $session->get('query');
            $provider = new ActiveDataProvider([
                'query' => $consulta,
                'pagination' => [
                  'pageSize' => 0,
                ],
            ]);
            $entidades = $provider->getModels();
           //$entidades = Entidades::find()->asArray()->all();
            $html = "

                <title> ENTIDADES </title>

                <table style="."width:100%".">
                <tr>
                    <th width='150'>".'ID ENTIDAD'."</th>
                    <th width='150'>".utf8_decode('AÑO PERSONERIA')."</th>
                    <th width='150'>".utf8_decode('N° PERSONERIA')."</th>
                    <th width='150'>".'NOMBRE'."</th>
                    <th width='150'>".'TIPO'."</th>
                    <th width='150'>".'CLASE'."</th>
                </tr> ";


           for($i =0; $i < sizeof($entidades);$i++){
                $tipo = TipoEntidad::findOne($entidades[$i]['id_tipo_entidad']);
                $clase = ClaseEntidad::findOne($entidades[$i]['id_clase_entidad']);
                $html =$html."

                <tr>
                    <td width='50'>".$entidades[$i]['id_entidad']."</td>
                    <td width='150'>".$entidades[$i]['personeria_year']."</td>
                    <td width='50'>".$entidades[$i]['personeria_n']."</td>
                    <td width='250'>".utf8_decode($entidades[$i]['nombre_entidad'])."</td>
                    <td width='150'>".utf8_decode($tipo['tipo_entidad'])."</td>
                    <td width='150'>".utf8_decode($clase['clase_entidad'])."</td>
                </tr>

                ";

            }

            $html = $html."</table>";

        header("Content-Type:application/vnd.ms-excelxls");
        header("Content-disposition:attachment; filename=Entidades.xls");
        echo $html;

        }
}

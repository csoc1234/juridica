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
use app\models\UbicacionEntidad;
use yii\helpers\ArrayHelper;

class EntidadesController extends Controller
{

    public function verificarElemento($elemento,$array){

      if(!in_array($elemento,$array)){
        array_push($array,$elemento);
      }
      return $array;

     }
     public function behaviors()
     {
     //Aqui se agregan los sitios que tendran restricción de acceso
      
      $permisos = ['index','dignatario','historial','view','download','res','createMPDF','samplepdf','metodoexcel','reportfiles'];

      
      if(isset(Yii::$app->user->identity->id_rol)){
        if(User::IsTramitador() || User::IsAdministrador()){    
          $permisos = $this->verificarElemento('create',$permisos);  
          $permisos = $this->verificarElemento('update',$permisos);
          $permisos = $this->verificarElemento('re',$permisos);                
        } 
      }     
      
      return [
         'access' => [
             'class' => AccessControl::className(),
             'only' => $permisos,
             'rules' => [
                 [
                     'actions' => $permisos,
                     'allow' => true,
                     'roles' => ['@'],
         'matchCallback' => function ($rule, $action) {
           $valid_roles = [User::PRIVILEGIO_REPARTIDOR,User::PRIVILEGIO_RADICADOR,User::PRIVILEGIO_TRAMITADOR,User::PRIVILEGIO_CERTIFICADOR];
           return User::roleInArray($valid_roles) && User::isActive();
                   }
                 ],
             ],
         ],
         'verbs' => [
             'class' => VerbFilter::className(),
             'actions' => [
                 'delete' => ['POST'],
             ],
         ],
     ];
     }

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
   
    public function actionView($id)
    {
        $session = Yii::$app->session;
        $session->set('id_entidad',$id);
        $msg = $session->get('msg');
        $session->set('msg',null);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'msg'=> $msg,
        ]);
    }

     public function actionCreate()
     {
       $session = Yii::$app->session;
       $idRadicado = $session->get('id_radicado');
       $radicado = Radicados::findOne($idRadicado);
       if((User::IsAdministrador() || User::IsTramitador()) && $radicado ){
         $model = new Entidades();
         $model->estado_entidad = 1;
         $tiempo = Carbon::now('America/Bogota');
         $año = $tiempo->year;
         list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);

       if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
           Yii::$app->response->format = 'json';
           return ActiveForm::validate($model);
         }

       if($radicado){
          if(User::IsAdministrador()){
              $fecha1 = Carbon::now();
              $fecha1= new Datetime($fecha1->toDateString());
          }else{
              $fecha1 = new DateTime($radicado->fecha_creacion);
          }
        }
        if ($model->load(Yii::$app->request->post())  ) {
        if(User::IsAdministrador()){
            $fecha1 = Carbon::now();
            $fecha1= new Datetime($fecha1->toDateString());
        }
          //$fecha2 = new Datetime($model->fecha_reconocimiento);
          $fecha3 = new DateTime($model->fecha_estatutos);
        // $interval1 = $fecha1->diff($fecha2); // la fecha de reconocimiento debe ser mayor o igual a la fecha de radicacion
          $interval2 = $fecha3->diff($fecha1); // la fecha de estatutos debe ser menor o igual a la fecha de radicacion
          //$a = $interval1->format('%R%a');
          $b = $interval2->format('%R%a');

        
          if( $b >= 0){

          if(!$model->save()){
            return $this->render('create', [
                'model' => $model,
              //'msg' => "LA FECHA DE LOS ESTATUTOS DEBE SER INFERIOR A $radicado->fecha_creacion",
              //'update' => false,
            ]);
          }

          $model->file = UploadedFile::getInstance($model, 'file');

          if($model->file == null){
            $model->datos_digitales = "";

          }else{

            if (!file_exists($model->id_entidad)){
              mkdir("/var/DocJuridica/".$model->id_entidad,0777);
              chmod("/var/DocJuridica/".$model->id_entidad,0777);
            }
            $model->file->saveAs("/var/DocJuridica/".$model->id_entidad.'/' . $model->file->baseName. '.' . $model->file->extension);
            //$model->datos_digitales = 'uploads/' . $model->nombre_entidad . '.' . $model->file->extension;
            $archivos = scandir("/var/DocJuridica/".$model->id_entidad);
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
          $historial->nombre_evento = "CREACIÓN DE ENTIDAD";
          $historial->id_tabla_modificada = $model->id_entidad;
          $historial->fecha_modificacion = $tiempo->toDateTimeString();
          $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
          $historial->tabla_modificada = "ENTIDADES";
          $historial->save(false);

          // crea la resolucion de reconimiento de personeria juridica para esta entidad
        

          $session = Yii::$app->session;
          $session->set('id_entidad',$model->id_entidad);
          $session->set('id_historial_reconocimiento',$historial->id_historial);
          //return $this->redirect(['view', 'id' => $model->id_entidad]);

          if($model->id_tipo_entidad == 5 || $model->id_tipo_entidad == 6 || $model->id_tipo_entidad == 10){
            $arrayUbicacion =  UbicacionEntidad::find()->where(['id_ubicacion'=>1])->one();
            
            $ubicacion = $arrayUbicacion['codigo'];
            $ubicacion = $this->crearUbicacion($ubicacion);
            $model->ubicacion_archivos_entidad = $ubicacion;
            $model->save(false);
            $arrayUbicacion->codigo = $ubicacion;
            $arrayUbicacion->save(false);
            
          }elseif($model->id_tipo_entidad == 12){
            $arrayUbicacion =  UbicacionEntidad::find()->where(['id_ubicacion'=>4])->one();
            $ubicacion = $arrayUbicacion['codigo'];
            $ubicacion = $this->crearUbicacion($ubicacion);
            $model->ubicacion_archivos_entidad = $ubicacion;
            $model->save(false);
            $arrayUbicacion->codigo = $ubicacion;
            $arrayUbicacion->save(false);
          }elseif($model->id_tipo_entidad == 9 || $model->id_tipo_entidad == 14){
            $arrayUbicacion =  UbicacionEntidad::find()->where(['id_ubicacion'=>3])->one();
            $ubicacion = $arrayUbicacion['codigo'];
            $ubicacion = $this->crearUbicacion($ubicacion);
            $model->ubicacion_archivos_entidad = $ubicacion;
            $model->save(false);
            $arrayUbicacion->codigo = $ubicacion;
            $arrayUbicacion->save(false);
          }elseif($model->id_tipo_entidad == 8){
            $arrayUbicacion =  UbicacionEntidad::find()->where(['id_ubicacion'=>2])->one();
            $ubicacion = $arrayUbicacion['codigo'];
            $ubicacion = $this->crearUbicacion($ubicacion);
            $model->ubicacion_archivos_entidad = $ubicacion;
            $model->save(false);
            $arrayUbicacion->codigo = $ubicacion;
            $arrayUbicacion->save(false);
          }
          return $this->crearDignatario();
        }else{
          return $this->render('create', [
              'model' => $model,
              'msg' => "LA FECHA DE LOS ESTATUTOS DEBE SER INFERIOR A $radicado->fecha_creacion",
              'update' => false,
            ]);
          } 
        } else {
          $model->personeria_n = null;
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

     public function crearUbicacion($ubicacion){
      
      $array_numeros_ubicacion = explode("-", $ubicacion);
      
      $array_numeros_ubicacion[2] += 1;
      $array_numeros_ubicacion[2] = $this->agregar3Ceros($array_numeros_ubicacion[2]);    
      if($array_numeros_ubicacion[2] > 999){
        $array_numeros_ubicacion[2] = "001";
        $array_numeros_ubicacion[1] += 1;
        $array_numeros_ubicacion[1] = $this->agregar2Ceros($array_numeros_ubicacion[1]); 
        if($array_numeros_ubicacion[1] > 99){
          $array_numeros_ubicacion[1] = "01";
          $codigo_letra = $array_numeros_ubicacion[0];
          $codigo_letra[1] = $codigo_letra[1]+1;
          if($codigo_letra[1] > 9){
            $codigo_letra[1] = "1";
          }
          $array_numeros_ubicacion[0] = $codigo_letra;
        }
      }
      return implode("-",$array_numeros_ubicacion);

     }
     public function agregar3Ceros($numero){
      if ($numero < 10)
      return "00".$numero;
      elseif ($numero >= 10 && $numero < 100 )
      return "0".$numero;
      else
      return strval($numero);
    }

    public function agregar2Ceros($numero){
      if ($numero < 10)
      return "0".$numero;
      else
      return strval($numero);
    }
     public function actionUpdate($id)
 {
   $session = Yii::$app->session;
   $idRadicado = $session->get('id_radicado');
   $radicado = Radicados::findOne($idRadicado);
   $model = $this->findModel($id);
   if((User::IsAdministrador() && $radicado) || (User::IsTramitador() && $radicado) ){
     //$model = $this->findModel($id);
     $oldModel = Entidades::findOne($id);
     $tiempo = Carbon::now('America/Bogota');
     $año = $tiempo->year;
     //$año = 2018; // cambiar el año para probar si funciona lo del incrementable de las resoluciones


     if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
       Yii::$app->response->format = Response::FORMAT_JSON;
       return ActiveForm::validate($model);
     }
      if ($model->load(Yii::$app->request->post())) {
       $model->file = UploadedFile::getInstance($model, 'file');
       if($model->file == null){
         $model->datos_digitales = "";
       }else{

         if (!file_exists("/var/DocJuridica/".$model->id_entidad)){
           mkdir("/var/DocJuridica/".$model->id_entidad,0777);
           chmod("/var/DocJuridica/".$model->id_entidad,0777);
         }
         if ($model->file->saveAs("/var/DocJuridica/".$model->id_entidad.'/' . $model->file->baseName . '.' . $model->file->extension)){
           $this->UpdateHistory("SUBIDA DE ARCHIVO ENTIDAD","datos_digitales","ENTIDADES", $model, $oldModel);
         }
         //$model->datos_digitales = 'uploads/' . $model->nombre_entidad . '.' . $model->file->extension;
         $archivos = scandir("/var/DocJuridica/".$model->id_entidad);
         unset($archivos[0],$archivos[1]);
         $nombres = "";
         foreach ($archivos as $key => $value) {
           $nombres = $nombres.$model->id_entidad.'/'.$value."\n";
          }
            $model->datos_digitales =  $nombres;
          }
          $model->file = null;//$model->save();

          
          $countTotal = 0;
          $count = 0;
          $array_historial_combinado = array();
          $array_nombre_cambio = array();
          $array_campo_cambio = array();

          $id_tipo_tramite = $radicado['id_tipo_tramite'];

          if($id_tipo_tramite == 2){
              $id_tipo_radicado = $radicado['id_tipo_resolucion'];
              
              if($id_tipo_radicado == 9){
                  $TiposDeResolucion = explode(",",$radicado['id_tipo_resolucion_combinada']);                  
                  $var_reformaEstatutos = in_array(2, $TiposDeResolucion);                      

                  if($var_reformaEstatutos){
                      $countTotal = $countTotal + 1;
                      $TiposDeReforma = explode(",",$radicado['id_tipo_reforma_estatutaria']);
                      $var_cambio_razon_social = in_array(1, $TiposDeReforma);
                      $var_cambio_domicilio = in_array(2, $TiposDeReforma);
                      $var_cambio_objeto_social = in_array(3, $TiposDeReforma);

                      if($var_cambio_razon_social) $countTotal = $countTotal + 1;  
                      if($var_cambio_domicilio) $countTotal = $countTotal + 1;  
                      if($var_cambio_objeto_social) $countTotal = $countTotal + 1;  
                  }
              }
          }

          if ($var_cambio_razon_social){
            
            if($model->nombre_entidad != $oldModel->nombre_entidad){
              $count = $count + 1; 
              array_push($array_nombre_cambio,"CAMBIO DE NOMBRE ENTIDAD");
              array_push($array_campo_cambio,"nombre_entidad");

              if($countTotal == 1){
                list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);
                $resolucion = $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);
                $this->UpdateIdHistoriales($resolucion);
              }             
             
            }
          }
          if($var_cambio_domicilio){
             $flag1 = false;
            if ($model->municipio_entidad != $oldModel->municipio_entidad){              
              $flag1 = true;
              array_push($array_nombre_cambio,"CAMBIO DE MUNICIPIO ENTIDAD");
              array_push($array_campo_cambio,"municipio_entidad");
              
              if($countTotal == 1){
                list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);
                $resolucion = $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);
                $this->UpdateIdHistoriales($resolucion);
              } 
            
            }

            if ($model->direccion_entidad != $oldModel->direccion_entidad){
              $flag1 = true;              
              array_push($array_nombre_cambio,"CAMBIO DE DIRECCIÓN ENTIDAD");
              array_push($array_campo_cambio,"direccion_entidad");
              
              if($countTotal == 1){
                list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);
                $resolucion = $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);
                $this->UpdateIdHistoriales($resolucion);
              }
            }
            if($flag1)$count = $count + 1; 
          }

          if($var_cambio_objeto_social){
            
            if ($model->id_tipo_entidad != $oldModel->id_tipo_entidad){
              $count = $count + 1;
              array_push($array_nombre_cambio,"CAMBIO DE TIPO ENTIDAD");
              array_push($array_campo_cambio,"id_tipo_entidad");
              
              if($countTotal == 1){
                list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);
                $resolucion = $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);
                $this->UpdateIdHistoriales($resolucion);
              }
            }
          }         

        
          if ($model->fecha_estatutos != $oldModel->fecha_estatutos){
            $count = $count + 1; 
            array_push($array_nombre_cambio,"CAMBIO DE FECHA DE LOS ESTATUTOS DE LA ENTIDAD");
            array_push($array_campo_cambio,"fecha_estatutos");
            
          }


          if ($model->telefono_entidad != $oldModel->telefono_entidad){
            array_push($array_nombre_cambio,"CAMBIO DEL TELEFONO DE LA ENTIDAD");
            array_push($array_campo_cambio,"telefono_entidad");
            
          }

          if ($model->email_entidad != $oldModel->email_entidad){
        
            array_push($array_nombre_cambio,"CAMBIO DEL EMAIL DE LA ENTIDAD");
            array_push($array_campo_cambio,"email_entidad");
            
          }

          if($count < $countTotal){
            Yii::$app->session->setFlash('ALERTA', "No ha realizado todos los cambio en el formulario");
            return $this->render('update', [
              'model' => $model,
              'update'=> true,
              'file' => false,
            ]);

          }else{

            if(!empty($array_nombre_cambio)) {
              for($i = 0; $i < count($array_nombre_cambio); $i++){
                $historial = $this->UpdateHistory($array_nombre_cambio[$i],$array_campo_cambio[$i],"ENTIDADES", $model, $oldModel);
                $campo_guardado = $array_campo_cambio[$i];
                if($campo_guardado == "nombre_entidad" || $campo_guardado == "municipio_entidad" || $campo_guardado == "direccion_entidad" || $campo_guardado == "id_tipo_entidad" || $campo_guardado == "fecha_estatutos")
                  array_push($array_historial_combinado,$historial->id_historial);
                }
            }

            
            if($countTotal == 0 ){
              Yii::$app->session->set('radicado',null);
              return $this->redirect(['view', 'id' => $model->id_entidad]);
            }else if($countTotal >= 1){
              list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);
              $resolucion = new Resoluciones();
              $tiempo = Carbon::now('America/Bogota');
              $resolucion->id_tipo_resolucion = $radicado['id_tipo_resolucion'];
              $resolucion->nombre_entidad = $model->nombre_entidad;
              $resolucion->id_entidad = $model->id_entidad;
              
              $resolucion->id_historial_combinado = implode($delimiter = ',',$array_historial_combinado);
              
              $resolucion->numero_resolucion = $numero_resolucion;
              $resolucion->ano_resolucion = $año;
              $resolucion->id_radicado = $radicado->id_radicado;
              $resolucion->fecha_creacion = $tiempo;
              $resolucion->save(false);
              $model->save(false);

              $this->UpdateIdHistoriales($resolucion);

              if(!empty($array_historial_combinado)){
                foreach ($array_historial_combinado as $valor){
                  $old_historial = Historial::findOne($valor);
                  $old_historial->id_resolucion = $resolucion->id_resolucion;
                  $old_historial->save(false);
                }
              } 

              Yii::$app->session->set('id_radicado',null);
              Yii::$app->session->set('radicado',null);
              $radicado['estado'] = 3;
              $radicado->save(false);
              return $this->redirect(['view', 'id' => $model->id_entidad]);
            }
            
          }

          
     } else {
         return $this->render('update', [
             'model' => $model,
             'update'=> true,
             'file' => false,
         ]);
     }
  }
   
   else{
        $bandera1 = false;
        $bandera2 = false;
        $model->file = UploadedFile::getInstance($model, 'file');
        if (!file_exists("/var/DocJuridica/".$model->id_entidad)){
          mkdir("/var/DocJuridica/".$model->id_entidad,0777);
          chmod("/var/DocJuridica/".$model->id_entidad,0777);
        }
        if ($model->file != null && $model->file->saveAs("/var/DocJuridica/".$model->id_entidad.'/' . $model->file->baseName . '.' . $model->file->extension)){
          $this->UpdateHistory("SUBIDA DE ARCHIVO ENTIDAD","datos_digitales","ENTIDADES", $model, $oldModel);
            $bandera1 = true;

        }
        if($bandera1 || $bandera2){
          return $this->redirect(['view', 'id' => $model->id_entidad]);
        }

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

 private function UpdateHistory($Nombre_evento, $Nombre_campo, $Tabla_modificada, $model, $oldModel){
  $historial = new Historial();
  $tiempo = Carbon::now('America/Bogota');
  $historial->nombre_evento = $Nombre_evento;
  $historial->id_tabla_modificada = $model->id_entidad;
  $historial->fecha_modificacion = $tiempo;
  $historial->nombre_campo_modificado = $Nombre_campo;
  $historial->valor_anterior_campo = $oldModel[$Nombre_campo];
  $historial->valor_nuevo_campo = $model[$Nombre_campo];
  $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
  $historial->tabla_modificada = $Tabla_modificada; 
  $historial->save(false);
  return $historial;
}

private function UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año){
  $resolucion = new Resoluciones();
  $tiempo = Carbon::now('America/Bogota');
  $resolucion->id_tipo_resolucion = $radicado['id_tipo_resolucion']; // inserto el id correspondiente a cancelacion de personería jurídica NPI
  $resolucion->nombre_entidad = $model->nombre_entidad;
  $resolucion->id_entidad = $model->id_entidad;
  if ($historial != null){
    $resolucion->id_historial = $historial->id_historial;
    $historial->id_resolucion = $resolucion->id_resolucion;
    $historial->save(false);
  }
  $resolucion->numero_resolucion = $numero_resolucion;
  $resolucion->ano_resolucion = $año;
  $resolucion->id_radicado = $radicado->id_radicado;
  $resolucion->fecha_creacion = $tiempo;
  $resolucion->save(false);

  return $resolucion;
}

private function UpdateIdHistoriales($resolucion){
  $session = Yii::$app->session;
  $array_id_historial_sin_resolucion = $session->get('array_id_historiales');
  if(!empty($array_id_historial_sin_resolucion)){
    foreach ($array_id_historial_sin_resolucion as $valor){
      $old_historial = Historial::findOne($valor);
      $old_historial->id_resolucion = $resolucion->id_resolucion;
      $old_historial->save(false);
    }
    $session->set('array_id_historiales', null); 
  } 
}

private function NumeroResolucion($año, $model){

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

  return array ($model, $numero_resolucion);
}
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
    $path = pathinfo($dir);
    $ruta = $path['dirname'];
    $extensions = $path['extension'];
    $nombre = $path['basename'];
     //Si el archivo existe

     //if (is_file($ruta.$nombre))
     //{
      //Obtener información del archivo
      //Obtener la extensión del archivo
 
      //if (is_array($extensions))
      //{
       //Si el argumento $extensions es un array
       //Comprobar las extensiones permitidas
       //foreach($extensions as $e)
       //{
        //Si la extension es correcta
        //if ($e === $extension)
        //{
         //Procedemos a descargar el archivo
         // Definir headers
         $size = filesize($dir);
         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header("Content-Disposition: attachment; filename=$nombre");
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
         header('Pragma: public');
         header("Content-Length: " . $size);
         // Descargar archivo
         readfile($dir);
         //Correcto
         return true;
         //}
       // }
      // }

      }
     
     //Ha ocurrido un error al descargar el archivo
     //echo $dir;
     //echo $ruta;
     //echo $nombre;
     //echo $exte;
     //echo $file;
    //}

    public function actionDownload()
    {
      $session = Yii::$app->session;
      $id = $session->get('id_entidad');
      $entidad = Entidades::findOne($id);
     if (Yii::$app->request->get("file"))
     {
      //Si el archivo no se ha podido descargar
      //downloadFile($dir, $file, $extensions=[])

        if (!$this->downloadFile('/var/DocJuridica/'.$entidad['id_entidad'].'/Radicado'.Yii::$app->request->get('radicado').'/'. Yii::$app->request->get('file'), ["pdf", "docx","doc"]) )
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

    public function actionRes($id, $tipoRadicado){
        $this->redirect(Yii::$app->request->baseUrl."?r=resoluciones%2Fview&id=".urlencode($id)."&tipoRadicado=".urlencode($tipoRadicado));
    }
    public function actionCreateMPDF(){
            //$mpdf = new mPDF(['format' => 'Legal']);
            $mpdf = new mPDF();

            $mpdf-> writeHTML($this->renderPartial('mpdf'));
            $mpdf->Output();
            exit;
        }

    public function actionSamplepdf($selection, $Full = false) {

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
            if($Full == false){
            $provider =  new \yii\data\ActiveDataProvider([
                'query' => Entidades::find()->where(['id_entidad' => $selection]),

                ]);
            } elseif ($Full == true) {
              $session = Yii::$app->session;
              $consulta = $session->get('query');
              $provider = new ActiveDataProvider([
                  'query' => $consulta,
                  'pagination' => [
                      'pageSize' => 0,
                  ],
              ]);
            }

            $entidades = $provider->getModels();
            //$entidades = $provider->getModels();

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

        public function actionMetodoexcel($selection, $Full = false){
            if($Full == false){

            $provider =  new \yii\data\ActiveDataProvider([
                'query' => Entidades::find()->where(['id_entidad' => $selection]),

                ]);
            } elseif ($Full == true) {

              $session = Yii::$app->session;
              $consulta = $session->get('query');
              $provider = new ActiveDataProvider([
                  'query' => $consulta,
                  'pagination' => [
                      'pageSize' => 0,
                  ],
              ]);
            }

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

      public function actionReportfiles(){

        $selection=(array)Yii::$app->request->post('selection');//typecasting

        switch (\Yii::$app->request->post('submit')) {
            case 'PDFSubmit':
                  if($selection != null){
                    $this->actionSamplepdf($selection);
                  }else{
                    Yii::$app->session->setFlash('Error', "No ha seleccionado ninguna casilla, intente generar el reporte completo.");
                    $this->redirect(["entidades/index"]);
                  }

              break;

            case 'ExcelSubmit':
              if($selection != null){
                  $this->actionMetodoexcel($selection);
                  }else{
                    Yii::$app->session->setFlash('Error', "No ha seleccionado ninguna casilla, intente generar el reporte completo.");
                    $this->redirect(["entidades/index"]);
                  }

              break;

            case 'PDFSubmitFull':
                  $Full = true;
                  $this->actionSamplepdf(null, $Full);
              break;

            case 'ExcelSubmitFull':
                  $Full = true;
                  $this->actionMetodoexcel(null, $Full);
              break;
          }
        }

}

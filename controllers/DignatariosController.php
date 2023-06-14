<?php

namespace app\controllers;

use Yii;
use app\models\Dignatarios;
use app\models\DignatariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Carbon\Carbon;
use app\models\User;
use app\models\Cargos;
use app\models\Historial;
use app\models\Entidades;
use app\models\Resoluciones;
use app\models\GruposCargos;
use app\models\Radicados;
use DateTime;

class DignatariosController extends Controller
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
      
      $permisos = ['historial','entidad','index','index1','view'];

      if(User::IsTramitador() || User::IsAdministrador()){    
        $permisos = $this->verificarElemento('create',$permisos);  
        $permisos = $this->verificarElemento('update',$permisos);
        $permisos = $this->verificarElemento('cancelar',$permisos);
        $permisos = $this->verificarElemento('anadirdignatario',$permisos);
        $permisos = $this->verificarElemento('cambiarRepresentante',$permisos);     
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

   public function actionHistorial($id)
   {
       $this->redirect(Yii::$app->request->baseUrl."?r=historial%2Findex2&id=".$id);
   }

   public function actionEntidad(){
	$session = Yii::$app->session;
	$id = $session->get('id_entidad');
	$this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$id);
   }

    public function actionIndex()
    {
        $searchModel = new DignatariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $session = Yii::$app->session;

        if(User::IsAdministrador() || User::IsTramitador() ){
          $session->set('editar',true);
        }else{
          $session->set('editar',false);
        }

        $id = $session->get('id_entidad');
        $cargos =Cargos::find()->all();
        $gcargos = GruposCargos::find()->all();
        $dataProvider->query->andWhere(['id_entidad'=>$id]);
        $titulo = Entidades::findOne($id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => $titulo['nombre_entidad'],
            'cargos' => $cargos,
            'gcargos' => $gcargos,

        ]);
    }

    public function actionIndex1($id)   // funcion llamada por el entidadescontroller para retornar la vista con los dignatarios desde el boton de usuarios
    {
        $searchModel = new DignatariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_entidad'=>$id]);

        // VERIFICA SI LOS DIGANTARIOS SE ENCUENTRAN DENTRO DEL INTERVALO DE SU PERIODO DE LO CONTRARIO LOS DESACTIVA
        $dignatarios = Dignatarios::find()->where(['id_entidad' => $id])->asArray()->all();
        $tiempo = Carbon::now('America/Bogota');

        $now = new DateTime($tiempo->toDateString());

        foreach ($dignatarios as $key) {

          $dignatario = Dignatarios::findOne($key['id_dignatario']);
          $fin = new DateTime($dignatario['fin_periodo']);
          $interval = $fin->diff($now);
          $a= $interval->format('%R%a'); // intervalo de tiempo entre el fecha fin y fecha actual
          if($a > 0){
            $dignatario->estado = 0;
            $dignatario->save();
          }
        }

        $cargos =Cargos::find()->all();
        $gcargos = GruposCargos::find()->all();
        $titulo = Entidades::findOne($id);
        $session = Yii::$app->session;
        $session->set('id_entidad',$id);

        if(User::IsAdministrador() || User::IsTramitador()){
          $session->set('editar',true);
        }else{
          $session->set('editar',false);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => $titulo['nombre_entidad'],
            'cargos' => $cargos,
            'gcargos' => $gcargos,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCancelar(){
      $session = Yii::$app->session;
      
      $id_entidad = $session->get('id_entidad');
     

      $isInscripcionDignatario = $session->get('isInscripcionDignatario');

      if($isInscripcionDignatario == true){
        $session->set('isInscripcionDignatario', false);
        $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fupdate&id=".$id_entidad); 
      }else{
        $id = $session->get('id_radicado');
        $radicado = Radicados::findOne($id);
        $entidad = Entidades::findOne($id_entidad); 
        $historial = null;
        $tiempo = Carbon::now('America/Bogota');
        $año = $tiempo->year;

        $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
        $numero_resolucion = 1;

        if($ultima_resolucion['ano_resolucion'] == $año){
          $numero_resolucion = $ultima_resolucion['numero_resolucion'];
          $numero_resolucion = $numero_resolucion + 1;
        }

        $resolucion = $this->UpdateResolucion($radicado, $entidad, $historial, $numero_resolucion, $año);
              $id_historial_reconocimiento = $session->get('id_historial_reconocimiento');
              if($id_historial_reconocimiento != null){
                $old_historial = Historial::findOne($id_historial_reconocimiento);
                $old_historial->id_resolucion = $resolucion->id_resolucion;
                $old_historial->save(false);
                $session->set('id_historial_reconocimiento',null);
              }

              $array_id_historial_sin_resolucion = $session->get('array_id_historiales');
              
              $id_historial_asimilacion = $session->get('id_historial_asimilacion');
              if($id_historial_asimilacion != null){
                array_push($array_id_historial_sin_resolucion, $id_historial_asimilacion);
                $session->set('id_historial_asimilacion', null);
              }
              
              if(!empty($array_id_historial_sin_resolucion)){
                foreach ($array_id_historial_sin_resolucion as $valor){
                  $old_historial = Historial::findOne($valor);
                  $old_historial->id_resolucion = $resolucion->id_resolucion;
                  $old_historial->save(false);
                }
                $session->set('array_id_historiales', null); 
              }
          $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$id_entidad);
        }
    }

    public function cambiarRepresentante($id_entidad){
      $dignatarios = Dignatarios::find()->where(['id_entidad'=>$id_entidad])->all();
      foreach($dignatarios as $dignatario){

        if($dignatario['representante_legal'] == 1){
          $dignatario['representante_legal'] = 0;
          $dignatario->save(false);
        }

      }
    }

    public function actionCreate()
    {
      $session = Yii::$app->session;
      $id = $session->get('id_radicado');
      $id_entidad = $session->get('id_entidad');
      $radicado = Radicados::findOne($id);
      $isAñadirDignatario = null;
   
      if(empty($id_entidad)){
        $id_entidad = $radicado->id_entidad;
      }else{
        $radicado->id_entidad = $id_entidad;
        $radicado->save(false);
      }
      
      if(User::IsAdministrador() || User::IsTramitador()){
        $model = new Dignatarios();
        $model->id_entidad = $id_entidad;        

        if(Yii::$app->request->post('create') == '1'){
          $isAñadirDignatario = false;
        }else if(Yii::$app->request->post('create') == '2'){
          $isAñadirDignatario = true;
        }

        $tiempo = Carbon::now('America/Bogota');
        $año = $tiempo->year;

        $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
        $numero_resolucion = 1;

        if($ultima_resolucion['ano_resolucion'] == $año){
          $numero_resolucion = $ultima_resolucion['numero_resolucion'];
          $numero_resolucion = $numero_resolucion + 1;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

        $model->fecha_ingreso = $tiempo->toDateString();
        if ($model->load(Yii::$app->request->post())) {
          $fin = new DateTime($model['fin_periodo']);
          $now = new Datetime($tiempo->toDateString());
          $interval = $now->diff($fin);
          $a = $interval->format('%R%a');
          $model->estado = 1;
          $session = Yii::$app->session;
 
          
          if($session->get('repre')) $model->id_cargo = 1;
          if($session->get('Presidente_Adhoc')) $model->id_cargo = 119;
          if($session->get('Secretario_Adhoc')) $model->id_cargo = 1064;
 

        if($a > 0){

          if($model['representante_legal'] == 1)$this->cambiarRepresentante($model->id_entidad);
          $model->save(false);
          // Se crea el historial de la creacion de los nuevos dignatarios.
          $historial = new Historial();
          $historial->nombre_evento = "CREACIÓN DE DIGNATARIO";
          $historial->id_tabla_modificada = $model->id_dignatario;
          $historial->fecha_modificacion = $tiempo->toDateTimeString();
          $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
          $historial->tabla_modificada = "DIGNATARIOS";
          $historial->save(false);

          $session = Yii::$app->session;
          $array_id_historial_sin_resolucion = $session->get('array_id_historiales');
          if(empty($array_id_historial_sin_resolucion)){
            $array_id_historial_sin_resolucion = array();
          }
          array_push($array_id_historial_sin_resolucion, $historial->id_historial);
          $session->set('array_id_historiales',$array_id_historial_sin_resolucion);


          if($isAñadirDignatario == true){
            $session = Yii::$app->session;
            $repre = $session->set('repre',false);
            $session->set('Presidente_Adhoc',false);
            
            $model = new Dignatarios();

            Yii::$app->session->setFlash('ALERTA', "Se ha guardado el dignatario correctamente!");
            return $this->render('create', [
              'model' => $model,
          ]);
          }else if($isAñadirDignatario == false){
            $session = Yii::$app->session;
            $entidad = Entidades::findOne($id_entidad); 
            $isInscripcionDignatario = $session->get('isInscripcionDignatario');  
            $session->set('Secretario_Adhoc',false);

            if($isInscripcionDignatario == true){
              $session->set('isInscripcionDignatario', false);
              $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fupdate&id=".$model->id_entidad); 
            }else{
              $resolucion = $this->UpdateResolucion($radicado, $entidad, $historial, $numero_resolucion, $año);
              $id_historial_reconocimiento = $session->get('id_historial_reconocimiento');
              if($id_historial_reconocimiento != null){
                $old_historial = Historial::findOne($id_historial_reconocimiento);
                $old_historial->id_resolucion = $resolucion->id_resolucion;
                $old_historial->save(false);
                $session->set('id_historial_reconocimiento',null);
              }

              $array_id_historial_sin_resolucion = $session->get('array_id_historiales');
              
              $id_historial_asimilacion = $session->get('id_historial_asimilacion');
              if($id_historial_asimilacion != null){
                array_push($array_id_historial_sin_resolucion, $id_historial_asimilacion);
                $session->set('id_historial_asimilacion', null);
              }
              
              if(!empty($array_id_historial_sin_resolucion)){
                foreach ($array_id_historial_sin_resolucion as $valor){
                  $old_historial = Historial::findOne($valor);
                  $old_historial->id_resolucion = $resolucion->id_resolucion;
                  $old_historial->save(false);
                }
                $session->set('array_id_historiales', null); 
              }              
              $radicado['estado'] = 3;
              $radicado->save(false);
              $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$model->id_entidad);
            }   
            
          }
          
        }else {
          Yii::$app->session->setFlash('ALERTA', "Las fechas de los periodos no coinciden".$a);
          return $this->render('create', [
            'model' => $model,
        ]);
        }
      } else {
          return $this->render('create', [
              'model' => $model,
          ]);
      }
    }else{
        $searchModel = new DignatariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $session = Yii::$app->session;
        if(User::IsAdministrador() || User::IsTramitador()){
          $session->set('editar',true);
        }else{
          $session->set('editar',false);
        }

        $id = $session->get('id_entidad');

        $dataProvider->query->andWhere(['id_entidad'=>$id]);
        $titulo = Entidades::findOne($id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => $titulo['nombre_entidad'],
            'msg' => "No puede realizar esta operación sin un radicado correspondiente",
        ]);

      }
    }

    private function UpdateHistory($Nombre_evento, $Nombre_campo, $Tabla_modificada, $model, $oldModel){
      $historial = new Historial();
      $historial->nombre_evento = $Nombre_evento;
      $historial->id_tabla_modificada = $model->id_dignatario;
      $tiempo = Carbon::now('America/Bogota');
      $historial->fecha_modificacion = $tiempo->toDateTimeString();
      $historial->nombre_campo_modificado = $Nombre_campo;
      $historial->valor_anterior_campo = $oldModel[$Nombre_campo];
      $historial->valor_nuevo_campo = $model[$Nombre_campo];
      $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
      $historial->tabla_modificada = $Tabla_modificada;
      $historial->save(false);
      return $historial;
    }

    private function UpdateResolucion($radicado, $entidad, $historial, $numero_resolucion, $año){
      $resolucion = new Resoluciones();
      $tiempo = Carbon::now('America/Bogota');
      $resolucion->id_tipo_resolucion = $radicado['id_tipo_resolucion']; // inserto el id correspondiente a cancelacion de personería jurídica NPI
      $resolucion->nombre_entidad = $entidad->nombre_entidad;
      $resolucion->id_entidad = $entidad->id_entidad;
      if($historial != null){
        $resolucion->id_historial = $historial['id_historial'];
        $historial->id_resolucion = $resolucion->id_resolucion;
        $historial->save(false);
      }      
      $resolucion->numero_resolucion = $numero_resolucion;
      $resolucion->ano_resolucion = $año;
      $resolucion->id_radicado = $radicado->id_radicado;
      $resolucion->fecha_creacion = $tiempo->toDateString();
      $resolucion->save(false);

      return $resolucion;
    }

    public function actionAnadirdignatario(){

      return $this->actionCreate($isAñadirDignatario = true); 
    }

    public function actionUpdate($id)
    {
      $session = Yii::$app->session;
      if(User::IsAdministrador() || User::IsTramitador()){
        $model = Dignatarios::findOne($id);
        $oldModel = Dignatarios::findOne($id);
    

        if ($model->load(Yii::$app->request->post())) {
            $tiempo = Carbon::now('America/Bogota');
            $fin = new DateTime($model['fin_periodo']);
            $inicio = new Datetime($model['inicio_periodo']);
            $interval = $fin->diff($inicio);
            $a= $interval->format('%R%a');
         
            if($a < 0){

              if($model['representante_legal'] == 1)$this->cambiarRepresentante($model->id_entidad);
              $model->save();

              if($model->cedula_dignatario != $oldModel->cedula_dignatario){
               
                $this->UpdateHistory("CAMBIO DE CÉDULA DIGNATARIO", "cedula_dignatario", "DIGNATARIOS", $model, $oldModel);
              
              }

              if($model->nombre_dignatario != $oldModel->nombre_dignatario){
                $this->UpdateHistory("CAMBIO DE NOMBRE DIGNATARIO", "nombre_dignatario", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->estado != $oldModel->estado){
                $this->UpdateHistory("CAMBIO DE ESTADO DIGNATARIO", "estado", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->id_municipio_expedicion != $oldModel->id_municipio_expedicion){
                $this->UpdateHistory("CAMBIO DE MUNICIPIO EXPEDICIÓN DIGNATARIO", "id_municipio_expedicion", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->fecha_ingreso != $oldModel->fecha_ingreso){
                $this->UpdateHistory("CAMBIO DE FECHA INGRESO DIGNATARIO", "fecha_ingreso", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->id_entidad != $oldModel->id_entidad){
                $this->UpdateHistory("CAMBIO DE ENTIDAD DIGNATARIO", "id_entidad", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->id_cargo != $oldModel->id_cargo){
                $this->UpdateHistory("CAMBIO DE CARGO DIGNATARIO", "id_cargo", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->id_grupo_cargos != $oldModel->id_grupo_cargos){
                $this->UpdateHistory("CAMBIO DE GRUPO CARGOS DIGNATARIO", "id_grupo_cargos", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->inicio_periodo != $oldModel->inicio_periodo){
                $this->UpdateHistory("CAMBIO DE FECHA INICIO PERIODO DIGNATARIO", "inicio_periodo", "DIGNATARIOS", $model, $oldModel);
              }


              if($model->fin_periodo != $oldModel->fin_periodo){
                $this->UpdateHistory("CAMBIO DE FECHA FIN PERIODO DIGNATARIO", "fin_periodo", "DIGNATARIOS", $model, $oldModel);
              }

              if($model->tarjeta_profesiona != $oldModel->tarjeta_profesiona){
                $this->UpdateHistory("CAMBIO DE N° TARJETA PROFESIONAL DIGNATARIO", "tarjeta_profesional", "DIGNATARIOS", $model, $oldModel);
              }
              return $this->redirect(['view', 'id' => $model->id_dignatario]);

            }else{
              Yii::$app->session->setFlash('ALERTA', "Las fechas de los periodos no coinciden".$a);
              return $this->render('update', [
              'model' => $model,              
            ]);
          }

        } else {
            return $this->render('update', [
                'model' => $model,
                              
            ]);
        }

      }else{
          $searchModel = new DignatariosSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
          $session = Yii::$app->session;
          if(User::IsAdministrador() || User::IsTramitador()){
            $session->set('editar',true);
          }else{
            $session->set('editar',false);
          }

          $id = $session->get('id_entidad');

          $dataProvider->query->andWhere(['id_entidad'=>$id]);
          $titulo = Entidades::findOne($id);
          return $this->render('index', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
              'titulo' => $titulo['nombre_entidad'],
              'msg' => "No puede realizar esta operación sin un radicado correspondiente",
          ]);

        }
    }
    
    protected function findModel($id)
    {
        if (($model = Dignatarios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

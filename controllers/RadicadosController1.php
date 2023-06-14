<?php

namespace app\controllers;

use Yii;
use app\models\Historial;
use app\models\Radicados;
use app\models\Entidades;
use app\models\Resoluciones;
use app\models\Entidadcamaracomercio;
use app\models\Certificados;
use app\models\Dignatarios;
use app\models\RadicadosSearch;
use app\models\TipoResolucionCheckBoxList;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\filters\AccessControl;
use app\models\TipoTramite;
use app\models\TramiteDevolucion;
use Carbon\Carbon;
use DateTime;
use yii\web\UploadedFile;
use mPDF;
use yii\data\ActiveDataProvider;
use app\models\TipoRegistroLibro;

class RadicadosController extends Controller
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
      
      $permisos = ['index','view','reporte','samplepdf','finalizado','vencido','sendmail','historial','lists','createMPDF','reportfiles','download','download1'];

      if(User::IsRepartidor() || User::IsRadicador() || User::IsAdministrador()){         
        $permisos = $this->verificarElemento('create',$permisos);
        $permisos = $this->verificarElemento('update',$permisos);
      } 

      if(User::IsTramitador() || User::IsAdministrador()){      
        $permisos = $this->verificarElemento('update',$permisos);
        $permisos = $this->verificarElemento('tramitar',$permisos);
        $permisos = $this->verificarElemento('devolucion',$permisos);        
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
        $session = Yii::$app->session;
        $searchModel = new RadicadosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $entidades = Entidades::find()->asArray()->all();
        $mensaje = $session->get('mensaje');
        $session->remove('mensaje');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'msg'=> $mensaje,
            'entidades' => $entidades,
        ]);
    }

    public function actionView($id)
    {
      $session = Yii::$app->session;
      $x = $session->get('id');
      $session->set('id_radicado',$id);
      $modelo = $this->findModel($id);
      $session->set('id_entidad',$modelo->id_entidad);
      $radicado = Radicados::findOne($id);

      if($x != 'x' && $radicado['estado'] != 3  && $radicado['estado'] != 4){
        $texto = $id." Tramite: ".$radicado->getTipoTramite();
        $session->set('id',$texto);
      }
      $msg = $session->get('msg');
      $session->set('msg',null);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'msg'=> $msg,
        ]);
    }

    public function saveModel($model)
    { 

    if($model['id_tipo_resolucion_combinada'] != null){
      $model->id_tipo_resolucion_combinada = implode($delimiter = ',', $model->arrayTipoResolucionCombinada);
    }   
    if($model['id_tipo_reforma_estatutaria'] != null){
      $model->id_tipo_reforma_estatutaria = implode($delimiter = ',', $model->arrayTipoReformaEstatutos);
    } 

    if($model['id_tipo_registro_libro'] != null){
      $model->id_tipo_registro_libro = implode($delimiter = ',', $model->arrayTipoRegistroLibro);
    } 

    return $model->save(false);    
    }

    public function actionCreate()
    {
        $model = new TipoResolucionCheckBoxList();
        
       
        $model->fecha_creacion = Carbon::now('America/Bogota')->toDateTimeString();
        $model->estado = 1;
        $model->id_usuario_creacion = Yii::$app->user->identity->id;
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post()) && $this->saveModel($model)) {
          $model->file = UploadedFile::getInstances($model, 'file');
          if( $model->file != null){
            if($model->id_entidad !=null){
            $i=0;
            foreach ($model->file as $file) {
              $i=$i+1;
              if(file_exists("/var/DocJuridica/".$model->id_entidad)){
              if(file_exists("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado)){
              $file->saveAs( "/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado.'/Documento del Radicado '.$model->id_radicado.'-'.$i. '.' . $file->extension);
              }else{ 
              mkdir("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado,0777);
              chmod("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado,0777);
              $file->saveAs( "/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado.'/Documento del Radicado '.$model->id_radicado.'-'.$i. '.' . $file->extension);
            }
            }else{
              mkdir("/var/DocJuridica/".$model->id_entidad,0777);
              chmod("/var/DocJuridica/".$model->id_entidad,0777);
              mkdir("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado,0777);
              chmod("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado,0777);
              $file->saveAs( "/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado.'/Documento del Radicado ' .$model->id_radicado.'-'.$i. '.' . $file->extension);
            }

          }
        }
              $i=0;
              foreach ($model->file as $file) {
                $i=$i+1;
                if(file_exists("/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado)){
                  $file->saveAs( "/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado.'/Documento del Radicado '.$model->id_radicado.'-'.$i. '.' . $file->extension);
                }else{
                  mkdir("/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado,0777);
                  chmod("/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado,0777);
                  $file->saveAs( "/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado.'/Documento del Radicado '.$model->id_radicado.'-'.$i. '.' . $file->extension);
                }
              }

            }
            $model->file = null;            
            $this->actionSendmail($model);          
            
            return $this->redirect(['view', 'id' => $model->id_radicado]);
        } else {
          $entidades = Entidades::find()->asArray()->all();
          for ($i=0; $i < count($entidades) ; $i++) {
            $entidades[$i]['nombre_entidad'] = $entidades[$i]['nombre_entidad'].' - '.$entidades[$i]['personeria_year'].'-'.$entidades[$i]['personeria_n'];
          }
            return $this->render('create', [
                'model' => $model,
                'entidades' =>$entidades,
            ]);
        }
    }

    public function actionSendmail($model){
    
      $destinatario = User::findOne($model->id_usuario_tramita);
      if($destinatario != null){
        $TipoTramite = TipoTramite::findOne($model->id_tipo_tramite)['descripcion'];
        $estadoRadicado = "";
        switch($model->estado){

          case 1:
          $estadoRadicado = "Reparto";
          break;

          case 2:
          $estadoRadicado = "Tramite";
          break;

          case 3:
          $estadoRadicado = "Finalizado";
          break;

          case 4:
          $estadoRadicado = "Devolución";
          break;

          case 5:
          $estadoRadicado = "Vencido";
          break;

          case 6:
          $estadoRadicado = "Cancelado";
          break;

          case 7:
          $estadoRadicado = "Validado";
          break;

        }
        Yii::$app->mailer->compose('radicadomail.php',[
          'nradicado' => $model->id_radicado,
          'tipoRadicado' => $TipoTramite,
          'estadoRadicado' =>$estadoRadicado,
          'imageFileName' => '/var/www/html/personerias/web/img/logo2.png',
        ])          
           ->setTo($destinatario->email) 
           ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])          
           ->setSubject("Portal Personerias - Asignación de radicado")              
           ->send();
      }
    }

    public function actionUpdate($id)
    {
      $modificar = false;      
      $model = TipoResolucionCheckBoxList::findOne($id); 
      $session = Yii::$app->session;  

      switch ($model->estado) {

        case 1: // ESTADO REPARTO
          $modificar = true;
          break;

        case 2: // ESTADO TRAMITE
          if(Yii::$app->user->identity->id == $model->id_usuario_tramita){
            $modificar = true;
          }else{
            $mensaje = "No se puede modificar el radicado N° $model->id_radicado porque se encuentra en tramite y no eres el funcionario asignado a este";
          }
          break;
          
        case 3: // ESTADO FINALIZADO
          $mensaje ="No se puede modificar el radicado N° $model->id_radicado porque su estado es finalizado";  
          break;
        
        case 4: // ESTADO DEVOLUCION
          if(Yii::$app->user->identity->id == $model->id_usuario_tramita){
            $modificar = true;
          }else{
            $mensaje = "No se puede modificar el radicado N° $model->id_radicado porque se encuentra en devolución y no eres el funcionario asignado a este";
          }
          break;

        case 5: // ESTADO VENCIDO 
          $mensaje ="No se puede modificar el radicado N° $model->id_radicado porque ha vencido";  
          break;

        case 6: // ESTADO CANCELADO 
          $mensaje ="No se puede modificar el radicado N° $model->id_radicado porque ha sido cancelado";  
          break;

        case 7: // ESTADO VALIDADO 
            $mensaje ="No se puede modificar el radicado N° $model->id_radicado porque ya ha sido validado";  
            break;
      }

      if(User::IsAdministrador()){
        $modificar = true;
      }
      if($modificar){

        $oldModel = TipoResolucionCheckBoxList::findOne($id);  
        
        if ($model->load(Yii::$app->request->post()) && $this->saveModel($model) ) {
      ////////////////////////////////////////// POR AHORA 16/10/2019 ///////////////////////////////////////////////////////////////////

          $model->id_tipo_resolucion_combinada = $oldModel->id_tipo_resolucion_combinada;
          $model->id_tipo_reforma_estatutaria = $oldModel->id_tipo_reforma_estatutaria;
          $model->id_tipo_registro_libro = $oldModel->id_tipo_registro_libro;
          $model->id_dignatario_tramite = $oldModel->id_dignatario_tramite;
          $model->save(false);
          //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
          $model->file = UploadedFile::getInstance($model, 'file');

          if( $model->file != null){

            if(file_exists($model->id_entidad)){
              $model->file->saveAs( $model->id_entidad.'/Radicado ' .$model->id_radicado. '.' . $model->file->extension);
            }else{
              $model->file->saveAs( 'radicados/Radicado '.$model->id_radicado.'.'. $model->file->extension);
            }

          }
          $model->file = null;            

          if($model->id_tipo_tramite != $oldModel->id_tipo_tramite){
            $this->UpdateHistory("CAMBIO DE TIPO DE TRÁMITE RADICADO", "id_tipo_tramite", "RADICADOS", $model, $oldModel);
          }

          if($model->id_entidad != $oldModel->id_entidad){
            $this->UpdateHistory("CAMBIO DE ENTIDAD RADICADO", "id_entidad", "RADICADOS", $model, $oldModel);
          }

          if($model->descripcion != $oldModel->descripcion){
            $this->UpdateHistory("CAMBIO DE DESCRIPCIÓN RADICADO", "descripcion", "RADICADOS", $model, $oldModel);
          }

          if($model->n_radicado_interno != $oldModel->n_radicado_interno){
            $this->UpdateHistory("CAMBIO DE N° RADICADO INTERNO", "n_radicado_interno", "RADICADOS", $model, $oldModel);  
          }

          if($model->estado != $oldModel->estado){
           $this->UpdateHistory("CAMBIO DE ESTADO RADICADO", "estado", "RADICADOS", $model, $oldModel);   
           $this->actionSendmail($model);
          
          }

          if($model->id_usuario_tramita != $oldModel->id_usuario_tramita){
            $this->UpdateHistory("CAMBIO DE USUARIO QUE TRAMITA EL RADICADO", "id_usuario_tramita", "RADICADOS", $model, $oldModel);              
          }

          if($model->sade != $oldModel->sade){
            $this->UpdateHistory("CAMBIO DE N° SADE", "sade", "RADICADOS", $model, $oldModel);
          }
         
          
          return $this->redirect(['view', 'id' => $model->id_radicado]);
          
        } else {          
          $entidades = Entidades::find()->asArray()->all();
          for ($i=0; $i < count($entidades) ; $i++) {
            $entidades[$i]['nombre_entidad'] = $entidades[$i]['nombre_entidad'].' - '.$entidades[$i]['personeria_year'].'-'.$entidades[$i]['personeria_n'];
          }
            return $this->render('update', [
                'model' => $model,
                'entidades' => $entidades,
            ]);
        }
      }else {
              $session = Yii::$app->session;
              $session->set('mensaje',$mensaje);
              return $this->redirect(['index',
              'searchModel' => null,
              'dataProvider' => null]);

      }

    }

    protected function findModel($id)
    {
        if (($model = Radicados::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionHistorial($id)
    {
        $this->redirect(Yii::$app->request->baseUrl."?r=historial%2Findex3&id=".$id);
    }

    public function actionTramitar($id){

      $radicado = Radicados::findOne($id);
      $prueba = Radicados::find()->where(['and', ['id_radicado' =>$id ],[ 'or',['estado' => 3],['estado' => 4]]])->one();
      if(!$prueba){
      $session = Yii::$app->session;
      $x = $session->get('id');
      $array =  $session->get('radicados');
      settype($array,'array');
      if(count($array) == 0 && !$radicado){
          $radicado = array();
          array_push($radicado,$id);
          $session->set('radicados',$radicado);
      }
      else
      {
          $pregunta = true; // preguntar si el id ya esta en la variable de session
                            // si ya esta no agregarlo
          for ($i=0; $i <count($array) ; $i++) {
            if($id == $array[$i]){
              $pregunta = false;
            }
          }
          if($pregunta){
            array_push($array,$id);
          }
          $session->set('radicados',$array);
      }
      
      $entidad = Entidades::findOne($radicado->id_entidad);
      $tipo_entidad = null;
      if($entidad != null){
        $tipo_entidad = $entidad->id_tipo_entidad;
      }
      
      switch ($radicado->id_tipo_tramite) 
        {
          // SELECCION DE CERTIFICADOS
          case 1:
          $this->CrearCertificado($radicado);           
          break;

          // SELECCION DE RESOLUCIONES
          case 2:

          if(($radicado->id_entidad == null && $radicado['id_tipo_resolucion'] == 1) || ($radicado->id_entidad != null && $radicado['id_tipo_resolucion'] != 1)) 
          { //validar que tengan entidad si no es un tramite de reconocimiento de personeria

            switch($radicado['id_tipo_resolucion'])
            {
              case 1: // RECONOCIMIENTO DE PERSONERIA
                $session = Yii::$app->session;
                $session->set('radicado',$radicado->id_radicado);
                $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fcreate&id=".$radicado->id_entidad);
              break;

              case 2: // CANCELACION DE PERSONERIA A PETICION o QUEJA
                $this->UpdateCancelar($radicado);
              break;

              case 3: // ASIMILAR PERSONERIA
              $session = Yii::$app->session;
              $session->set('radicado',$radicado->id_radicado);
              $entidad = Entidades::findOne($radicado->id_entidad);
              $oldModel = $entidad;
              $tiempo = Carbon::now('America/Bogota');
              $año = $tiempo->year;
              $tipo_entidad = $entidad->id_tipo_entidad;

              if($tipo_entidad == 12){

                $entidad->estado_entidad = 2;
                $entidad->save(false);
                $model = $entidad;

                $historial = $this->UpdateHistory("CAMBIO DE ASIMILACION", "nombre_entidad","ENTIDADES", $model, $oldModel);                
                $session->set('id_historial_asimilacion',$historial->id_historial);         

                $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fcreate&id=".$radicado->id_entidad);
              }else{
                $mensaje = "No se puede realizar el trámite correspondiente porque la entidad seleccionada no es una Junta de Vivienda Comunitaria";
                  return $this->render('view', [
                  'model' => $this->findModel($id),
                  'mensaje' => $mensaje,
              ]);
              }
                // cambio de junta coMuNitaria a JAC = RECONO PERSONERIA
              break;

              case 4: // CANCELACION DE PERSONERIA A QUEJA
                $this->UpdateCancelar($radicado);
              break; 

              case 5: // CAMBIO DE OBJETO SOCIAL
                  
              $session = Yii::$app->session;
              $session->set('radicado',$radicado->id_radicado);
              $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fupdate&id=".$radicado->id_entidad);   
              
              break;

              case 6: // IMPUGNACION Preguntar si se cancela la entidad
              $session = Yii::$app->session;
              $session->set('radicado',$radicado->id_radicado);
              $entidad = Entidades::findOne($radicado->id_entidad);
              $tiempo = Carbon::now('America/Bogota');
              $año = $tiempo->year;
              $tipo_entidad = $entidad->id_tipo_entidad;

              if($tipo_entidad == 9){
                $radicado['estado'] = 3;
                $radicado->save(false);  
                $model = $entidad;
                $historial = null;
                list($model, $numero_resolucion) = $this->NumeroResolucion($año, $model);
                $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);
                $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$entidad->id_entidad);
              }
              else{
                $mensaje = "No se puede realizar el trámite correspondiente porque la entidad seleccionada no es una Junta de Acción Comunal";
                  return $this->render('view', [
                  'model' => $this->findModel($id),
                  'mensaje' => $mensaje,
              ]);
              }
                  // Crear nuevo formatos de reclamos.
              break;

              case 7: // ADHOC  1064 = id secretariado, 119 = id presidente adhoc
                      //Crear formato para añadir presidente y secretariado adhoc.
                          
              $session = Yii::$app->session;
              $session->set('radicado',$radicado->id_radicado);
              $session->set('ADHOC', true);
              $this->redirect(Yii::$app->request->baseUrl."?r=dignatarios%2Fcreate&id=".$radicado->id_entidad);
              break;

              case 8: // CANCELACION DE DIGNATARIOS
                    // Cancelacion de algun miembro de la entidad,(Redireccionar al buscar dignatario).
              $session = Yii::$app->session;
              $session->set('radicado',$radicado->id_radicado);
              $dignatario_tramite = Dignatarios::findOne($radicado->id_dignatario_tramite);
              $tiempo = Carbon::now('America/Bogota');
              $año = $tiempo->year;               
              $oldModel = $dignatario_tramite;

              $dignatario_tramite['estado'] = 0;
              $dignatario_tramite->save(false);

              $model = $dignatario_tramite;
              $entidad = Entidades::findOne($radicado->id_entidad);
              $radicado['estado'] = 3;
              $radicado->save(false);
              $historial = $this->UpdateHistory("CAMBIO DE ESTADO DIGNATARIO", "estado", "DIGNATARIOS", $model, $oldModel);
              list($model, $numero_resolucion) = $this->NumeroResolucion($año, $entidad);
              $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);
              $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$entidad->id_entidad);

              break;

              case 9: // COMBINADO: INSCRIPCION - REFORMA - REGISTRO DE LIBROS
                $TiposDeResolucion = explode(",",$radicado['id_tipo_resolucion_combinada']);
                $var_inscripcionDignatarios = in_array(1, $TiposDeResolucion);
                $var_reformaEstatutos = in_array(2, $TiposDeResolucion);
                $var_registroLibros = in_array(3, $TiposDeResolucion);
                $array_id_historial_sin_resolucion = array();
                
                
                if($var_registroLibros){
                  $session = Yii::$app->session;
                  $session->set('radicado',$radicado->id_radicado);
                  
                  $tiempo = Carbon::now('America/Bogota');

                  $TiposLibros = explode(",", $radicado['id_tipo_registro_libro']);
                    if(!empty($TiposLibros)){
                      $NombreEvento = "Registro de ";
                      foreach($TiposLibros as $valor){
                      
                        $NombreLibro = TipoRegistroLibro::findOne($valor)['nombre_tipo_registro_libro'];
                        $NombreEvento .= $NombreLibro.", ";
  
                      }
                      $historial = new Historial();
                      $historial->nombre_evento = $NombreEvento;
                      $historial->id_tabla_modificada = $radicado->id_entidad;
                      $tiempo = Carbon::now('America/Bogota');
                      $historial->fecha_modificacion = $tiempo->toDateTimeString();                    
                      $historial->id_usuario_modifica = Yii::$app->user->identity->id;
                      $historial->tabla_modificada = "REGISTRO DE LIBROS";
                      $historial->save(false);
                      array_push($array_id_historial_sin_resolucion, $historial->id_historial);

                      if(!$var_inscripcionDignatarios && !$var_reformaEstatutos){
                        $tiempo = Carbon::now('America/Bogota');
                        $año = $tiempo->year;  
                        $historial = null;
                        $entidad = Entidades::findOne($radicado->id_entidad);
                        $radicado['estado'] = 3;
                        $radicado->save(false);
                        list($model, $numero_resolucion) = $this->NumeroResolucion($año, $entidad);
                        $this->UpdateResolucion($radicado, $model, $historial, $numero_resolucion, $año);                                           
                        $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$entidad->id_entidad);
                      }
                      
    
                    }else if(!$var_inscripcionDignatarios && !$var_reformaEstatutos){
                      $mensaje = "No se puede realizar el trámite correspondiente porque no registro ningún tipo de libro";
                      return $this->render('view', [
                          'model' => $this->findModel($id),
                          'mensaje' => $mensaje,
                        ]);
                    }
                  }
                  if($var_inscripcionDignatarios)
                  { 
                    $session = Yii::$app->session;
                    $session->set('id_radicado',$radicado->id_radicado);  
                    if($var_reformaEstatutos) $session->set('isInscripcionDignatario',true);
                    else $session->set('isInscripcionDignatario',false);
                    if(!empty($array_id_historial_sin_resolucion)){
                      $session->set('array_id_historiales',$array_id_historial_sin_resolucion); 
                    }        
                    $this->redirect(Yii::$app->request->baseUrl."?r=dignatarios%2Fcreate&id=".$radicado->id_entidad);
                  }else
                  if($var_reformaEstatutos)
                  {           
                    $session = Yii::$app->session;
                    $session->set('radicado',$radicado->id_radicado);
                    if(!empty($array_id_historial_sin_resolucion)){
                      $session->set('array_id_historiales',$array_id_historial_sin_resolucion); 
                    }
                    $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fupdate&id=".$radicado->id_entidad);            
                                  
                  }

              break;
            }
            
          }// fin de if que valida que haya un entidad si no es un tramite de reconocimiento
          else
          {
              $mensaje = "No se puede realizar el trámite correspondiente porque no hay una entidad asociada al radicado";
              return $this->render('view', [
                  'model' => $this->findModel($id),
                  'mensaje' => $mensaje,
              ]);
          }
        break;

        }// fin de switch
        
      }
      else
      {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
      }
    }

    private function UpdateHistory($Nombre_evento, $Nombre_campo, $Tabla_modificada, $model, $oldModel){
      $historial = new Historial();
      $historial->nombre_evento = $Nombre_evento;
      if($Tabla_modificada = "ENTIDADES"){
		$historial->id_tabla_modificada = $model->id_entidad;
	  }elseif ($Tabla_modificada = "DIGNATARIOS") {
		$historial->id_tabla_modificada = $model->id_dignatario;
	  }else{
		$historial->id_tabla_modificada = $model->id_radicado;
	  }
      $tiempo = Carbon::now('America/Bogota');
      $historial->fecha_modificacion = $tiempo->toDateTimeString();
      $historial->nombre_campo_modificado = $Nombre_campo;
      $historial->valor_anterior_campo = $oldModel[$Nombre_campo];
      $historial->valor_nuevo_campo = $model[$Nombre_campo];
      $historial->id_usuario_modifica = Yii::$app->user->identity->id;
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
    }

    private function CrearCertificado($radicado){

      $session = Yii::$app->session;
      $session->set('radicado',$radicado->id_radicado);
      

      $tiempo = Carbon::now('America/Bogota');
      $año = $tiempo->year; 

      $certificado = new Certificados();
      $certificado->ano_certificado = $año;
      $certificado->fecha_creacion = $tiempo->toDateString();
      $certificado->id_tipo_certificado = $radicado['id_tipo_certificado'];
      $certificado->id_radicado = $radicado->id_radicado;
      $radicado['estado'] = 3;
      $radicado->save(false);
      
      if($radicado['id_tipo_certificado'] == 5){
  
       switch($radicado['id_entidadcg']){
        case 1:
        $entidad = Entidades::findOne($radicado->id_entidad);
        $certificado->id_entidad = $entidad->id_entidad;
        $certificado->save(false);
        $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$entidad->id_entidad);
        break;

        case 2:
        $entidad_camara = Entidadcamaracomercio::findOne($radicado->id_entidad_camara);
        $certificado->id_entidad_camara = $entidad_camara->id_entidad_camara;
        $certificado->save(false);
        $this->redirect(Yii::$app->request->baseUrl."?r=entidadcamaracomercio%2Fview&id=".$entidad_camara->id_entidad_camara);
        break;

        case 3:
        $certificado->save(false);
        $this->redirect(Yii::$app->request->baseUrl."?r=entidadcamaracomercio%2Fcreate&id=".$certificado->id_certificado);
        break;
       }

      }else{
        $entidad = Entidades::findOne($radicado->id_entidad);
        $certificado->id_entidad = $entidad->id_entidad;
        $certificado->save(false);
        $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$entidad->id_entidad);
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

    private function UpdateCancelar($radicado)
    {
      $session = Yii::$app->session;
      $session->set('radicado',$radicado->id_radicado);
       
        $entidad = Entidades::findOne($radicado->id_entidad);
        $oldModel = $entidad;
        $entidad['estado_entidad'] = 2; //inactivo la entidad 
        $entidad->save(false);
        //creo la resolucion
        $model = $entidad;

        $ultima_resolucion = Resoluciones::findOne(Resoluciones::find()->max('id_resolucion'));
        $numero_resolucion = 1;
        $tiempo = Carbon::now('America/Bogota');
        $año = $tiempo->year;
        if($ultima_resolucion['ano_resolucion'] == $año){
            $numero_resolucion = $ultima_resolucion['numero_resolucion'];
            $numero_resolucion = $numero_resolucion + 1;

        }
        
        $historial = $this->UpdateHistory("CAMBIO DE ESTADO ENTIDAD", "estado_entidad", "ENTIDADES", $model, $oldModel);

        if($entidad->estado_entidad == 2){
          $this->UpdateResolucion($radicado, $entidad, $historial, $numero_resolucion, $año);
        }
        //Entidades::actionView($entidad->id_entidad);
        $radicado['estado'] = 3;
        $radicado->save(false);
        $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$entidad->id_entidad);

    }
  
    public function actionLists($id){
    
      $countDignatarios = Dignatarios::find()
      ->where(['id_entidad'=>$id])
      ->count();

      $dignatarios = Dignatarios::find()
      ->where(['and', ['id_entidad'=>$id], ['estado' => 1]])
      ->all();

      if($countDignatarios>0){

        foreach($dignatarios as $dignatario){
          echo "<option value='".$dignatario->id_dignatario."'>".$dignatario->nombre_dignatario."</option>";
        }

      }else{
        echo"<option>-</option>";
      }

    }


    public function actionReporte($selection, $Full = false){

      if($Full == false){
            $provider =  new \yii\data\ActiveDataProvider([
                'query' => Radicados::find()->where(['id_radicado' => $selection]),

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
      $radicados = $provider->getModels();
    //  $tramites = array();
    //  $usuarios = array();

      $html = "

          <title> Radicados </title>

          <table style="."width:100%".">
          <tr>
              <td width='150'>".utf8_decode('N° RADICADO')."</td>
              <td width='150'>".utf8_decode('N° SADE')."</td>
              <td width='150'>".utf8_decode('DESCRIPCIÓN')."</td>
              <td width='150'>".utf8_decode('TIPO TRÁMITE')."</td>
              <td width='150'>".utf8_decode('ESTADO TRÁMITE')."</td>
              <td width='150'>".'USUARIO QUE TRAMITA'."</td>
          </tr> ";

      for($i =0; $i < sizeof($radicados);$i++){
          $user = User::findOne($radicados[$i]['id_usuario_tramita']);
          $tramite = TipoTramite::findOne($radicados[$i]['id_tipo_tramite']);
          $estado ;
          switch ($radicados[$i]['estado']) {
            case 1:
              $estado = 'Reparto';
              break;
            case 2:
              $estado = 'Trámite';
              break;
            case 3:
              $estado = 'Finalizado';
              break;
            case 4:
              $estado = 'Devolucion';
              break;
            case 5:
             $estado = 'Vencido';
              break;
            case 6:
              $estado = 'Cancelado';
                break;
            case 7:
            $estado = 'Validado';
            break;
          }
          $html =$html."
          <tr>
              <td width='150'>".$radicados[$i]['id_radicado']."</td>
              <td width='150'>".$radicados[$i]['sade']."</td>
              <td width='300'>".utf8_decode($radicados[$i]['descripcion'])."</td>
              <td width='300'>".utf8_decode($tramite['descripcion'])."</td>
              <td width='150'>".utf8_decode($estado)."</td>
              <td width='300'>".utf8_decode($user['nombre_funcionario'])."</td>

          </tr>
          ";

      }

      $html = $html."</table>";

      header("Content-Type:application/vnd.ms-excelxls");
      header("Content-disposition:attachment; filename=Reporte_radicados.xls");
      echo $html;
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


        $mpdf->SetTitle('Radicados'); //Título

        $mpdf->AddPageByArray(array(
            'sheet-size' => 'Letter',
            'resetpagenum' => '1',
            'pagenumstyle' => '1',
        ));
        /*Texto, aqui se escriben las páginas*/
        if($Full == false){
            $provider =  new \yii\data\ActiveDataProvider([
                'query' => Radicados::find()->where(['id_radicado' => $selection]),

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
        $radicados = $provider->getModels();

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

        <h1>Radicados</h1>

          <table style="."width:100%".">
               <thead>
                  <tr>
                      <th width='150'>".'N° RADICADO'."</th>
                      <th width='150'>".'N° SADE'."</th>
                      <th width='300'>".'TIPO TRÁMITE'."</th>
                      <th width='150'>".'ESTADO TRÁMITE'."</th>
                      <th width='150'>".'USUARIO TRAMITA'."</th>

                  </tr>
                </thead>";


          for($i =0; $i < sizeof($radicados);$i++){
            $user = User::findOne($radicados[$i]['id_usuario_tramita']);
            $tramite = TipoTramite::findOne($radicados[$i]['id_tipo_tramite']);
            $estado ;
            switch ($radicados[$i]['estado']) {
              case 1:
                $estado = 'Reparto';
                break;
              case 2:
                $estado = 'Trámite';
                break;
              case 3:
                $estado = 'Finalizado';
                break;
              case 4:
                $estado = 'Devolucion';
                break;
              case 5:
               $estado = 'Vencido';
                break;
              case 6:
                $estado = 'Cancelado';
                  break;
              case 7:
                $estado = 'Validado';
              break;

          }
            $html =$html."
            <tbody>
              <tr>
                  <td width='150'>".$radicados[$i]['id_radicado']."</td>
                  <td width='150'>".$radicados[$i]['sade']."</td>
                  <td width='300'>".$tramite['descripcion']."</td>
                  <td width='150'>".$estado."</td>
                  <td width='150'>".$user['nombre_funcionario']."</td>
              </tr>

            </tbody>
            ";

        }
        $html = $html."</table>";
        $mpdf->WriteHTML($html);

        /*Fin de las páginas*/

        /*Se da la salida del PDF*/
        //$mpdf->Output();
        $mpdf->Output('Reporte radicados.pdf','D'); //Para que descargue automaticamente
        exit;
    }

    private function downloadFile($dir, $file, $extensions=[])
    {
    $path = pathinfo($dir);
    $ruta = $path['dirname'];
    //$extensions = $path['extension'];
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
      }

      //}
     
     //Ha ocurrido un error al descargar el archivo
     //echo $dir;
     //echo $ruta;
     //echo $nombre;
     //echo $exte;
     //echo $file;
    //}

    public function actionDownload($file)
    {
      $session = Yii::$app->session;
     if (Yii::$app->request->get("file"))
     {
        if (!$this->downloadFile('/var/DocJuridica/Reconocimientos/Radicado'.Yii::$app->request->get('radicado').'/'. Yii::$app->request->get('file'), ["pdf", "docx","doc"]) )
        {
         //Mensaje flash para mostrar el error
         Yii::$app->session->setFlash("error");
        }



     }

     return $this->render('view', [
         'model' => $this->findModel(Yii::$app->request->get('radicado')),
     ]);
    }

    public function actionDownload1()
    {
      $session = Yii::$app->session;
      $id = $session->get('id_entidad');
      $idRadicado = $session->get('id_radicado');
      $radicado = Radicados::findOne($idRadicado);
      $entidad = Entidades::findOne($id);
      if (Yii::$app->request->get("file"))
      {



        if (!$this->downloadFile('/var/DocJuridica/'.$entidad['id_entidad']."/", "Radicado $radicado->id_radicado", ["pdf", "docx","doc"]) )
        {
         //Mensaje flash para mostrar el error

         Yii::$app->session->setFlash("error");
        }



     }

     return $this->render('view', [
         'model' => $radicado,
     ]);
    }

    public function actionFinalizado(){

        if(Yii::$app->request->post('id')){
          $id = Yii::$app->request->post('id');

        $radicado = Radicados::findOne($id);
        if($radicado){
          $tiempo = Carbon::now('America/Bogota');
          $historial = new Historial();
          $historial->nombre_evento = "CAMBIO DE ESTADO RADICADO";
          $historial->id_tabla_modificada = $radicado->id_radicado;
          $historial->fecha_modificacion = $tiempo->toDateTimeString();
          $historial->nombre_campo_modificado = "estado";
          $historial->valor_anterior_campo = $radicado->estado;
          $historial->valor_nuevo_campo = 3;
          $historial->id_usuario_modifica = Yii::$app->user->identity->id ;
          $historial->tabla_modificada = "RADICADOS";
          $historial->save(false);
          $radicado->estado = 3;
          $radicado->save(false);

          $session = Yii::$app->session;
          $radicados = $session->get('radicados');
          $nradicados = count($radicados);
          for ($i=0; $i < $nradicados; $i++) {
            if($radicado->id_radicado == $radicados[$i]){
              unset($radicados[$i]);
            }
          }

          if($radicados){
            $nuevo = array_values($radicados);
            $session->set('radicados',$nuevo);
          }else{
            $session->set('radicados',array());
          }
        }
        }
    }

    public function actionDevolucion($id){

      $radicado = Radicados::findOne($id);
      $devolucion = new TramiteDevolucion();
      $devolucion->id_radicado = $id;
      $devolucion->id_entidad = $radicado->id_entidad;
      $devolucion->save(false);

      $radicado->estado = 3;
      $radicado->save(false);
      $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$radicado->id_entidad);      
    }


     public function actionReportfiles(){

        $selection=(array)Yii::$app->request->post('selection');//typecasting

        switch (\Yii::$app->request->post('submit')) {
            case 'PDFSubmit':
                  if($selection != null){
                    $this->actionSamplepdf($selection);
                  }else{
                    Yii::$app->session->setFlash('Error', "No ha seleccionado ninguna casilla, intente generar el reporte completo.");
                    $this->redirect(["radicados/index"]);
                  }

              break;

            case 'ExcelSubmit':
              if($selection != null){
                  $this->actionReporte($selection);
                  }else{
                    Yii::$app->session->setFlash('Error', "No ha seleccionado ninguna casilla, intente generar el reporte completo.");
                    $this->redirect(["radicados/index"]);
                  }

              break;

            case 'PDFSubmitFull':
                  $Full = true;
                  $this->actionSamplepdf(null, $Full);
              break;

            case 'ExcelSubmitFull':
                  $Full = true;
                  $this->actionReporte(null, $Full);
              break;
          }
        }

}

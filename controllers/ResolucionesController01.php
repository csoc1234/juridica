<?php

namespace app\controllers;

use Yii;
use app\models\Resoluciones;
use app\models\Certificados;
use app\models\Entidades;
use app\models\Radicados;
use app\models\Dignatarios;
use app\models\Historial;
use app\models\Valores;
use app\models\Municipios;
use app\models\Departamentos;
use app\models\ResolucionesSearch;
use app\models\Entidadcamaracomercio;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\TipoResolucion;
use app\models\User;
use app\models\Cargos;
use app\models\GruposCargos;
use yii\filters\AccessControl;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use app\models\TipoEntidad;
use app\models\Profesional;
use app\models\TipoRegimen;
use app\models\TipoRegistroLibro;
use app\models\TramiteDevolucion;

class ResolucionesController extends Controller
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
      
      $permisos = ['index','view', 'devolucion'];
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

    public function actionIndex($id)
    {

        $searchModel = new ResolucionesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_tipo_resolucion'=>$id]);
        $titulo = TipoResolucion::findOne($id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => $titulo['nombre_tipo_resolucion'],
        ]);

    }

    public function actionDevolucion($id){
      $devolucion = TramiteDevolucion::findOne($id);
      $radicado = Radicados::findOne($devolucion['id_radicado']);
      $entidad = Entidades::findOne($devolucion['id_entidad']);
      $municipio_entidad = Municipios::findOne($entidad['municipio_entidad']);
      $departamento_entidad = Departamentos::findOne($municipio_entidad['departamento_id']);
      $tipoEntidad = TipoEntidad::findOne($entidad['id_tipo_entidad']);
      $nombre_entidad = $entidad['nombre_entidad'];
      $retencion_documental = $tipoEntidad['codigo_trd'];
      
      
      $presidente = Dignatarios::find()->where(['and', ['id_entidad'=>$entidad['id_entidad']],['representante_legal' => 1],['estado' => 1] ])->orderBy(['fin_periodo' => SORT_DESC])->limit(1)->one(); 

        if($presidente != null)
        {
          $municipio_presidente  = Municipios::findOne($presidente['id_municipio_expedicion']);
          $departamento_presidente  = Departamentos::findOne($municipio_presidente['departamento_id']);
        }
      
      $var_ref = "";
      $var_disciplina = "";
      $var_leyes = "";
      //SI ES DEPORTIVA O LIGA
      if($tipoEntidad['id_tipo_entidad'] == 10 || $tipoEntidad['id_tipo_entidad'] == 6){
        $var_ref .= "Ref:   Inspección Vigilancia y Control _____________________________";
        $var_disciplina .= "EN LA DISCIPLINA DE _________________";
        $var_leyes .= "Decreto 525 de 1990 (compilado por el Decreto 1075 de 2015), ley 181 de 1995, Decreto ley 1228 de 1995, ley 1946 del 4 enero de 2019, ley 1967 del 11 de julio del 2019 (compilados por el Decreto Único Reglamentario 1085 de 2015).";
      }

      // SE VERIFICA SI ES ORGANO COMUNAL
      elseif($tipoEntidad['id_tipo_entidad'] == 9 || $tipoEntidad['id_tipo_entidad'] == 12 || $tipoEntidad['id_tipo_entidad'] == 14 || $tipoEntidad['id_tipo_entidad'] == 48){

        $var_leyes .= "Ley 743 de 2002, Decreto Reglamentario 2350 de 2003, Decreto Reglamentario 890 de 2008, Circular EXT. CIR09-156-DDP-0210";
        
      }
      // SE VERIFICA SI ES UNA ENTIDAD DE SALUD
      elseif($tipoEntidad['id_tipo_entidad'] == 8){
        $var_leyes .= "Decreto 1088 de 1981, ley 10 de 1990, resolución 13565 del 1991 (compilados por el decreto único reglamentario 780 del 2016)";
       
      }
      // SE VERIFICA SI ES UNA ENTIDAD EDUCATIVA
      elseif($tipoEntidad['id_tipo_entidad'] == 5){
        $var_leyes .= "Decreto 525 de 1990 (compilado por el Decreto 1075 de 2015), ley 115 de 1994 (compilado por el decreto 1075 de 2015)";
        
      }
  
       $var_asunto = "";
       $var_tipo_radicado = $radicado->id_tipo_tramite;

       switch ($var_tipo_radicado){

        case 1:
          $var_tipo_certificado = $radicado->id_tipo_certificado;

          switch($var_tipo_certificado){

            case 1:
            $var_asunto .= "Certificado histórico";
            break;

            case 2:
            $var_asunto .= "Certificado de existencia y representación legal";
            break;

            case 3:
            $var_asunto .= "Certificado de proponentes";
            break;

            case 4:
            $var_asunto .= "Certificado de dignatarios";
            break;

            case 5:
            $var_asunto .= "Certificado de inspección vigilancia y control";
            break;

            case 6:
            $var_asunto .= "Certificado individual";
            break;

          }
        break;

        case 2:
        $var_tipo_resolucion = $radicado->id_tipo_resolucion;

        switch($var_tipo_resolucion){

          case 1:
          $var_asunto .= "Reconocimiento de personería";
          break;

          case 2:
          $var_asunto .= "Cancelación de personería jurídica a petición";
          break;

          case 3:
          $var_asunto .= "Asimilación de personería";
          break;

          case 4:
          $var_asunto .= "Cancelación de personería jurídica a petición";
          break;

          case 5:

          break;

          case 6:
          $var_asunto .= "Recurso de apelación";
          break;
          
          case 7:
          $var_asunto .= "Inscripción de dignatarios ADHOC";
          break;

          case 8:
          $var_asunto .= "Cancelación de dignatarios";
          break;

          case 9:

            $TiposDeResolucion = explode(",",$radicado['id_tipo_resolucion_combinada']);
            $var_inscripcionDignatarios = in_array(1, $TiposDeResolucion);
            $var_reformaEstatutos = in_array(2, $TiposDeResolucion);
            $var_registroLibros = in_array(3, $TiposDeResolucion);

            if($var_inscripcionDignatarios){
              $var_asunto .= "Inscripción de dignatario(s), ";
              $var_leyes .= ", Articulo 5 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
            }

            if($var_reformaEstatutos){
              $var_asunto .= "Reforma de estatutos, ";
              $var_leyes .= ", Articulo 4 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
            }

            if($var_registroLibros){
              $var_asunto .= "Registro de libro(s), ";
              $var_leyes .= ", Articulo 16 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
            }
          break;

        }
      break;

       }

       $sade = $radicado['sade'];
       $interna = $radicado['n_radicado_interno'];
       list($año,$mes,$dia) = explode("-",$radicado->fecha_creacion);

        $profesional = Profesional::findOne(1);
        $profesional1 = Profesional::findOne(2);
        $profesional2 = Profesional::findOne(3);
        $usuario = Yii::$app->user->identity->nombre_funcionario;
        $cargo = Yii::$app->user->identity->cargo_funcionario;


        $document = new TemplateProcessor ('plantillas/Plantilla Devolucion.docx');

        $document->setValue("retencion_documental",$tipoEntidad['codigo_trd']);                
        $document->setValue("var_ref",$var_ref);
        $document->setValue("var_asunto",$var_asunto);
        $document->setValue("var_disciplina",$var_disciplina);

        $document->setValue("nombre_entidad",$entidad['nombre_entidad']);
        $document->setValue("municipio_entidad",$municipio_entidad['municipio']);
        $document->setValue("departamento_entidad",$departamento_entidad['departamento']);

        if($presidente != null){

          $document->setValue("presidente",$presidente['nombre_dignatario']);
          $document->setValue("municipio_presidente",$municipio_presidente['municipio']);
          $document->setValue("departamento_presidente",$departamento_presidente['departamento']);
        }

        $document->setValue("sade",$sade);
        $document->setValue("interna",$interna);

        $document->setValue("dia",$dia);
        $document->setValue("mes",$mes);
        $document->setValue("año",$año);
        
        $document->setValue("var_leyes",$var_leyes);
      
        $document->setValue("nombre_profesional",$profesional['nombre_profesional']);
        $document->setValue("cargo_profesional",$profesional['cargo_profesional']);

        $document->setValue("nombre_profesional_revisor",$profesional1['nombre_profesional']);
        $document->setValue("cargo_profesional_revisor",$profesional1['cargo_profesional']);
        
        $document->setValue("nombre_profesional_vobo",$profesional2['nombre_profesional']);
        $document->setValue("cargo_profesional_vobo",$profesional2['cargo_profesional']);


        $document->setValue("nombre_usuario",$usuario);
        $document->setValue("cargo_usuario",$cargo);
        $document->setValue("ubicacion_archivo",$entidad['ubicacion_archivos_entidad']);

        $document->saveAs('Devolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
        header('Content-Disposition: attachment; filename=Devolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx; charset=iso-8859-1');
        echo file_get_contents('Devolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
        unlink('Devolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');

    }
    public function actionDevolucionentidad($id){
      
      $radicado = Radicados::findOne($id);
      $var_ref = "";
      $var_disciplina = "";
      $var_asunto = "Reconocimiento de personería";
      $sade = $radicado['sade'];
      $interna = $radicado['n_radicado_interno'];
      list($año,$mes,$dia) = explode("-",$radicado->fecha_creacion);
      $profesional = Profesional::findOne(1);
      $profesional1 = Profesional::findOne(2);
      $profesional2 = Profesional::findOne(3);
      $usuario = Yii::$app->user->identity->nombre_funcionario;
      $cargo = Yii::$app->user->identity->cargo_funcionario;

      $document = new TemplateProcessor ('plantillas/Plantilla Devolucion.docx');

      $document->setValue("var_ref",$var_ref);
      $document->setValue("var_asunto",$var_asunto);
      $document->setValue("var_disciplina",$var_disciplina);
      $document->setValue("sade",$sade);
      $document->setValue("interna",$interna);
      $document->setValue("dia",$dia);
      $document->setValue("mes",$mes);
      $document->setValue("año",$año);
      $document->setValue("nombre_profesional",$profesional['nombre_profesional']);
      $document->setValue("cargo_profesional",$profesional['cargo_profesional']);
      $document->setValue("nombre_profesional_revisor",$profesional1['nombre_profesional']);
      $document->setValue("cargo_profesional_revisor",$profesional1['cargo_profesional']);
      $document->setValue("nombre_profesional_vobo",$profesional2['nombre_profesional']);
      $document->setValue("cargo_profesional_vobo",$profesional2['cargo_profesional']);
      $document->setValue("nombre_usuario",$usuario);
      $document->setValue("cargo_usuario",$cargo);
      $document->saveAs('Devolucion de Personeria '.$radicado['id_radicado'].'-'.$año.'.docx');
      header('Content-Disposition: attachment; filename=Devolucion de Personeria '.$radicado['id_radicado'].'-'.$año.'.docx; charset=iso-8859-1');
      echo file_get_contents('Devolucion de Personeria '.$radicado['id_radicado'].'-'.$año.'.docx');
      unlink('Devolucion de Personeria '.$radicado['id_radicado'].'-'.$año.'.docx');

    }
    public function actionView($id, $tipoRadicado)
    {  
        $certificado = null;
        $resolucion = null;
        $fecha_creacion = null;
        $radicado = null;
        $entidad = null;
        $entidad_ivc = null;
        $historial = array();
        $historial_cambio_objeto_social = null;
        $historial_cambio_razon_social = null;
        $historial_cambio_domicilio_municipio = null;
        $historial_cambio_domicilio_direccion = null;
        $ResolucionesFile = 0;

        // INFORMACION DE CERTIFICADO 
        if ($tipoRadicado == 1){
          $certificado = Certificados::findOne($id);
          $fecha_creacion = $certificado['fecha_creacion'];
          $radicado = Radicados::findOne($certificado['id_radicado']);
          $entidad = Entidades::findOne($certificado['id_entidad']);
        }
        // INFORMACION DE RESOLUCION 
        elseif ($tipoRadicado == 2){
          $resolucion = Resoluciones::findOne($id);
          $fecha_creacion = $resolucion['fecha_creacion'];
          $radicado = Radicados::findOne($resolucion['id_radicado']);
          $entidad = Entidades::findOne($resolucion['id_entidad']);
          if($resolucion['id_historial'] != null) array_push($historial, $resolucion['id_historial']);
          
          if(empty($historial)) $historial = explode(",", $resolucion['id_historial_combinado']);
          foreach($historial as $valor){

            $var = Historial::findOne($valor);
            if($var['nombre_campo_modificado'] == "nombre_entidad"){
              
              $historial_cambio_razon_social = $var;
              
            }else
            if($var['nombre_campo_modificado'] == "id_tipo_entidad"){
              $historial_cambio_objeto_social = $var;

            }else
            if($var['nombre_campo_modificado'] == "municipio_entidad"){
              $historial_cambio_domicilio_municipio = $var;

            }else
            if($var['nombre_campo_modificado'] == "direccion_entidad"){
              $historial_cambio_domicilio_direccion = $var;

            }
            
          }
        }

        // INFORMACION DE LA ENTIDAD
        $municipio_entidad = Municipios::findOne($entidad['municipio_entidad']);
        $tipoEntidad = TipoEntidad::findOne($entidad['id_tipo_entidad']);
        $nombre_entidad = $entidad['nombre_entidad'];
        $tipo_regimen = TipoRegimen::findOne($entidad['id_tipoRegimen'])['nombre_tipoRegimen'];
        if($tipo_regimen == null || empty($tipo_regimen)) $tipo_regimen = "NO REGISTRA";
        $numero_personeria = $entidad['personeria_n'];

        if($numero_personeria == null){

          $numero_personeria = "NO REGISTRA";
        }
       
        if($entidad != null){
          if($entidad->fecha_reconocimiento != null){
            list($año_personeria,$mes_personeria,$dia_personeria) = explode("-",$entidad->fecha_reconocimiento);
          }else{
            $año_personeria = "NO REGISTRA";
            $mes_personeria = "NO REGISTRA";
            $dia_personeria = "NO REGISTRA";
          }
        }else{          
          $entidad_ivc = Entidadcamaracomercio::findOne($certificado['id_entidad_camara']);
        }     

        $flag_entidad_comunitaria = false;     

        ///////////////// Llevarse para resolucines controller//////////////////
        if($entidad['id_tipo_entidad'] == 9 || $entidad['id_tipo_entidad'] == 12 || $entidad['id_tipo_entidad'] == 14 || $entidad['id_tipo_entidad'] == 48 ){
          $flag_entidad_comunitaria = true;
        }
      ////////////////////////////////////// FECHA GACETA /////////////////////////////////////////////////////
        //$flag_entidad_comunitaria || $radicado->fecha_gaceta != null
        if(true){
          
          // INFORMACION PARA EL DIGNATARIO ESPECIFICO A TRAMITAR
          $id_dignatario_tramite = $radicado['id_dignatario_tramite'];
          if($id_dignatario_tramite != null)
          {
            $dignatario_tramite = Dignatarios::findOne($id_dignatario_tramite);
            $municipio_dignatario_tramite  = Municipios::findOne($dignatario_tramite['id_municipio_expedicion']);
            $cargo_dignatario_tramite = Cargos::findOne($dignatario_tramite['id_cargo']);
          }

          /////////////////////// VARIABLES COMUNES PARA LAS PLANTILLAS ////////////////////////////
          list($año,$mes,$dia) = explode("-",$fecha_creacion);

          //INFORMACION DEL RADICADO        
          $date = date('Y-m-d', strtotime($radicado->fecha_creacion));
          list($añoradi,$mesradi,$diaradi) = explode("-",$date);
          //list($añoradi,$mesradi,$diaradi) = explode("-",$radicado->fecha_creacion);

          // INFORMACION DE LOS DIGNATARIOS

          $presidente = Dignatarios::find()->where(['and', ['id_entidad'=>$entidad['id_entidad']],['representante_legal' => 1],['estado' => 1] ])->orderBy(['fin_periodo' => SORT_DESC])->limit(1)->one(); 
          
          if($presidente != null)
          {
            $municipio_presidente  = Municipios::findOne($presidente['id_municipio_expedicion']);
            $departamento_presidente  = Departamentos::findOne($municipio_presidente['departamento_id']);
            $cargo_array = Cargos::findOne($presidente['id_cargo']);
            $cargo_representante = $cargo_array['nombre_cargo'];
           
          }

          // INFORMACION DEL PERSONAL PERJUR
          $profesional = Profesional::findOne(1);
          $profesional1 = Profesional::findOne(2);
          $profesional2 = Profesional::findOne(3);
          $usuario = Yii::$app->user->identity->nombre_funcionario;
          $cargo = Yii::$app->user->identity->cargo_funcionario;
                  
          $representante_legal_ivc= null;       
          if($entidad_ivc != null){
            $representante_legal_ivc = $entidad_ivc->nombre_representante;
          }

          if($presidente == null && $representante_legal_ivc == null){
         
            $session = Yii::$app->session;
            $r = 'LA ENTIDAD '.$entidad['nombre_entidad'].' NO TIENE UN REPRESENTANTE LEGAL ACTIVO, ACTUALICE LOS DIGNATARIOS DE LA ENTIDAD E INTENTE NUEVAMENTE';
            $session->set('msg',$r);
            $this->redirect(Yii::$app->request->baseUrl."?r=entidades%2Fview&id=".$resolucion['id_entidad']);
          
          }else{

            switch($tipoRadicado)
            {
              // SELECCION DE CERTIFICADOS
              case 1:
                $var_tipo_certificado = "";
                $var_codigo = "FO-M4-P2-01";
                $var_version =  "01";
                $var_dignatario = "";
                $var_estatutos = "";

                $resolucion_reconocimiento = null;
                $var_articulo_estatutos = "";
                $var_articulo_cambio_razon_social = ""; 
                $var_articulo_cambio_objeto_social = "";              
                $var_articulo_cambio_domicilio_municipio = "";
                $var_articulo_cambio_domicilio_direccion = "";
                $var_articulo_registro_libros = "";
                $var_articulo_reconocimiento = "";
                $dignatarios_1= "";
                $dignatarios_2= "";

                $array_reforma_estatutos = array();
                $array_reforma_cambio_razon_social = array();
                $array_reforma_cambio_objeto_social = array();
                $array_reforma_cambio_domicilio_municipio = array();
                $array_reforma_cambio_domicilio_direccion = array();
                $array_registro_libros = array();

                $array_historial_cambio_razon_social = array();
                $array_historial_cambio_objeto_social = array();
                $array_historial_cambio_domicilio_municipio = array();
                $array_historial_cambio_domicilio_direccion = array();
                $array_historial_registro_libros = array();
                
                $var_temp1 = $radicado['id_tipo_certificado'];  
                
                if($var_temp1 == 1 || $var_temp1 == 4 ){

                  $dignatarios = Dignatarios::find()->where(['id_entidad'=>$entidad['id_entidad']])->all();

                  for ($i=0; $i <sizeof($dignatarios) ; $i++) {
                    $aux = $i-1;

                    if($aux < 0 ){
                      $dignatarios_1 .= GruposCargos::findOne($dignatarios[$i]['id_grupo_cargos'])['nombre_grupo_cargo']."<w:br w:type='line'/>"."<w:br w:type='line'/>" 
                      .Cargos::findOne($dignatarios[$i]['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>";
                      $dignatarios_2 .="<w:br w:type='line'/>"."<w:br w:type='line'/>";
                      $dignatarios_2 .= $dignatarios[$i]['nombre_dignatario'] ." C.C: ".$dignatarios[$i]['cedula_dignatario']."<w:br w:type='line'/>"; 
                    }else{
                      if(GruposCargos::findOne($dignatarios[$i]['id_grupo_cargos'])['nombre_grupo_cargo'] == GruposCargos::findOne($dignatarios[$aux]['id_grupo_cargos'])['nombre_grupo_cargo']){
                        $dignatarios_1 .= Cargos::findOne($dignatarios[$i]['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>";
                        $dignatarios_2 .= $dignatarios[$i]['nombre_dignatario'] ." C.C: ".$dignatarios[$i]['cedula_dignatario']."<w:br w:type='line'/>"; 
                      }else{
                        $dignatarios_1 .="<w:br w:type='line'/>";
                        $dignatarios_1 .= GruposCargos::findOne($dignatarios[$i]['id_grupo_cargos'])['nombre_grupo_cargo']."<w:br w:type='line'/>"."<w:br w:type='line'/>" 
                        .Cargos::findOne($dignatarios[$i]['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>";
                        $dignatarios_2 .="<w:br w:type='line'/>"."<w:br w:type='line'/>"."<w:br w:type='line'/>";
                        $dignatarios_2 .= $dignatarios[$i]['nombre_dignatario'] ." C.C: ".$dignatarios[$i]['cedula_dignatario']."<w:br w:type='line'/>";
                      }
                    }                                      
                          
                  }
                }
                
                $var_historiales = Historial::find()->where(['id_tabla_modificada' =>$entidad['id_entidad']])->all();
                
                foreach($var_historiales as $valor){
                  $tipo_cambio = $valor->nombre_campo_modificado;

                  if($tipo_cambio == 'fecha_estatutos'){
                    array_push($array_reforma_estatutos, $valor->id_resolucion);
                  }else
                  if($tipo_cambio == 'nombre_entidad'){
                    array_push($array_reforma_cambio_razon_social, $valor->id_resolucion);
                    array_push($array_historial_cambio_razon_social, $valor->id_historial);
                  }else
                  if($tipo_cambio == 'id_tipo_entidad'){
                    array_push($array_reforma_cambio_objeto_social, $valor->id_resolucion);
                    array_push($array_historial_cambio_objeto_social, $valor->id_historial);
                  }else
                  if($tipo_cambio == 'municipio_entidad'){
                    array_push($array_reforma_cambio_domicilio_municipio, $valor->id_resolucion);
                    array_push($array_historial_cambio_domicilio_municipio, $valor->id_historial);
                  }else
                  if($tipo_cambio == 'direccion_entidad'){
                    array_push($array_reforma_cambio_domicilio_direccion, $valor->id_resolucion);
                    array_push($array_historial_cambio_domicilio_direccion, $valor->id_historial);
                  }else
                  if($tipo_cambio == null && $valor->tabla_modificada == "REGISTRO DE LIBROS"){
                    array_push($array_registro_libros, $valor->id_resolucion);
                    array_push($array_historial_registro_libros, $valor->id_historial);
                  }else
                  if($valor->nombre_evento == "CREACIÓN DE ENTIDAD"){
                    $resolucion_reconocimiento = Resoluciones::findOne($valor);                
                  }
                }

                foreach($array_reforma_estatutos as $valor){
                  $resolucion = Resoluciones::findOne($valor);
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion->fecha_creacion);
                  $var_articulo_estatutos .= "Que mediante Resolución N° ".$resolucion['numero_resolucion']." del ".$dia_r." de ".$mes_r." del ".$año_r.", la Gobernación del Departamento del Valle del Cauca aprobó reforma de estatutos de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']."."
                  ."<w:br w:type='line'/>";
                }

                for($i = 0; $i < count($array_reforma_cambio_razon_social); $i++){
                  $resolucion = Resoluciones::findOne($array_reforma_cambio_razon_social[$i]);
                  $historial = Historial::findOne($array_historial_cambio_razon_social[$i]);
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion->fecha_creacion);
                  $var_articulo_cambio_razon_social .= "Que mediante Resolución N° ".$resolucion['numero_resolucion']." del ".$dia_r." de ".$mes_r." del ".$año_r.", la Gobernación del Departamento del Valle del Cauca aprobó el cambio de razón social de ".$historial['valor_anterior_campo']." a ".$historial['valor_nuevo_campo']." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']."."
                  ."<w:br w:type='line'/>";
                }

                for($i = 0; $i < count($array_reforma_cambio_objeto_social); $i++){
                  $resolucion = Resoluciones::findOne($array_reforma_cambio_objeto_social[$i]);                
                  $historial = Historial::findOne($array_historial_cambio_objeto_social[$i]);
                  $nuevo_tipo_entidad = TipoEntidad::findOne($historial['valor_nuevo_campo'])['tipo_entidad'];
                  $viejo_tipo_entidad = TipoEntidad::findOne($historial['valor_anterior_campo'])['tipo_entidad'];
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion->fecha_creacion);
                  $var_articulo_cambio_objeto_social .= "Que mediante Resolución N° ".$resolucion['numero_resolucion']." del ".$dia_r." de ".$mes_r." del ".$año_r.", la Gobernación del Departamento del Valle del Cauca aprobó el cambio de objeto social de ser una entidad ".$viejo_tipo_entidad." a ".$nuevo_tipo_entidad." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']."."
                  ."<w:br w:type='line'/>";
                }

                for($i = 0; $i < count($array_reforma_cambio_domicilio_municipio); $i++){
                  $resolucion = Resoluciones::findOne($array_reforma_cambio_domicilio_municipio[$i]);
                  $historial = Historial::findOne($array_historial_cambio_domicilio_municipio[$i]);
                  $nuevo_municipio_entidad = Municipios::findOne($historial['valor_nuevo_campo'])['municipio'];
                  $viejo_municipio_entidad = Municipios::findOne($historial['valor_anterior_campo'])['municipio'];
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion->fecha_creacion);
                  $var_articulo_cambio_domicilio_municipio .= "Que mediante Resolución N° ".$resolucion['numero_resolucion']." del ".$dia_r." de ".$mes_r." del ".$año_r.", la Gobernación del Departamento del Valle del Cauca aprobó el cambio de domicilio de ".$viejo_municipio_entidad." al ".$nuevo_municipio_entidad." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']."."
                  ."<w:br w:type='line'/>";
                }

                for($i = 0; $i < count($array_reforma_cambio_domicilio_direccion); $i++){
                  $resolucion = Resoluciones::findOne($array_reforma_cambio_domicilio_direccion[$i]);
                  $historial = Historial::findOne($array_historial_cambio_domicilio_direccion[$i]);
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion->fecha_creacion);
                  $var_articulo_cambio_domicilio_direccion .= "Que mediante Resolución N° ".$resolucion['numero_resolucion']." del ".$dia_r." de ".$mes_r." del ".$año_r.", la Gobernación del Departamento del Valle del Cauca aprobó el cambio de su oficio ".$historial['valor_anterior_campo']." a ".$historial['valor_nuevo_campo']." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']."."
                  ."<w:br w:type='line'/>";
                }

                for($i = 0; $i < count($array_registro_libros); $i++){
                  $resolucion = Resoluciones::findOne($array_registro_libros[$i]);
                  $historial = Historial::findOne($array_historial_registro_libros[$i]);
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion->fecha_creacion);
                  $var_articulo_registro_libros .= "Que mediante Resolución N° ".$resolucion['numero_resolucion']." del ".$dia_r." de ".$mes_r." del ".$año_r.", la Gobernación del Departamento del Valle del Cauca aprobó el ".$historial['nombre_evento']." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']."."
                  ."<w:br w:type='line'/>";
                }

                $valores = Valores::find()->all();
                
                if($resolucion_reconocimiento != null){
                  list($año_r,$mes_r,$dia_r) = explode("-",$resolucion_reconocimiento->fecha_creacion);
                  $var_articulo_reconocimiento .= "Que por medio de la resolución No ".$resolucion_reconocimiento['numero_resolucion']." de fecha ".$dia_r." del mes de ".$mes_r." del ".$año_r." él(la) ___________________________________, le reconoció Personería Jurídica a la entidad sin ánimo de lucro denominada: ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'].", de finalidad ".$tipoEntidad['tipo_entidad'].", dicha personería se encuentra vigente a la fecha.";
                }
                
                $var_ivc_parrafo1 = "";
                $var_ivc_parrafo2 = "";
                $var_ivc_parrafo3 = ""; 
                $var_articulos_proponentes = "";
                $var_articulos_proponentes_2 = "";
                $var_articulos_proponentes_3 = "";

              
                switch($radicado['id_tipo_certificado'])
                {
                  case 1: //Certificación Historica
                    $var_tipo_certificado .= "HISTORICO";
                  
                  
                    $var_dignatario .= "Que el(la) actual Representante legal de la entidad denominada ".$nombre_entidad." con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".$mes_personeria." de ".$año_personeria." y NIT ____________________, en su condición de ".$cargo_representante." es el(la) señor(a) " .$presidente['nombre_dignatario']." identificado(a) con la Cédula de Ciudadanía ".$presidente['cedula_dignatario']." expedida en ".$municipio_presidente['municipio']."-".$departamento_presidente['departamento'].", cuyo nombre se encuentra inscrito en los registros que para tal efecto se llevan en este despacho.";

                    $var_estatutos .= "RAZON SOCIAL: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."AMBITO TERRITORIAL: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."DURACION: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."OBJETIVOS: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."ADMINISTRACIÓN Y FUNCIONAMIENTO: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."ATRIBUCIONES DE LA ASAMBLEA: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."DE LA JUNTA DIRECTIVA: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."SON ATRIBUCIONES DE LA JUNTA DIRECTIVA: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                
                    ."PRESIDENTE: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."FISCAL: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."PATRIMONIO: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."DISOLUCIÓN Y LIQUIDACIÓN: ___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________";
                  break;

                  case 2: // Certificado de Existencia y Representacion Legal
                    $var_tipo_certificado .= "DE EXISTENCIA Y REPRESENTACION LEGAL ENTIDADES SIN ANIMO DE LUCRO";
                    $var_dignatario .= "Que el(la) actual Representante legal de la citada entidad, en su condición de ".$cargo_representante." es el(la) señor(a) " .$presidente['nombre_dignatario']." identificado(a) con la Cédula de Ciudadanía ".$presidente['cedula_dignatario']." expedida en ".$municipio_presidente['municipio']."-".$departamento_presidente['departamento'].", cuyo nombre se encuentra inscrito en los registros que para tal efecto se llevan en este despacho.";
    
                  
                  break;

                  case 3: // Certificado individual
                    $var_tipo_certificado .= "DE DIGNATARIO INDIVIDUAL";
                    $var_dignatario .= "Que el señor (a) ".$dignatario_tramite['nombre_dignatario'].", en su condición de ".$cargo_dignatario_tramite['nombre_cargo']." identificado(a) con la Cédula de Ciudadanía ".$dignatario_tramite['cedula_dignatario']."-".$municipio_dignatario_tramite['municipio'].", cuyo nombre se encuentra inscrito en los registros que para tal efecto se llevan en este despacho."; 
                    $var_articulo_estatutos = "";
                    $var_articulo_cambio_razon_social = ""; 
                    $var_articulo_cambio_objeto_social = "";              
                    $var_articulo_cambio_domicilio_municipio = "";
                    $var_articulo_cambio_domicilio_direccion = "";
                    $var_articulo_registro_libros = "";
                  break;

                  case 4: // Certificado dignatarios
                    $var_tipo_certificado .= "DE DIGNATARIOS";
                    $var_dignatario .= "Que el(la) actual Representante legal de la citada entidad, en su condición de ".$cargo_representante." es el(la) señor(a) " .$presidente['nombre_dignatario']." identificado(a) con la Cédula de Ciudadanía ".$presidente['cedula_dignatario']." expedida en ".$municipio_presidente['municipio']."-".$departamento_presidente['departamento'].", cuyo nombre se encuentra inscrito en los registros que para tal efecto se llevan en este despacho.";
                    $var_articulo_estatutos = "";
                    $var_articulo_cambio_razon_social = ""; 
                    $var_articulo_cambio_objeto_social = "";              
                    $var_articulo_cambio_domicilio_municipio = "";
                    $var_articulo_cambio_domicilio_direccion = "";
                    $var_articulo_registro_libros = "";
                  break;

                  case 5: // Certificado IVC
                    $entidad_ivc = null;
                    $nombre_entidad_ivc = "";
                    $direccion_ivc = "";
                    $municipio_ivc ="";
                    $municipio_camara="____________";
                    $representante_legal_ivc="";
                    $cedula_ivc="";
                    $nit_ivc="__________";
                    if($radicado['id_entidadcg']==1){
                    $nombre_entidad_ivc = $nombre_entidad;
                    $direccion_ivc = $entidad['direccion_entidad'];
                    $municipio_ivc =$municipio_entidad['municipio'];
                    $representante_legal_ivc=$presidente['nombre_dignatario'];
                    $cedula_ivc=$presidente['cedula_dignatario'];
                    
                    }elseif($radicado['id_entidadcg']==2 || $radicado['id_entidadcg']==3)
                    {
                    $entidad_ivc = Entidadcamaracomercio::findOne($certificado['id_entidad_camara']);
                    $nombre_entidad_ivc = $entidad_ivc->nombre_entidad_camara;
                    $direccion_ivc = $entidad_ivc->direccion_entidad;
                    $municipio_ivc = Municipios::findOne($entidad_ivc['id_municipio'])['municipio'];
                    $municipio_camara=Municipios::findOne($entidad_ivc['id_municipio_camara'])['municipio'];
                    $representante_legal_ivc= $entidad_ivc->nombre_representante;
                    $cedula_ivc=$entidad_ivc->cedula_representante;
                    $nit_ivc=$entidad_ivc->nit_entidad;
                    }
                    $var_tipo_certificado .= "DE INSPECCIÓN VIGILANCIA Y CONTROL";
                    $var_articulo_reconocimiento = "";
                    $var_ivc_parrafo1 = "Que la entidad denominada ".$nombre_entidad_ivc.", con domicilio principal en la ".$direccion_ivc." de la ciudad ".$municipio_ivc.", se encuentra inscrita en la Cámara de Comercio de ".$municipio_camara." con Nit: ".$nit_ivc.", y su Representante Legal es el señor(a): " .$representante_legal_ivc." identificado(a) con la Cédula de Ciudadanía ".$cedula_ivc.".";
                    $var_ivc_parrafo2 = "Que en virtud a la normatividad que regula la Inspección y vigilancia, de las Asociaciones, Corporaciones, Fundaciones e instituciones de Utilidad común (Constitución Nacional, Decreto 1338 de 1988, Decreto 1093 de 1989, Decreto 1529 de 1990, articulo 24 y Decreto 1066 de 2015), y a la delegación por parte del Gobernador 
                    del Departamento del Valle del Cauca, contenida en el Decreto Departamental 0837 de 2011, Decreto Departamental 0081 de 2012 y Resolución 0024 de 2013 y Decreto Departamental Nro. 1138 del 29 de agosto de 2016; el Departamento Administrativo de Jurídica es la Dependencia encargada de ejercer la Inspección y Vigilancia de la entidad denominada ".$nombre_entidad_ivc.", con domicilio en ".$municipio_ivc;
                    $var_ivc_parrafo3 = "Que hasta la fecha la entidad ______________ registra sanción disciplinaria impuesta por el Departamento del Valle del Cauca con ocasión del ejercicio de las facultades de inspección, control y vigilancia contenidas en el Decreto Departamental Nro. 1138 del 29 de agosto de 2016. Adicionalmente, obra en el expediente requerimiento para aportar documentación, con el fin de ejercer proceso correspondiente y confirmar la información solicitada en virtud de las facultades enunciadas.";
                    $var_articulo_estatutos = "";
                    $var_articulo_cambio_razon_social = ""; 
                    $var_articulo_cambio_objeto_social = "";              
                    $var_articulo_cambio_domicilio_municipio = "";
                    $var_articulo_cambio_domicilio_direccion = "";
                    $var_articulo_registro_libros = "";
                    $var_articulo_reconocimiento = "";
                  break;

                  case 6: // Certificado proponentes
                    $var_tipo_certificado .= "DE EXISTENCIA Y REPRESENTACION LEGAL PARA REGISTRO ÚNICO DE PROPONENTES DE CONFORMIDAD CON DEL DECRETO 1082 DE MAYO 26 DE 2015";
                    $var_dignatario .= "Que el(la) actual Representante legal de la citada entidad, en su condición de ".$cargo_representante." es el(la) señor(a) " .$presidente['nombre_dignatario']." identificado(a) con la Cédula de Ciudadanía ".$presidente['cedula_dignatario']." expedida en ".$municipio_presidente['municipio']."-".$departamento_presidente['departamento'].", cuyo nombre se encuentra inscrito en los registros que para tal efecto se llevan en este despacho.";
                    $var_articulos_proponentes .= "Que en los estatutos vigentes menciona nombre, naturaleza, domicilio y duración:"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Artículo __: _____________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Artículo __: _____________________________________________________________________________________________________________________"  
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Artículo __: _____________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Artículo __: _____________________________________________________________________________________________________________________";
                    $var_articulos_proponentes_2 .= "Que en los estatutos vigentes menciona del presidente y sus funciones:"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Artículo __: _____________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Artículo __: _____________________________________________________________________________________________________________________"; 
                    $var_articulos_proponentes_3 .= "Para la obtención de personería jurídica la entidad se constituyó por documento privado el __ de _______________ de _____.";

                  break;
                }
                $document = new TemplateProcessor ('plantillas/Plantilla Certificados.docx');

                $document->setValue("var_tipo_certificado",$var_tipo_certificado);                
                $document->setValue("var_codigo",$var_codigo);
                $document->setValue("var_version",$var_version);

                $document->setValue("nombre_entidad",$entidad['nombre_entidad']);
                $document->setValue("municipio_entidad",$municipio_entidad['municipio']);
                $document->setValue("tipoEntidad",$tipoEntidad['tipo_entidad']);

                $document->setValue("var_dignatario",$var_dignatario);
                
                $document->setValue("dignatarios_1",$dignatarios_1);
                $document->setValue("dignatarios_2",$dignatarios_2);

                $document->setValue("var_estatutos",$var_estatutos);
                
                $document->setValue("var_articulo_reconocimiento",$var_articulo_reconocimiento);
                $document->setValue("var_articulo_cambio_objeto_social",$var_articulo_cambio_objeto_social);
                $document->setValue("var_articulo_cambio_razon_social",$var_articulo_cambio_razon_social);
                $document->setValue("var_articulo_cambio_domicilio_municipio",$var_articulo_cambio_domicilio_municipio);
                $document->setValue("var_articulo_cambio_domicilio_direccion",$var_articulo_cambio_domicilio_direccion);

                $document->setValue("var_articulo_estatutos",$var_articulo_estatutos);
                $document->setValue("var_articulo_registro_libros",$var_articulo_registro_libros);

                $document->setValue("var_ivc_parrafo1",$var_ivc_parrafo1);
                $document->setValue("var_ivc_parrafo2",$var_ivc_parrafo2);
                $document->setValue("var_ivc_parrafo3",$var_ivc_parrafo3);
                
                $document->setValue("var_articulos_proponentes",$var_articulos_proponentes);

                $document->setValue("var_articulos_proponentes_2",$var_articulos_proponentes_2);
                $document->setValue("var_articulos_proponentes_3",$var_articulos_proponentes_3);

                
                
                $document->setValue("sdia",$dia);
                $document->setValue("smes",$mes);
                $document->setValue("saño",$año);

                $document->setValue("s1",$valores[0]['Descripcion_valor']);
                $document->setValue("v1",$valores[0]['valor']);
                $document->setValue("s2",$valores[1]['Descripcion_valor']);
                $document->setValue("v2",$valores[1]['valor']);
                $document->setValue("s3",$valores[2]['Descripcion_valor']);
                $document->setValue("v3",$valores[2]['valor']);
                $document->setValue("s4",$valores[3]['Descripcion_valor']);
                $document->setValue("v4",$valores[3]['valor']);
                $document->setValue("s5",$valores[4]['Descripcion_valor']);
                $document->setValue("v5",$valores[4]['valor']);
                $document->setValue("s6",$valores[5]['Descripcion_valor']);
                $document->setValue("v6",$valores[5]['valor']);
                $document->setValue("s7",$valores[6]['Descripcion_valor']);
                $document->setValue("v7",$valores[6]['valor']);
                $document->setValue("s8",$valores[7]['Descripcion_valor']);
                $document->setValue("v8",$valores[7]['valor']);

                $document->setValue("nombre_profesional",$profesional['nombre_profesional']);
                $document->setValue("cargo_profesional",$profesional['cargo_profesional']);

                $document->setValue("nombre_profesional_revisor",$profesional1['nombre_profesional']);
                $document->setValue("cargo_profesional_revisor",$profesional1['cargo_profesional']);
                
                $document->setValue("nombre_profesional_vobo",$profesional2['nombre_profesional']);
                $document->setValue("cargo_profesional_vobo",$profesional2['cargo_profesional']);


                $document->setValue("nombre_usuario",$usuario);
                $document->setValue("cargo_usuario",$cargo);
                $document->setValue("ubicacion_archivo",$entidad['ubicacion_archivos_entidad']);

               if($entidad != null){
                  $document->saveAs('Certificado de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
                  header('Content-Disposition: attachment; filename=Certificado de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx; charset=iso-8859-1');
                  echo file_get_contents('Certificado de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
                  unlink('Certificado de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
                }else{
                  $document->saveAs('Certificado '.$entidad_ivc['nombre_entidad_camara'].'.docx');
                  header('Content-Disposition: attachment; filename=Certificado '.$entidad_ivc['nombre_entidad_camara'].'.docx; charset=iso-8859-1');
                  echo file_get_contents('Certificado '.$entidad_ivc['nombre_entidad_camara'].'.docx');
                  unlink('Certificado '.$entidad_ivc['nombre_entidad_camara'].'.docx');
                }
                
              break;

              // SELECCION DE RESOLUCIONES
              case 2:
              
                // VARIABLES GENERALES DE LA PLANTILLA DE RESOLUCIONES
                $var_tipo_resolucion1 = "";
                $var_tipo_resolucion2 = "";
                $var_disciplina = "";
                $var_articulo_libros = "";  
                $var_articulo_libros2 = "";
                $var_articulo_libros3 = "";         
                $var_articulo_reforma = "";
                $var_articulo_reconocimiento = "";
                $var_articulo_reconocimiento2 = "";
                $var_paragrafo_reconocimiento = "";
                $var_articulo_asimilacion = "";

                $dignatarios_1= "";
                $dignatarios_2= "";             
                  
                $var_temp2 = $radicado['id_tipo_resolucion'];

                if($var_temp2 == 1 || $var_temp2 == 3 || $var_temp2 == 7 || ($var_temp2 == 9 && in_array(1,explode(",",$radicado['id_tipo_resolucion_combinada'])))){

                  $array_dignatarios_historial = array();
                  $var_historiales = Historial::find()->where(['and',['id_resolucion' =>$resolucion['id_resolucion']],['nombre_evento' =>'CREACIÓN DE DIGNATARIO']])->all();
                  $dignatarios = array();

                  foreach ($var_historiales as $valor){
                  
                    array_push($dignatarios, Dignatarios::find()->where(['id_dignatario'=>$valor->id_tabla_modificada])->one());               
                  } 

                  for ($i=0; $i <sizeof($dignatarios) ; $i++) {
                    $aux = $i-1;

                    if($aux < 0 ){
                      $dignatarios_1 .= GruposCargos::findOne($dignatarios[$i]['id_grupo_cargos'])['nombre_grupo_cargo']."<w:br w:type='line'/>"."<w:br w:type='line'/>" 
                      .Cargos::findOne($dignatarios[$i]['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>";
                      $dignatarios_2 .="<w:br w:type='line'/>"."<w:br w:type='line'/>";
                      $dignatarios_2 .= $dignatarios[$i]['nombre_dignatario'] ." C.C: ".$dignatarios[$i]['cedula_dignatario']."<w:br w:type='line'/>"; 
                    }else{
                      if(GruposCargos::findOne($dignatarios[$i]['id_grupo_cargos'])['nombre_grupo_cargo'] == GruposCargos::findOne($dignatarios[$aux]['id_grupo_cargos'])['nombre_grupo_cargo']){
                        $dignatarios_1 .= Cargos::findOne($dignatarios[$i]['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>";
                        $dignatarios_2 .= $dignatarios[$i]['nombre_dignatario'] ." C.C: ".$dignatarios[$i]['cedula_dignatario']."<w:br w:type='line'/>"; 
                      }else{
                        $dignatarios_1 .="<w:br w:type='line'/>";
                        $dignatarios_1 .= GruposCargos::findOne($dignatarios[$i]['id_grupo_cargos'])['nombre_grupo_cargo']."<w:br w:type='line'/>"."<w:br w:type='line'/>" 
                        .Cargos::findOne($dignatarios[$i]['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>";
                        $dignatarios_2 .="<w:br w:type='line'/>"."<w:br w:type='line'/>"."<w:br w:type='line'/>";
                        $dignatarios_2 .= $dignatarios[$i]['nombre_dignatario'] ." C.C: ".$dignatarios[$i]['cedula_dignatario']."<w:br w:type='line'/>";
                      }
                    }                                      
                          
                  }
                }                
                
                $var_articulo_cancelacion = "";
                $var_cancelacion_considerando = "";
                $var_cancelacion_considerando2 = "";
                
                $var_calidad_cargo = "".$cargo_representante." y Representante Legal ";

                $var_cambio_objeto_social_considerando = "";
                $var_articulo_reformas = "";
                $var_articulo_cambio_objeto_social2 = "";

                $var_articulo_adhoc = "";

                $var_articulo_cancelacion_dignatarios1 = "";
                $var_articulo_cancelacion_dignatarios2 = "";

                $var_gaceta = "La Resolución en comento deberá publicarse en la Gaceta Departamental por cuenta de los interesados, trámite que se entenderá surtido con el pago de los derechos de publicación, de conformidad con el Decreto 1529 de 1990, compilado por el Decreto No. 1066 de 2015 numeral 2.2.1.3.10.";

                
                $var_hechos  = "";
                $var_impugnacion_articulo = "";

                $var_considerando_reformas = "";
                $var_revision = "";
                $var_leyes = "";
                $var_leyes_1 = "";
                $var_argumento_libros = "";
                $var_paragrafo_legal = "Esta Resolución surte efectos legales únicamente cuando se dé cumplimiento con lo dispuesto en este artículo.";
                $var_articulo_comunal1 = "";
                $var_paragrafo_comunal1 = "";
                $var_impugnacion_paragrafo_comunal = "";
                $var_cancelacion_dignatario = "";
                $var_articulo_cpaca = "La presente Resolución se notificará de conformidad con lo dispuesto por el artículo 67 y S.S. del Código de Procedimiento Administrativo de lo Contencioso Administrativo CPACA, advirtiendo que contra ella procede sólo el Recurso de Reposición, el cual deberá interponerse dentro de los diez (10) días siguientes a su notificación.";
               

                $var_comunicado = "NOTIFIQUESE, PUBLIQUESE";
                $var_articulo_dignatarios = "Inscríbase a las siguientes personas como dignatarios de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", departamento del Valle del Cauca. Según consta en el Acta de Asamblea General ordinaria Nº ____ del ___ de ________ de ____, Acta de Comité Ejecutivo de asignación de cargos y elección del tercer miembro comisión disciplinaria No. ____ del ___ de _________ de  ______.";
                $var_articulo_reconocimiento2 .= "Ejecutoriada la Resolución de Reconocimiento de Personería Jurídica, de conformidad con lo dispuesto en el Artículo 16 del Decreto 1529 de 1990, (compilado por el Decreto 1066 de 2015); el Representante Legal de la Entidad deberá presentar en el Dpto. Administrativo Jurídico de la Gobernación del Departamento del Valle del Cauca, los Libros de Afiliados, de Actas de Asamblea General y de Resoluciones del órgano responsable, para su correspondiente registro.";
                // SE VERIFICA SI ES DEPORTIVA O LIGA 
                if($tipoEntidad['id_tipo_entidad'] == 10 || $tipoEntidad['id_tipo_entidad'] == 6){
                  $var_disciplina .= "EN LA DISCPLINA DE ___________ ";
                  $var_articulo_dignatarios .= "y nombramiento del tercer miembro comisión del ______de ___________ de _____, Acta N° ____ del ___ de ____ de _____ acogimiento expreso de la Ley, y Acuerdo ______del ____ de _________ de ____.";
                  $var_leyes .= "Decreto 525 de 1990 (compilado por el Decreto 1075 de 2015), ley 181 de 1995, Decreto ley 1228 de 1995, ley 1946 del 4 enero de 2019, ley 1967 del 11 de julio del 2019 (compilados por el Decreto Único Reglamentario 1085 de 2015).";
                  //$var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos exigidos en el artículo 28 del Decreto N° 525 de 1990, de la Ley 181 de 1995 y Decreto Ley 1228 de 1995 (compilados por el Decreto Único Reglamentario 1085 de 2015).";
                  $var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos según la ley 181/95 decreto reglamentario 1227, y 1228/95; Artículo 5 del Decreto 1529 de 1990 compilado por el Decreto 1066 de 2015, Decreto No.1960 del 05 de octubre de 2015. Decreto compilatorio 1985 de 2015, Ley 1967 del 11 de julio de 2019.";
                  $var_revision .= "Del estudio realizado a la documentación anterior, se deduce que la entidad en mención no tiene ánimo de lucro y se ajusta en todo, con los preceptos de la moral y el orden legal, establecido para proporcionar a los deportistas la recreación y convivencia al amparo del ejercicio de dicho deporte de carácter recreativo.";
                }
                // SE VERIFICA SI ES ORGANO COMUNAL
                elseif($tipoEntidad['id_tipo_entidad'] == 9 || $tipoEntidad['id_tipo_entidad'] == 12 || $tipoEntidad['id_tipo_entidad'] == 14 || $tipoEntidad['id_tipo_entidad'] == 48){
                  $var_comunicado = "NOTIFIQUESE";
                  $var_gaceta = "Con fundamento en el artículo séptimo del decreto 1419 del 31 de mayo de 1.991, el reconocimiento de personería jurídica de los organismos comunales no requiere publicación.";
                  $var_leyes .= "Ley 743 de 2002, Decreto Reglamentario 2350 de 2003, Decreto Reglamentario 890 de 2008, Circular EXT. CIR09-156-DDP-0210";
                  $var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos según ley 52 de Dic.28/90, (Ministerio del Interior). Ley 743 de junio 5 de 2.002. Decreto Reglamentario No. 2350 de agosto 20 de 2.003, Decreto 890 del marzo 28 de 2008, Decreto compilatorio 1066 de 2015.";       
                  $var_paragrafo_legal = "";
                  $var_articulo_reconocimiento2 = "";
                  $var_articulo_comunal1 .= "De acuerdo con el artículo 57 de la Ley 743 del 05 de junio de 2.002, deberán presentar, ante el departamento administrativo de jurídica, los libros de Registro de Afiliados, de actas de asamblea y de directiva, tesorería e inventario con el fin de registrarlos.";
                  $var_paragrafo_comunal1 .= "De conformidad con lo ordenado en el Artículo 45 de la Ley 190 de 1995, todas las entidades sin ánimo de Lucro, deberán llevar la Contabilidad, de acuerdo con los principios generalmente aceptados.";
                  $var_articulo_cpaca = "";
                }
                // SE VERIFICA SI ES UNA ENTIDAD DE SALUD
                elseif($tipoEntidad['id_tipo_entidad'] == 8){
                  $var_leyes .= "Decreto 1088 de 1981, ley 10 de 1990, resolución 13565 del 1991 (compilados por el decreto único reglamentario 780 del 2016)";
                  //$var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos exigidos en el Decreto 1088 de 1981, ley 10 de 1990, resolución 13565 del 1991 (compilados por el decreto único reglamentario 780 del 2016).";
                  $var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos según Ley 10/93 decreto reglamentario 1088 de abril 25/91. Decreto 1088 de 1991 compilado por el Decreto Nro. 780 de 2016.";
                  
                }
                // SE VERIFICA SI ES UNA ENTIDAD EDUCATIVA
                elseif($tipoEntidad['id_tipo_entidad'] == 5){
                  $var_leyes .= "Decreto 525 de 1990 (compilado por el Decreto 1075 de 2015), ley 115 de 1994 (compilado por el decreto 1075 de 2015)";
                  //$var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos exigidos en el artículo 28 del Decreto N° 525 de 1990, de la ley 515 de 1994 (compilado por el decreto 1075 de 2015).";
                  $var_leyes_1 .= "El peticionario acompañó a la solicitud los documentos según Ley 115/94, compilado por el Decreto 1075 de 2015, decreto 525 de 1990, Decreto 1529/90 compilado por el Decreto 1066 de 2015.";

                }

                $var_articulo_inscripcion = "Inscríbase al señor (a) ".$presidente['nombre_dignatario'].", identificado (a) con la cédula de ciudadanía No. ".$presidente['cedula_dignatario']. ", expedida en ".$municipio_presidente['municipio']." - (".$departamento_presidente['departamento']."), en calidad de ".$var_calidad_cargo. " de la entidad denominada ".$nombre_entidad. " ".$var_disciplina.", con domicilio en el Municipio de " .$municipio_entidad['municipio'].", Departamento del Valle del Cauca. Según consta en el Acta de Asamblea General ordinaria Nº _      __   del __     _________ de _____________ de _____.";
              
                switch($radicado['id_tipo_resolucion'])
                {
                  case 1: // RECONOCIMIENTO DE PERSONERIA
                    $var_tipo_resolucion1 .= "RECONOCE PERSONERIA JURIDICA, SE APRUEBAN ESTATUTOS Y SE ORDENA LA INSCRIPCION DE DIGNATARIOS ";
                    $var_tipo_resolucion2 .= "el reconocimiento de personería jurídica, la aprobación de los estatutos y la inscripción de dignatarios";
                    $var_revision .= "Del estudio realizado a la documentación anterior, se deduce que la entidad en mención no tiene ánimo de lucro.";
                    $var_articulo_reconocimiento .= "Reconocer Personería Jurídica a la entidad sin ánimo de lucro, denominada ".$nombre_entidad. ", ".$var_disciplina. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] ." (Valle del Cauca), según consta en el acta N° ___ de constitución del __ de ___ de _____.";
                    $var_articulo_reforma .= "Aprobar los estatutos de la entidad denominada ".$nombre_entidad. ", con domicilio en el municipio de " .$municipio_entidad['municipio'] ." departamento del Valle del Cauca los cuales fueron presentados y corresponden a los adoptados y aprobados en Asamblea General en sesión del ____ de ____ de _________, según acta No. ____  del ____ de ____ de _________";
                    $var_paragrafo_reconocimiento .= "De acuerdo con lo ordenado en el Artículo 45 de la Ley 190 de 1995, todas las Entidades sin ánimo de Lucro, deberán llevar Contabilidad, según lo establecido en los principios generalmente aceptados.";
                    $var_leyes .= ", Articulo 4, 5, 16 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
                    $var_paragrafo_comunal1 = "";
                  break;

                  case 2: // CANCELACION DE PERSONERIA A PETICION
                    list($var_tipo_resolucion1, $var_cancelacion_considerando, $var_tipo_resolucion2, $var_cancelacion_considerando2, $var_revision, $var_calidad_cargo, $var_articulo_dignatarios,
                    $var_articulo_cancelacion) =
                    $this->TipoCancelacion($var_tipo_resolucion1, $var_cancelacion_considerando, $var_tipo_resolucion2, $var_cancelacion_considerando2, $var_revision, $var_calidad_cargo, $var_articulo_dignatarios,
                    $var_articulo_cancelacion, $nombre_entidad, $municipio_entidad, $tipoEntidad, $presidente, $municipio_presidente, "A PETICIÓN");
                    $var_articulo_inscripcion = "";
                    $var_articulo_dignatarios = "";
                    
                  break;

                  case 3: // ASIMILAR PERSONERIA
                    $asimilacion = Historial::find()->where(['and',['id_resolucion' =>$resolucion['id_resolucion']],['nombre_evento' =>'CAMBIO DE ASIMILACION']])->one();
                    $entidad_asimilacion = Entidades::find()->where(['nombre_entidad'=>$asimilacion['valor_anterior_campo']])->one();
                    $numero_personeria_asimilacion = $entidad_asimilacion['personeria_n'];

                    if($numero_personeria_asimilacion == null){

                      $numero_personeria_asimilacion = "NO REGISTRA";
                    }

                    if($entidad_asimilacion->fecha_reconocimiento != null){
                      list($año_personeria_asimilacion,$mes_personeria_asimilacion,$dia_personeria_asimilacion) = explode("-",$entidad_asimilacion->fecha_reconocimiento);
                    }else{
                      $fecha_reconocimiento = "NO REGISTRA";
                      $año_personeria_asimilacion = "NO REGISTRA";
                      $mes_personeria_asimilacion = "NO REGISTRA";
                      $dia_personeria_asimilacion = "NO REGISTRA";
                    }
                  
                    $var_tipo_resolucion1 .= "ASIMILA LA JUNTA DE VIVIENDA COMUNITARIA ".$asimilacion['valor_anterior_campo']. " Y SE RECONOCE PERSONERIA JURIDICA A LA JUNTA DE ACCIÓN COMUNAL ";
                    $var_tipo_resolucion2 .= "el otorgamiento de la Personería Jurídica por Asimilación o transformación de la Junta de Vivienda Comunitaria ".$nombre_entidad;
                    $var_revision .= "El inciso segundo del artículo 8° de la Ley 743 de 2002, expresa que la Junta de Vivienda Comunitaria como organización cívica sin ánimo de lucro podrá asimilarse a las Juntas de Acción Comunal si fuere procedente; una vez concluido totalmente el programa de vivienda, ubicado dentro de los linderos establecidos en el certificado expedido por la autoridad municipal competente."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."Que la documentación y los estatutos se encuentran ajustados a las normas comunales, Ley 743 del 05 de junio de 2002, Decreto Reglamentario No. 2350 del 20 de agosto de 2003 y 890 del 28 de Marzo de 2008, compilado por el Decreto Único Reglamentario No. 1066 de 2015."; 
                    $var_articulo_asimilacion .= "Asimilar la Junta de Vivienda Comunitaria ".$asimilacion['valor_anterior_campo'].", con domicilio en Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, entidad con Personería Jurídica No. ".$numero_personeria_asimilacion." del ".$dia_personeria_asimilacion." de ".$mes_personeria_asimilacion." de ".$año_personeria_asimilacion."  a Junta de Acción Comunal y reconocer Personería Jurídica a la entidad sin ánimo de lucro denominada JUNTA DE ACCIÓN COMUNAL ".$nombre_entidad. ", con domicilio en el municipio de " .$municipio_entidad['municipio'] ." departamento del Valle del Cauca.";
                    $var_articulo_reforma .= "Aprobar los estatutos de la entidad denominada JUNTA DE ACCIÓN COMUNAL ".$nombre_entidad. ", con domicilio en el municipio de " .$municipio_entidad['municipio'] ." departamento del Valle del Cauca los cuales fueron presentados y corresponden a los adoptados y aprobados en Asamblea General en sesión del ____ de ____ de _________, según acta No. ____  del ____ de ____ de _________.";
                    $var_leyes .= ", Articulo 4, 5, 16 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
                  break;

                  case 4: // CANCELACION DE PERSONERIA DE OFICIO
                    list($var_tipo_resolucion1, $var_cancelacion_considerando, $var_tipo_resolucion2, $var_cancelacion_considerando2, $var_revision, $var_calidad_cargo, $var_articulo_dignatarios,
                    $var_articulo_cancelacion) =
                    $this->TipoCancelacion($var_tipo_resolucion1, $var_cancelacion_considerando, $var_tipo_resolucion2, $var_cancelacion_considerando2, $var_revision, $var_calidad_cargo, $var_articulo_dignatarios,
                    $var_articulo_cancelacion, $nombre_entidad, $municipio_entidad, $tipoEntidad, $presidente, $municipio_presidente, "DE OFICIO");
                    $var_articulo_inscripcion = "";
                    $var_articulo_dignatarios = "";
                  break; 

                  case 5: // CAMBIO DE OBJETO SOCIAL
                  break;

                  case 6: // IMPUGNACION
                    $var_leyes_1 = "";
                    $var_articulo_cpaca = "";
                    $var_gaceta = "";
                    $var_paragrafo_legal = "";
                    $var_tipo_resolucion1 .= "RESUELVE UN RECURSO DE APELACION INTERPUESTO POR __________________________ EN CONTRA DE ________________ DE FECHA __ DE ______ DE _____ DE LA ______________ ";
                    $var_hechos  .= "HECHOS"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."Los señores ____________________________________________________, en calidad de ______________________ de la entidad denominada ".$nombre_entidad. " elegidos mediante acta N°__ del __ de _____ de _____. Manifestaron a la comunidad sobre ____________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."Los señores ______________________________________________, en calidad de _____________________________ de la entidad denominada ".$nombre_entidad. " presentan ante el ______________________________________________________ del Municipio de ____________ la impugnación a las elecciones del __ de _________ de ______, por violación a los estatutos como consta en el artículo _________. que a letra dice: artículo __. ____________________________________________________________________________________________________________________________________________"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."De igual manera manifiestan en los escritos del ___ de ______ y __ de __________ de ______ que la documentación no cumple con los requisitos exigidos por la ley _____ de _________ y los estatutos vigentes de la entidad denominada ".$nombre_entidad. ", ya que _________________________________________________________________________________________________________________________________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."La instancia correspondiente ________________________ admite la demanda de ______________________ presentada por ______________________________ el __ de _____ de ______."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."La instancia correspondiente ________________________, del Municipio de _________ emite fallo el ____ de _________ de _______, resolviendo:" 
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Articulo 1 ________________________ ordenar ______________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."Articulo 2 ________________________ ordenar ______________________________________________________________________________________." 
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"                 
                    ."El (los) impugnante (s) inconforme (s) con el fallo de la primera instancia del ___ de _______ de ________, interponen recurso de apelación el día ___ de ________ de _______ recurso que fue admitido por esta dependecia el __ de ______ de ______."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    ."COMPETENCIA"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    ."Hecho el análisis a la normatividad vigente, respecto a la competencia que le asiste a la Gobernación del Valle del Cauca atinente a ______________________________________, los recursos de apelación _________________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    ."FUNDAMENTOS DE DERECHO"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    ."La constitución política de Colombia __________, la ley _____, el Decreto ____, y los estatutos de la entidad denominada __________________ _____________________________________________________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"      
                    ."No obstante, lo anterior, ________________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    ."DEL  RECURSO  INTERPUESTO"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    ."La inconformidad del apelante se fundamenta en _________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    
                    ."ARGUMENTOS Y ANALISIS DEL DESPACHO"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    
                    ."Este despacho procedió a examinar el acervo probatorio aportado mediante carpeta contentiva de _______________________ folios, en el cual se fundamentó la primera instancia para proferir oficio del 02 de junio de 2016, mediante la cual  se avoca el conocimiento de la entidad denominada ".$nombre_entidad. ", con domicilio en el municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, emitida por ____________________________________________________ del ___________  mediante Fallo del __ de ______ de _______."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   

                    ."Se evidencia en la investigación administrativa el cumplimiento del procedimiento en las actuaciones administrativas adelantadas por la _______________________________________________________ del ____________ por la atención a las quejas, apertura de la investigación, pruebas documentales y testimoniales, con el fin de determinar y establecer la responsabilidad de los implicados, cuando en el ejercicio de sus funciones o con ocasión de éstas, incurran en cualquiera de las conductas o comportamientos previstos en las normas legales vigentes y en los estatutos que conlleve al incumplimiento de deberes, extralimitación en el ejercicio de derechos y funciones, prohibiciones, como  quedo  demostrado en  el fallo del ___ de _______ de ______."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"   
                    
                    ."Por lo anteriormente expuesto, este despacho considera que existen razones suficientes para ________________ el fallo emitido por la ____________________________________________ de _________________ de fecha __ de ________ de __________, por cuanto quedo demostrado que _______________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"  
                    ."En ese orden de ideas, _____________________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."En mérito de lo anteriormente expuesto, este despacho,"
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>";


                    $var_impugnacion_articulo .= 
                    "ARTÍCULO 1º _____(Revocar o Confirmar)_____ el fallo emitido por ____________________________________________ mediante auto número  ______________ de fecha ___ de _____ del ________." 
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."ARTÍCULO 2º  ___________________________________________________________________________________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."ARTÍCULO 3º  En consecuencia de lo anterior, ______________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."ARTÍCULO 4º   Requerir a los afiliados de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, para ____________________________________________________________________________________________________." 
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."ARTÍCULO 5º   Notificar el contenido de la presente Resolución en los términos del articulo 67 y S.S. del Código de Procedimiento Administrativo y de lo Contencioso Administrativo (CPACA), al señor _______________, en calidad apelante de la _____________________________________________ del Municipio de ____________, a los miembros de ________________________________ de la entidad denominada ".$nombre_entidad. " del Municipio de " .$municipio_entidad['municipio'] .", y los señores ___________________________."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"
                    ."ARTÍCULO 6º   Contra la presente Resolución no procede recurso alguno quedando agotada la vía gubernativa."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
                    ."ARTÍCULO 7º   Una vez surtido el trámite de notificación se procede a devolver el expediente a su lugar de origen."
                    ."<w:br w:type='line'/>" ."<w:br w:type='line'/>";
                    if($tipoEntidad['id_tipo_entidad'] == 9 || $tipoEntidad['id_tipo_entidad'] == 12 || $tipoEntidad['id_tipo_entidad'] == 14 || $tipoEntidad['id_tipo_entidad'] == 48){
                      $var_impugnacion_paragrafo_comunal .= 
                      "PARAGRAFO   La Alcaldia Municipal podrá notificar por inmediatez y celeridad el presente acto administrativo de conformidad con la Delegación del Decreto N°0351 del 10 de marzo de 2016, remitiendo constancia a este despacho." 
                      ."<w:br w:type='line'/>" ."<w:br w:type='line'/>";
                      $var_articulo_inscripcion = "";
                      $var_articulo_dignatarios = "";
                    }
                  
                  break;

                  case 7: // ADHOC
                    $PresidenteAdhoc = Dignatarios::find()->where(['and',['id_entidad' => $entidad['id_entidad']],['id_cargo'=> 119],['estado'=> 1]])->one();
                    $Municipio_Presidente_Adhoc = Municipios::findOne($PresidenteAdhoc['id_municipio_expedicion']);
                    $SecretarioAdhoc = Dignatarios::find()->where(['and',['id_entidad' => $entidad['id_entidad']],['id_cargo'=> 1064],['estado'=> 1]])->one();
                    $Municipio_Secretario_Adhoc = Municipios::findOne($SecretarioAdhoc['id_municipio_expedicion']);
                    $var_tipo_resolucion1 .= "ORDENA LA INSCRIPCION DE PRESIDENTE Y SECRETARIO ADHOC";
                    $var_tipo_resolucion2 .= "la solicitud de inscripción del nombramiento de presidente y secretario ad hoc y el tribunal de garantías, para dar cumplimiento al proceso electoral de dignatarios por el resto del periodo ______________";
                    $var_revision .= "Para tal efecto aporta: convocatoria de invitación, Acta de Asamblea fechada del 12 de julio de 2017, listado de asistencia, y fotocopia de las cédulas de Ciudadanía."
                                      ."<w:br w:type='line'/>" ."<w:br w:type='line'/>";
                    $var_articulo_adhoc .= "Inscribir al señor(a) ".$PresidenteAdhoc['nombre_dignatario']." identificado(a) con la cédula de ciudadanía No. ".$PresidenteAdhoc['cedula_dignatario'].", expedida en ".$Municipio_Presidente_Adhoc['municipio'].", en calidad de Presidente ADHOC y el(la) señor(a) ".$SecretarioAdhoc['nombre_dignatario']." identificada con la cédula de ciudadanía No. ".$SecretarioAdhoc['cedula_dignatario']." expedida en ".$Municipio_Secretario_Adhoc['municipio'].", en calidad de Secretario (a) ADHOC, de la ".$nombre_entidad. " con domicilio  en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".$mes_personeria." de ".$año_personeria.", otorgada por la Gobernación del  Departamento  del Valle  del Cauca; inscripción que se realiza  según consta en  Acta de Asamblea General de Afiliados de fecha _____ de _________ de _______, para dar cumplimiento al proceso electoral de Dignatarios por  el resto del periodo _______________. Elección que se deberá realizar en un plazo máximo de dos (2) meses, en los términos que consagra el Parágrafo Segundo del Artículo 32 de la Ley 743 de 2002.";
                    $var_leyes .= ", Articulo 5 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
                    $var_articulo_inscripcion = "";

                  break;

                  case 8: // CANCELACION DE DIGNATARIOS                
                    $var_tipo_resolucion1 .= "CANCELA LA INSCRIPCIÓN POR SANCIÓN DISCIPLINARIA DEL DIGNATARIO ".$dignatario_tramite['nombre_dignatario'];
                    $var_tipo_resolucion2 .= "cancelar su inscripción como Dignatario para el período _______, al señor(a) ".$dignatario_tramite['nombre_dignatario'].", identificado con la Cédula de Ciudadanía No ".$dignatario_tramite['cedula_dignatario'].", expedida en ".$municipio_dignatario_tramite['municipio'].", en calidad de ".$cargo_dignatario_tramite['nombre_cargo'];
                    $var_cancelacion_dignatario .= "La cancelación de la inscripción de dignatario teniendo en cuenta que _________________________________________.";
                    $var_articulo_cancelacion_dignatarios1 .= "Cancelar la Inscripción del dignatario ".$dignatario_tramite['nombre_dignatario'].", identificado con la Cédula de Ciudadanía No ".$dignatario_tramite['cedula_dignatario'].", expedida en ".$municipio_dignatario_tramite['municipio'].", en calidad de ".$cargo_dignatario_tramite['nombre_cargo']." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".$mes_personeria." de ".$año_personeria.", otorgada por la Gobernación del Departamento del Valle del Cauca. Según consta en el escrito de renuncia irrevocable de fecha __ de _______ de _________.";
                    $var_articulo_cancelacion_dignatarios2 .= "Notificar al señor ________________________ en calidad de _______________ de la ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, para que con carácter urgente convoque a Asamblea de afiliados para la elección de los dignatarios que hayan renunciado o no se encuentren activos. ";
                    
                    $dignatario = Dignatarios::find()->where(['id_dignatario'=>$radicado['id_dignatario_tramite']])->one();
                    $dignatarios_1 .= GruposCargos::findOne($dignatario['id_cargo'])['nombre_grupo_cargo']."<w:br w:type='line'/>"               
                    .Cargos::findOne($dignatario['id_cargo'])['nombre_cargo']."<w:br w:type='line'/>"

                    ."<w:br w:type='line'/>"."<w:br w:type='line'/>";

                    $dignatarios_2 .= $dignatario['nombre_dignatario']."<w:br w:type='line'/>"
                    ."C.C: ".$dignatario['cedula_dignatario']." Exp: ".Municipios::findOne($dignatario['id_municipio_expedicion'])['municipio']."<w:br w:type='line'/>"           
                    ."PERIODO ".$dignatario['inicio_periodo']." A ".$dignatario['fin_periodo']

                    ."<w:br w:type='line'/>"."<w:br w:type='line'/>";    
                    $var_articulo_inscripcion = "";
                    $var_articulo_dignatarios = "";              
                  break;

                  case 9: // COMBINADO: INSCRIPCION - REFORMA - REGISTRO DE LIBROS
                    $TiposDeResolucion = explode(",",$radicado['id_tipo_resolucion_combinada']);
                    $var_inscripcionDignatarios = in_array(1, $TiposDeResolucion);
                    $var_reformaEstatutos = in_array(2, $TiposDeResolucion);
                    $var_registroLibros = in_array(3, $TiposDeResolucion);
          					$ResolucionesFile = 0;
                    
                    $TiposDeReforma = explode(",",$radicado['id_tipo_reforma_estatutaria']);
                    $var_cambio_razon_social = in_array(1, $TiposDeReforma);
                    $var_cambio_domicilio = in_array(2, $TiposDeReforma);
                    $var_cambio_objeto_social = in_array(3, $TiposDeReforma);

                    if($radicado['id_tipo_reforma_estatutaria'] == null){
                      $TiposDeReforma = array();
                    }
                   
                    if($var_inscripcionDignatarios)
                    {
                      $var_tipo_resolucion1 .= "ORDENA LA INSCRIPCION DE DIGNATARIOS, ";
                      $var_tipo_resolucion2 .= "LA INSCRIPCION DE DIGNATARIOS, ";
                      $var_leyes .= ", Articulo 5 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
                      $ResolucionesFile = 1;
                    }else{
                      $var_articulo_inscripcion = "";
                      $var_articulo_dignatarios = "";
                    }      

                    if(!empty($TiposDeReforma)){                   
                      $var_considerando_reformas .= "Que al aprobar esta reforma, la citada entidad ";
                      $var_articulo_reformas .= "Aprobar la reforma total de estatutos, ";
                      $ResolucionesFile += 3;
                    }

                    if($var_reformaEstatutos)
                    {
                      $var_tipo_resolucion1 .= "APRUEBA LA REFORMA _________ DE LOS ESTATUTOS, ";
                      $var_tipo_resolucion2 .= "LA REFORMA _______________ DE LOS ESTATUTOS, ";
                      $var_articulo_reforma .= "Aprobar la reforma ____________ introducida a los articulos ______________________ de los estatutos de la entidad denominada " .$nombre_entidad .", CON DOMICILIO EN EL MUNICIPIO DE " .$municipio_entidad['municipio'] .", DEPARTAMENTO DEL VALLE DEL CAUCA según consta en el acta de asamblea general de Afiliados __________________. Entidad con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".$mes_personeria." de ".$año_personeria.", expedida por la Gobernación del Valle del Cauca.";
                      $var_leyes .= ", Articulo 4 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
                    }

                    if($var_cambio_razon_social)
                    {
                      $nuevo_nombre_entidad = $historial_cambio_razon_social['valor_nuevo_campo'];
                      $viejo_nombre_entidad = $historial_cambio_razon_social['valor_anterior_campo'];
                      
                      
                      $var_tipo_resolucion1 .= "APRUEBA CAMBIO A LA RAZON SOCIAL, ";
                      $var_tipo_resolucion2 .= "EL CAMBIO A LA RAZON SOCIAL, ";
                      $var_considerando_reformas .= "modifica su razón social de ".$viejo_nombre_entidad. " a ". $nuevo_nombre_entidad .", ";

                      $var_articulo_reformas .= "modificación de su razón social de ".$viejo_nombre_entidad. " a ". $nuevo_nombre_entidad .", ";
                    
                    }

                    if($var_cambio_domicilio)
                    {
                      $var_tipo_resolucion1 .= "APRUEBA CAMBIO DOMICILIO, ";
                      $var_tipo_resolucion2 .= "EL CAMBIO DE DOMICILIO, ";

                      if($historial_cambio_domicilio_municipio != null){
                        $nuevo_municipio_entidad = Municipios::findOne($historial_cambio_domicilio_municipio['valor_nuevo_campo'])['municipio'];
                        $viejo_municipio_entidad = Municipios::findOne($historial_cambio_domicilio_municipio['valor_anterior_campo'])['municipio'];
                        $var_considerando_reformas .= "modifica su domicilio de ".$viejo_municipio_entidad. " a ".$nuevo_municipio_entidad.", ";

                        $var_articulo_reformas .= "modificación de su domicilio de ".$viejo_municipio_entidad. " a ".$nuevo_municipio_entidad.", ";
                      }

                      if($historial_cambio_domicilio_direccion != null){
                        $nuevo_direccion_entidad = $historial_cambio_domicilio_direccion['valor_nuevo_campo'];
                        $viejo_direccion_entidad = $historial_cambio_domicilio_direccion['valor_anterior_campo'];
                        $var_considerando_reformas .= "modifica su oficio de ".$viejo_direccion_entidad. " a ".$nuevo_direccion_entidad.", ";
                        $var_articulo_reformas .= "modificación de su oficio de ".$viejo_direccion_entidad. " a ".$nuevo_direccion_entidad.", ";
                      }
                      
                    }

                    if($var_cambio_objeto_social)
                    {
                      $temp_anteriorTipoEntidad = TipoEntidad::findOne($historial_cambio_objeto_social['valor_anterior_campo'])['tipo_entidad'];
                      $temp_nuevoTipoEntidad = TipoEntidad::findOne($historial_cambio_objeto_social['valor_nuevo_campo'])['tipo_entidad'];

                      $var_tipo_resolucion1 .= "APRUEBA CAMBIO AL OBJETO SOCIAL, ";
                      $var_tipo_resolucion2 .= "EL CAMBIO AL OBJETO SOCIAL, ";
                      $var_cambio_objeto_social_considerando .= "Teniendo en cuenta lo anterior y conforme a lo establecido en los artículos 42 y 43 del Decreto Ley 2150 de 1995, en concordancia con el artículo 1 y 8 del Decreto Reglamentario 0427 de marzo 5 de 1996, las reformas estatutarias por cambio en sus objetivos, deberán inscribirse en la ________________________ con jurisdicción en el domicilio principal de la persona Jurídica."
                      ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"; 
                      $var_considerando_reformas .= "deja de ser una entidad ".$temp_anteriorTipoEntidad.", convirtiéndose en una entidad de ".$temp_nuevoTipoEntidad." por las actividades a desarrollar, lo anterior conforme a lo establecido en el artículo 2º del Decreto 0427 de marzo 05 de 1996.";           
                      $var_articulo_reformas .= "cambio del objeto social de ".$temp_anteriorTipoEntidad." a ".$temp_nuevoTipoEntidad." para que sea registrada por competencia legal ante la ____________________ de su territorio, ";
                      $var_articulo_cambio_objeto_social2 .= "Que, a partir de la fecha, la Gobernación del Valle del Cauca, pierde la competencia pasando a ser de la __________________ el ente competente para registrar, aprobar, certificar y cancelar conforme a lo dispuesto en el Decreto 215 de 1995 en su articulado 40 a 45 y 143 a 148 y el Decreto 0427 de 1996 articulo 2.";
                      
                    }

                    if(!empty($TiposDeReforma)){
                      
                      $var_articulo_reformas .= "con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca. La cual fue discutida y aprobada en Asamblea Acta No. _ del _ de _____ de ______. Entidad con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".$mes_personeria." de ".$año_personeria." expedida por la Gobernación del Valle del Cauca. ";
                      
                    }

                    if($var_registroLibros)
                    {
                      $var_argumento_libros .= "El peticionario acompañó a su solicitud, los siguientes documentos:" ."<w:br/>" ."1.________________________" ."<w:br/>" ."2.________________________"."<w:br/>" ."3.________________________";
                      $var_articulo_libros2 .= "Comuníquese al Presidente y /o Tesorero y Secretario de la ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio']." (V), dignatarios que deben velar por la custodia del (los) libro(s) registrado(s), de conformidad con los estatutos y la ley comunal.";
                      $var_articulo_libros3 .= "La presente Resolución rige a partir de la fecha de su expedición."; 
                      if(!$var_inscripcionDignatarios && !$var_reformaEstatutos){
                        $var_leyes_1 = "";
                        $var_gaceta = "";
                        $var_articulo_comunal1 = "";
                        $var_paragrafo_comunal1 = "";
                      }
               
                      $TiposLibros = explode(",", $radicado['id_tipo_registro_libro']);
                      if(!empty($TiposLibros)){
                        $var_libros_registrados = "";
                        
                        
                        foreach($TiposLibros as $valor){
                          $NombreLibro = TipoRegistroLibro::findOne($valor)['nombre_tipo_registro_libro'];
                          $var_libros_registrados .= $NombreLibro ."(Folios 1 a ___);";
                        }
                        $var_tipo_resolucion1 .= "ORDENA EL REGISTRO Y HABILITACIÓN DE LIBROS, ";
                        $var_tipo_resolucion2 .= "EL REGISTRO DE LIBROS, ";
                        $var_revision = "Que en el artículo 16 del Decreto 1529 de 1990 compilado en el Decreto 1066 de 2015, faculta a esta Entidad para ejercer inspección, vigilancia y control y registro de los siguientes libros: de actas de la asamblea, de registro de afiliados, de
                          inventarios y de tesorería, de las entidades sin ánimo de lucro de competencia de la Gobernación del Valle del Cauca.";
                        
                        $var_articulo_libros .= "Ordenar el registro, habilitar y sellar los siguientes libros
                          reglamentarios: ".$var_libros_registrados." para uso exclusivo de la entidad denominada " .$nombre_entidad .", del Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca. Entidad con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".ResolucionesController::mes($mes_personeria)." de ".$año_personeria.", otorgada por la Gobernación del Valle del Cauca. ";
                        $var_leyes .= ", Articulo 16 del Decreto 1529 de 1990 (compilado por el decreto único reglamentario 1066 de 2015)";
                        
                      }
                      $ResolucionesFile += 5;                      
                    }
                  break;            
                }
              
                $var_considerando = "Que el (la) señor (a) ".$presidente['nombre_dignatario']." identificado (a) con la cédula de ciudadanía No. ".$presidente['cedula_dignatario']. ", en calidad de ".$var_calidad_cargo." de la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] ." - (Valle del Cauca), ha solicitado a la Gobernación del Departamento del Valle del Cauca, ".$var_tipo_resolucion2. " según radicación Sade N° ".$radicado['sade']." e interna No. ".$radicado['n_radicado_interno']." de ".$diaradi." ".ResolucionesController::mes($mesradi)." de ".$añoradi.".";  
               
                if ($radicado['id_tipo_resolucion'] == 6){ // LIMPIEZA DE LOS ARTICULOS PARA EL FORMATO DE IMPUGNACION
                  $var_considerando = "Que mediante escrito de fecha __ de ________ de _______, recibido en este despacho el __ de _______ del mismo año bajo el sade N° ".$radicado['sade']."  e interna No. ".$radicado['n_radicado_interno']." de ".$diaradi." ".ResolucionesController::mes($mesradi)." de ".$añoradi.", el señor __________________________ en calidad de________________________  remitió documentación sobre _________________ en contra de __________ el __ de ______ de ________ de la ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", con su respetivo fallo de fecha __________ de ___ de ______.";
                }

                if ($radicado['id_tipo_resolucion'] != 1 && $radicado['id_tipo_resolucion'] != 3 ){ // LIMPIEZA DE LOS ARTICULOS PARA EL FORMATO DE IMPUGNACION
                  $var_articulo_inscripcion .= "Entidad con Personería Jurídica No. ".$numero_personeria." del ".$dia_personeria." de ".$mes_personeria." de ".$año_personeria.", otorgada por la Gobernación del Valle del Cauca. ";
                }
                // ESOGE LA PLANTILLA PARA USAR EN LA RESOLUCION
        				if ($ResolucionesFile == 1){ // PLANTILLA DIGNATARIOS
                    $var_comunicado = "COMUNIQUESE";
                    $document = new TemplateProcessor ('plantillas/Plantilla Dignatarios.docx');
                }elseif ($ResolucionesFile == 5) { // PLANTILLA LIBROS
        					  $var_comunicado = "COMUNIQUESE";
        					  $document = new TemplateProcessor ('plantillas/Plantilla Libros.docx');
                }else{ // PLANTILLA GENERAL
                    $document = new TemplateProcessor ('plantillas/Plantilla Resoluciones.docx');
                }
                //$document = new TemplateProcessor ('plantillas/Plantilla Resoluciones.docx');

                $document->setValue("retencion_documental",$tipoEntidad['codigo_trd']);

                $document->setValue("numero_resolucion",$resolucion['numero_resolucion']);                 
                
                $document->setValue("sdia",$dia);
                $document->setValue("smes",$mes);
                $document->setValue("saño",$año);

                $document->setValue("var_tipo_resolucion1",$var_tipo_resolucion1);
                $document->setValue("var_tipo_resolucion2",$var_tipo_resolucion2);
                $document->setValue("var_disciplina",$var_disciplina);
                $document->setValue("var_considerando",$var_considerando);

                $document->setValue("nombre_entidad",$entidad['nombre_entidad']);
                $document->setValue("municipio_entidad",$municipio_entidad['municipio']);

                $document->setValue("nombre_presidente",$presidente['nombre_dignatario']);
                $document->setValue("cedula_presidente",$presidente['cedula_dignatario']);
                $document->setValue("municipio_presidente",$municipio_presidente['municipio']);
                $document->setValue("departamento_presidente",$departamento_presidente['departamento']);

                $document->setValue("sade",$radicado['sade']);
                $document->setValue("numero_radicado",$radicado['n_radicado_interno']);       
                $document->setValue("mes_radicado",ResolucionesController::mes($mesradi));
                $document->setValue("dia_radicado",$diaradi);
                $document->setValue("año_radicado",$añoradi);

                $document->setValue("var_hechos",$var_hechos);
                $document->setValue("revision",$var_revision);

                $document->setValue("var_leyes",$var_leyes);
                $document->setValue("var_leyes_1",$var_leyes_1);
                $document->setValue("var_argumento_libros",$var_argumento_libros);
                $document->setValue("var_paragrafo_legal",$var_paragrafo_legal);
                $document->setValue("var_articulo_comunal1",$var_articulo_comunal1);
                $document->setValue("var_paragrafo_comunal1",$var_paragrafo_comunal1);
                $document->setValue("var_impugnacion_paragrafo_comunal",$var_impugnacion_paragrafo_comunal);
                $document->setValue("var_cancelacion_dignatario",$var_cancelacion_dignatario);
                $document->setValue("var_articulo_cpaca",$var_articulo_cpaca);

                $document->setValue("var_cambio_objeto_social_considerando",$var_cambio_objeto_social_considerando);
                $document->setValue("var_articulo_reformas",$var_articulo_reformas);
                $document->setValue("var_articulo_cambio_objeto_social2",$var_articulo_cambio_objeto_social2);

                $document->setValue("var_considerando_reformas",$var_considerando_reformas);


                $document->setValue("var_articulo_reconocimiento",$var_articulo_reconocimiento);
                $document->setValue("var_articulo_reconocimiento2",$var_articulo_reconocimiento2);
                $document->setValue("var_paragrafo_reconocimiento",$var_paragrafo_reconocimiento);
                $document->setValue("var_articulo_asimilacion",$var_articulo_asimilacion);

                $document->setValue("var_articulo_cancelacion",$var_articulo_cancelacion);
                $document->setValue("var_cancelacion_considerando",$var_cancelacion_considerando);
                $document->setValue("var_cancelacion_considerando2",$var_cancelacion_considerando2);
                $document->setValue("var_calidad_cargo",$var_calidad_cargo);
                $document->setValue("var_articulo_dignatarios",$var_articulo_dignatarios);
                $document->setValue("var_articulo_inscripcion",$var_articulo_inscripcion);
                $document->setValue("var_gaceta",$var_gaceta);

                $document->setValue("var_articulo_adhoc",$var_articulo_adhoc);

                $document->setValue("var_articulo_cancelacion_dignatarios1",$var_articulo_cancelacion_dignatarios1);
                $document->setValue("var_articulo_cancelacion_dignatarios2",$var_articulo_cancelacion_dignatarios2);

                $document->setValue("var_impugnacion_articulo",$var_impugnacion_articulo);

                $document->setValue("var_articulo_libros",$var_articulo_libros);
                $document->setValue("var_articulo_libros2",$var_articulo_libros2);
                $document->setValue("var_articulo_libros3",$var_articulo_libros3);
                $document->setValue("var_articulo_reforma",$var_articulo_reforma);


                $document->setValue("tipo_regimen",$tipo_regimen);
                $document->setValue("dignatarios_1",$dignatarios_1);
                $document->setValue("dignatarios_2",$dignatarios_2);

                $document->setValue("var_comunicado",$var_comunicado);

                $document->setValue("nombre_profesional",$profesional['nombre_profesional']);
                $document->setValue("cargo_profesional",$profesional['cargo_profesional']);

                $document->setValue("nombre_profesional_revisor",$profesional1['nombre_profesional']);
                $document->setValue("cargo_profesional_revisor",$profesional1['cargo_profesional']);
                
                $document->setValue("nombre_profesional_vobo",$profesional2['nombre_profesional']);
                $document->setValue("cargo_profesional_vobo",$profesional2['cargo_profesional']);


                $document->setValue("nombre_usuario",$usuario);
                $document->setValue("cargo_usuario",$cargo);
                $document->setValue("ubicacion_archivo",$entidad['ubicacion_archivos_entidad']);

                $document->saveAs('Resolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
                header('Content-Disposition: attachment; filename=Resolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx; charset=iso-8859-1');
                echo file_get_contents('Resolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');
                unlink('Resolucion de Personeria '.$entidad['personeria_n'].'-'.$entidad['personeria_year'].'.docx');

              break;
            }
          }
          }else{
            
            if($entidad != null){
              echo "<script> alert('No se refleja el pago de la gaceta');
              window.location.href = '?r=entidades%2Fview&id=".$entidad->id_entidad."'; </script>";
            }else{
              echo "<script> alert('No se refleja el pago de la gaceta');
              window.location.href = '?r=entidadcamaracomercio%2Fview&id=".$entidad_ivc->id_entidad_camara."'; </script>";
            }
        
          }
           ////////////////////////////////////// FECHA GACETA /////////////////////////////////////////////////////     
  
}

public function TipoCancelacion($var_tipo_resolucion1, $var_cancelacion_considerando, $var_tipo_resolucion2, $var_cancelacion_considerando2, $var_revision, $var_calidad_cargo, $var_articulo_dignatarios,
 $var_articulo_cancelacion, $nombre_entidad, $municipio_entidad, $tipoEntidad, $presidente, $municipio_presidente, $tipo){
  $var_tipo_resolucion1 .= "CANCELA LA PERSONERIA JURIDICA ".$tipo;
  $var_cancelacion_considerando .= "Que la Gobernación del Valle del Cauca le reconoció Personería Jurídica a la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, con finalidad (".$tipoEntidad['tipo_entidad'].") sin ánimo de lucro.";
  $var_tipo_resolucion2 .= "la cancelación de la personería jurídica ".$tipo;
  $var_cancelacion_considerando2 .= "Que teniendo en cuenta la imposibilidad de continuar con el desarrollo de su OBJETO SOCIAL: ______________________________________________________________________ decidiendo por  unanimidad ______________________ con un 100% la aprobación del cierre de la entidad y por consiguiente su disolución y liquidación, una vez se cumpla el procedimiento estatutario establecido en el  ________________ de sus estatutos y demás normas concordantes con la ley. "
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"  
  ."Que la entidad nombro como liquidador (es) al (los) señor (es) ".$presidente['nombre_dignatario']. ", identificado con la cédula de ciudadanía No. ".$presidente['cedula_dignatario']. ", expedida en ".$municipio_presidente['municipio']. ", en calidad de Liquidador Principal y como liquidador Suplente a la (el) señor (a) ____________________, identificada (o) con cedula de ciudadanía N° _________________, nombrado (s) mediante acta de asamblea _________________ N° ______ del ______ de __________ de _________ de la entidad denominada ________________________, con domicilio en el Municipio de _____________________, Departamento del Valle del Cauca. "
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>"  
  ."El Liquidador (a) procedió a publicar en el Diario ___________________________. informativo de alta circulación los tres (3) avisos, conforme lo estipula el artículo 19 del decreto 1529 de 1990, compilado mediante el Decreto N° 1066 de 2015, en los artículos 2.2.1.3.13, durante los días ________________________________________________________, informando a la ciudadanía sobre el proceso de disolución y liquidación e instando a los acreedores a hacer valer sus derechos."
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
  ."Que mediante Acta de Asamblea General __________ No.___ del ____ de ______ de ____, se presentó el informe del liquidador, estados financieros de liquidación y estado de resultado con corte al ____________________. Así como la aprobación de inventario y cuenta final de liquidación; de igual manera todos los activos de la ______________________________________, representados en muebles y enseres, los cuales fueron donados al _____________________________."
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
  ."Que, en el informe de los estados financieros para la liquidación de la entidad, con corte a __________________, el Revisor Fiscal expresó que la entidad ".$nombre_entidad. ", no cumple ______________________________________________________________________."
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
  ."Que, mediante Acta N° _________ del _______ de _____ de ____, se aprobó la cuenta final de liquidación conforme el art. 242 del Código de Comercio generando una distribución en ceros (o), según los derechos y valor de aportes entre sus asociados."
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
  ."Que, para efectos del bodegaje de los archivos de la Corporacion, se ha dispuesto que los mismos sean mantenidos por un término de cinco (5) años establecidos en la Ley. Teniendo en cuanta los estados financieros no hay remanentes de la liquidación v conforme con lo previsto en la regulación aplicable en los estatutos de la Corporacion y previa aprobación de los corporados de la cuenta final de liquidación, la misma se encuentra lista para su liquidación, indicando que a partir de la fecha la entidad  ".$nombre_entidad. ", queda en estado de Liquidación";
  $var_revision = "Que la solicitud reúne los requisitos para cancelar la Personería Jurídica de la entidad en comento a la luz del artículo 7° del Decreto Nacional 1529 del 12 de julio de 1990 y Sección IV del Dcto. 1088 del 25 de abril de 1991; compilado mediante el Decreto N° 1066 de 2015, en los artículos 2.2.1.3.13."
  ."<w:br w:type='line'/>" ."<w:br w:type='line'/>" 
  ."En mérito de lo anterior, el/la Directora del Departamento Administrativo de Jurídica de la Gobernación del Departamento del Valle del Cauca";
  $var_calidad_cargo = "Liquidador Principal y como liquidador Suplente el (la) señor (a) ___________________________, identificada (o) con cedula de ciudadanía N° ____________,";
  $var_articulo_dignatarios = "";
  $var_articulo_cancelacion = "Cancelar ".$tipo ." la Personería Jurídica a la entidad denominada ".$nombre_entidad. ", con domicilio en el Municipio de " .$municipio_entidad['municipio'] .", Departamento del Valle del Cauca, como entidad ".$tipoEntidad['tipo_entidad']. " sin ánimo de lucro reconocida mediante Resolución N° __________del ________ de _______ de ________, emanada de la _____________________________ representada legalmente por el señor (a) ".$presidente['nombre_dignatario']. ", identificado (a) con la cédula de ciudadanía No. ".$presidente['cedula_dignatario']. ", expedida en ".$municipio_presidente['municipio'];

  return array($var_tipo_resolucion1, $var_cancelacion_considerando, $var_tipo_resolucion2, $var_cancelacion_considerando2, $var_revision, $var_calidad_cargo, $var_articulo_dignatarios,
  $var_articulo_cancelacion);
}
    public function mes($mes){
      switch ($mes) {
        case 1:
          return "Enero";
          break;
        case 2:
          return "Febrero";
          break;

        case 3:
          return "Marzo";
          break;

        case 4:
          return "Abril";
          break;

        case 5:
          return "Mayo";
          break;

        case 6:
          return "Junio";
          break;

        case 7:
          return "Julio";
          break;

        case 8:
          return "Agosto";
          break;

        case 9:
          return "Septiembre";
          break;

        case 10:
          return "Octubre";
          break;

        case 11:
          return "Noviembre";
          break;

        case 12:
          return "Diciembre";
          break;


      }
    }
    
    protected function findModel($id)
    {
        if (($model = Resoluciones::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

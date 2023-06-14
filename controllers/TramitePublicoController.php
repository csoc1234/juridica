<?php

namespace app\controllers;

use Yii;
use app\models\TramitePublico;
use app\models\TramitePublicoSearch;
use app\models\TipomotivosolicTramitepublico;
use app\models\ClaseSolicTramitePublico;
use app\models\TipocertTramitepublico;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TemplateProcessor;
use yii\filters\AccessControl;
use app\models\User;


class TramitePublicoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function verificarElemento($elemento,$array){

        if(!in_array($elemento,$array)){
          array_push($array,$elemento);
        }
        return $array;
  
       }
       public function behaviors()
       {
       //Aqui se agregan los sitios que tendran restricción de acceso
        
        $permisos = ['index','view', 'create', 'update', 'delete'];
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
        $searchModel = new TramitePublicoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $CodigoResolucion = "FO-M4-P2-03";
        $FechaResolucion = "29/03/2017";
        $id_tramite_publico = $id;


        $dbTramite_Publico = TramitePublico::findOne($id_tramite_publico);
        $fecha = $dbTramite_Publico['fecha_tramitePublico'];
        list($año,$mes,$dia) = explode("-",$fecha);

        $document = new TemplateProcessor('plantillas/Plantilla Tramite Publico.docx');

        $document->setValue('Codigo',$CodigoResolucion);
        $document->setValue('FechaResolucion',$FechaResolucion);
        $document->setValue('Dia',$dia);
        $document->setValue('Mes',$mes);
        $document->setValue('Año',$año);

        $document->setValue('DirigidoA', $dbTramite_Publico['dirigido_tramitePublico']);
        $document->setValue('NombreSolicitante', $dbTramite_Publico['nombre_solicitante_tramitePublico']);
        $document->setValue('CedulaSolicitante', $dbTramite_Publico['cedula_tramitePublico']);
        $document->setValue('DireccionSolicitante', $dbTramite_Publico['direccion_tramitePublico']);
        $document->setValue('TelefonoSolicitante', $dbTramite_Publico['telefono_tramitePublico']);
        $document->setValue('EmailSolicitante', $dbTramite_Publico['email_tramitePublico']);
        $document->setValue('NombreEntidad', $dbTramite_Publico['nombre_entidad_tramitePublico']);
        $document->setValue('DireccionEntidad', $dbTramite_Publico['direccion_entidad_tramitePublico']);
        $document->setValue('TelefonoEntidad', $dbTramite_Publico['telefono_entidad_tramitePublico']);
        $document->setValue('EmailEntidad', $dbTramite_Publico['email_entidad_tramitePublico']);
        $document->setValue('NombreRepresentante', $dbTramite_Publico['nombre_represeLegal_tramitePublico']);

        $Temp1 = TipomotivosolicTramitepublico::findOne($dbTramite_Publico['motivo_solicitud_tramitePublico']);
        $document->setValue("MotivoSolicitud",$Temp1['nombreMotivo_tramite_publico']);

        $document->setValue('OtroCual', $dbTramite_Publico['otrosMotivoCert_tramite_publico']);

        $Temp2 = ClaseSolicTramitePublico::findOne($dbTramite_Publico['clase_solicitud_tramitePublico']);
        $document->setValue('ClaseSolicitud', $Temp2['nombreClase_tramite_publico']);

        $Temp3 = TipocertTramitepublico::findOne($dbTramite_Publico['tipocertificado_tramite_publico']);
        $document->setValue('TipoCertificado',$Temp3['nombreCert_tramite_publico']);

        $document->setValue('Cant', $dbTramite_Publico['cantidad_tipocert_tramite_publico']);

        
        $document->saveAs('Certificado tramite publico'.'.docx');

        header('Content-Disposition: attachment; filename=Certificado tramite publico'.'.docx; charset=iso-8859-1');
        echo file_get_contents('Certificado tramite publico'.'.docx');
        
    }

    public function actionCreate()
    {
        $model = new TramitePublico();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {         

        return $this->redirect(['view', 'id' => $model->id_tramite_publico]);

        }

        return $this->render('create', [
            'model' => $model,
        ]);

        
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_tramite_publico]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TramitePublico::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}

<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Validacion;
use app\models\Radicados;
use app\models\CertificarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use setasign\fpdi\fpdi;
use yii\widgets\Breadcrumbs;
use yii\filters\AccessControl;
use yii\db\Query; 
require_once('FPDI/src/autoload.php');
require_once('fpdf/rpdf.php');
require_once('FPDI/src/Fpdi.php');



class CertificarController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //Aqui se agregan los sitios que tendran restricción de acceso
    $only = ['index', 'update', 'update2', 'view'];
    return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => $only,
                'rules' => [
                    [
                        'actions' => ['index', 'update','update2', 'view',],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
              $valid_roles = [User::ROL_SUPERUSER,User::ROL_USER,User::ROL_REPARTIDOR,User::ROL_RADICADOR];
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

 
    public function actionIndex()
    {   
        $searchModel = new CertificarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
       
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

 
    public function actionCreate()
    {   $model = new Validacion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_validacion]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //Resoluciones
        if ($model->load(Yii::$app->request->post()) && $model->IDT_tramite== '2') {
            $año =substr($model->fecha_resolucion,0,4);
            $mes =substr($model->fecha_resolucion,5,2);
            $dia =substr($model->fecha_resolucion,8,2);
            echo shell_exec("python3 /var/www/html/personerias/controllers/segu_2.py $dia $mes $año $model->numero_resolucion $model->id_radicado 2>&1");
            sleep(1);
            //ejecutar python
            echo shell_exec("python3 /var/www/html/personerias/controllers/encryp.py $model->id_radicado 2>&1");
            //guardar archivo en temporal
            $archivo = UploadedFile::getInstance($model, 'archivo');
            if($archivo!=null){
                if(file_exists("/var/DocJuridica/Certificar/Radicado".$model->id_radicado)){
                    $newFilePath = "/var/DocJuridica/Certificar/Radicado".$model->id_radicado."/Radicado".$model->id_radicado.".pdf";
                    $uploadSuccess = $archivo->saveAs($newFilePath);
                    if (!$uploadSuccess) {
                        throw new CHttpException('Error uploading file.');  
                    }
                //modifcar archivo pdf
                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile($newFilePath);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $tplIdx = $pdf->importPage($pageNo);
                        $pdf->AddPage();
                        $pdf->useTemplate($tplIdx,['adjustPageSize' => true]);
                        $pdf->Image('img/codigo/codigo.png', 5, 323, 73, 23);
                        $pdf->Image('img/hash.jpg', 2, 85.25);
                        $pdf->SetXY(30,322);
                        $pdf->SetFont('Arial','',9);
                        $pdf->Write(0,'Radicado #'.$model->id_radicado);
                    }
                    $pdf->Output($newFilePath, "F");
                    //guardar Archivo en base de datos
                    //$content = file_get_contents($newFilePath);
                    $model->archivo = $newFilePath;
                    $model->save();
                    Yii::$app->db->createCommand("UPDATE `radicados` SET `estado` = '3' WHERE `id_radicado` = $model->id_radicado")
                    ->execute();
                    //eliminar archivo
                    //unlink($newFilePath);
                    return $this->redirect(['view', 'id' => $model->id_validacion]);
                }else{
                    mkdir("/var/DocJuridica/Certificar/Radicado".$model->id_radicado);
                    chmod("/var/DocJuridica/Certificar/Radicado".$model->id_radicado,0777);
                    $newFilePath = "/var/DocJuridica/Certificar/Radicado".$model->id_radicado."/Radicado".$model->id_radicado.".pdf";
                    $uploadSuccess = $archivo->saveAs($newFilePath);
                    if (!$uploadSuccess) {
                        throw new CHttpException('Error uploading file.');  
                    }
                //modifcar archivo pdf
                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile($newFilePath);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $tplIdx = $pdf->importPage($pageNo);
                        $pdf->AddPage();
                        $pdf->useTemplate($tplIdx,['adjustPageSize' => true]);
                        $pdf->Image('img/codigo/codigo.png', 5, 248, 73, 23);
                        $pdf->Image('img/hash.jpg', 2, 85.25);
                        $pdf->SetXY(30,246);
                        $pdf->SetFont('Arial','',9);
                        $pdf->Write(0,'Radicado #'.$model->id_radicado);
                    }
                    $pdf->Output($newFilePath, "F");
                    //guardar Archivo en base de datos
                    //$content = file_get_contents($newFilePath);
                    $model->archivo = $newFilePath;
                    $model->save();
                    Yii::$app->db->createCommand("UPDATE `radicados` SET `estado` = '3' WHERE `id_radicado` = $model->id_radicado")
                    ->execute();
                    //eliminar archivo
                    //unlink($newFilePath);
                   return $this->redirect(['view', 'id' => $model->id_validacion]);
                }
			    
            }else{
                $model->archivo = null;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id_validacion]);
            }
        }

        //certificados
        if ($model->load(Yii::$app->request->post()) && $model->IDT_tramite== '1') {
            $model->fecha_resolucion = '0000-00-00';
            $model->numero_resolucion = '000';
            //ejecutar python
            echo shell_exec("python3 /var/www/html/personerias/controllers/encryp.py $model->id_radicado");
            //guardar archivo en temporal
            $archivo = UploadedFile::getInstance($model, 'archivo');
            if($archivo!=null){
                if(file_exists("/var/DocJuridica/Certificar/".$model->id_radicado)){
                    $newFilePath = "/var/DocJuridica/Certificar/Radicado".$model->id_radicado."/Radicado".$model->id_radicado.".pdf";
                    $uploadSuccess = $archivo->saveAs($newFilePath);
                    if (!$uploadSuccess) {
                        throw new CHttpException('Error uploading file.');  
                    }
                //modifcar archivo pdf
                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile($newFilePath);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $tplIdx = $pdf->importPage($pageNo);
                        $pdf->AddPage();
                        $pdf->useTemplate($tplIdx,['adjustPageSize' => true]);
                        $pdf->Image('img/codigo/codigo.png', 5, 248, 73, 23);
                        $pdf->Image('img/hash.jpg', 2, 85.25);
                        $pdf->SetXY(30,246);
                        $pdf->SetFont('Arial','',9);
                        $pdf->Write(0,'Radicado #'.$model->id_radicado);
                    }
                    $pdf->Output($newFilePath, "F");
                    //guardar Archivo en base de datos
                    //$content = file_get_contents($newFilePath);
                    $model->archivo = $newFilePath;
                    $model->save();
                    Yii::$app->db->createCommand("UPDATE `radicados` SET `estado` = '3' WHERE `id_radicado` = $model->id_radicado")
                    ->execute();
                    //eliminar archivo
                    //unlink($newFilePath);
                    return $this->redirect(['view', 'id' => $model->id_validacion]);
                }else{
                    mkdir("/var/DocJuridica/Certificar/Radicado".$model->id_radicado);
                    chmod("/var/DocJuridica/Certificar/Radicado".$model->id_radicado,0777);
                    $newFilePath = "/var/DocJuridica/Certificar/Radicado".$model->id_radicado."/Radicado".$model->id_radicado.".pdf";
                    $uploadSuccess = $archivo->saveAs($newFilePath);
                    if (!$uploadSuccess) {
                        throw new CHttpException('Error uploading file.');  
                    }
                //modifcar archivo pdf
                    $pdf = new Fpdi();
                    $pageCount = $pdf->setSourceFile($newFilePath);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $tplIdx = $pdf->importPage($pageNo);
                        $pdf->AddPage();
                        $pdf->useTemplate($tplIdx,['adjustPageSize' => true]);
                        $pdf->Image('img/codigo/codigo.png', 5, 248, 73, 23);
                        $pdf->Image('img/hash.jpg', 2, 85.25);
                        $pdf->SetXY(30,246);
                        $pdf->SetFont('Arial','',9);
                        $pdf->Write(0,'Radicado #'.$model->id_radicado);
                    }
                    $pdf->Output($newFilePath, "F");
                    //guardar Archivo en base de datos
                    //$content = file_get_contents($newFilePath);
                    $model->archivo = $newFilePath;
                    $model->save();
                    Yii::$app->db->createCommand("UPDATE `radicados` SET `estado` = '3' WHERE `id_radicado` = $model->id_radicado")
                    ->execute();
                    //eliminar archivo
                    //unlink($newFilePath);
                    return $this->redirect(['view', 'id' => $model->id_validacion]);
                }
			    
            }else{
                $model->archivo = null;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id_validacion]);
            }
        }

        if ($model->archivo==null || Yii::$app->user->identity->id_rol == 1) {
        return $this->render('update', [
            'model' => $model,
        ]);
        } else {
        ?>
        <script>
        alert('Este radicado ya cuenta con un domuento si desea cambiarlo contacte con un administrador');
        window.location.href = 'index.php?r=certificar';
        </script>
        <?php
        }
    }


    protected function findModel($id)
    {
        if (($model = Validacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}

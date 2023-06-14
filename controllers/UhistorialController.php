<?php

namespace app\controllers;

use Yii;
use app\models\Uhistorial;
use app\models\UhistorialSearch;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use mPDF;
use yii\data\ActiveDataProvider;


class UhistorialController extends Controller
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
        
        $permisos =  ['index','view','createMPDF','samplepdf','sampleexcel','reportfiles'];

        if(User::IsAdministrador()){      
          $permisos = $this->verificarElemento('create',$permisos);
          $permisos = $this->verificarElemento('update',$permisos);      
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
         $searchModel = new UhistorialSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $session = Yii::$app->session;
         $session->set('query',$dataProvider->query);
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
    {
        $model = new Uhistorial();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_Uhistorial]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_Uhistorial]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Uhistorial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateMPDF(){
      
        $mpdf = new mPDF();

        $mpdf-> writeHTML($this->renderPartial('mpdf'));
        $mpdf->Output();
        exit;
    }
    public function actionSamplepdf() {

        $mpdf = new mPDF;
    
        $mpdf->setHeader('<div style="width: 100%; height: 80px;">

        <img src="img/logo2.png" width = "200px" >

          </div>');
        $mpdf->setFooter('Página {PAGENO}'. '   Generado por software personería juridíca');

        //Marca de agua
        //$mpdf->SetWatermarkText('ÉSTE DOCUMENTO NO TIENE VALIDEZ LEGAL');
        //$mpdf->showWatermarkText = true;
        //$mpdf->SetWatermarkImage('../img/logo.png');
        $mpdf->SetWatermarkImage('img/escudovalle.png');
        //$mpdf->showWatermarkImage = true;
        //$mpdf->SetWatermarkImage('https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Escudo_del_Valle_del_Cauca.svg/240px-Escudo_del_Valle_del_Cauca.svg.png');
        $mpdf->showWatermarkImage = true;

        $mpdf->SetTitle('Usuarios Eliminados'); //Título

        /*Configuracion de las páginas a adicionar*/
        $mpdf->AddPageByArray(array(
            'sheet-size' => 'Letter',
            'resetpagenum' => '1',
            'pagenumstyle' => '1',
        ));

        /*Texto, aqui se escriben las páginas*/
        $session = Yii::$app->session;
        $consulta = $session->get('query');
        $provider = new ActiveDataProvider([
            'query' => $consulta,
            'pagination' => [
                'pageSize' => 0,
              ],
        ]);
        $historia = $provider->getModels();
        //$historia = Historial::find()->asArray()->all();
        $arrayUser = array();
        for ($k = 0; $k< sizeof($historia); $k++){

            $sqlper = 'select nombre_funcionario from user where id='.$historia[$k]['U_id_usuario_modifica'];
            $arrayUser[$k] = Yii::$app->db->createCommand($sqlper)->queryScalar();

        }
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
        table .service,
        table .desc {
          text-align: left;
        }

        table td {
          padding: 20px;
          text-align: right;
        }

        table td.service,
        table td.desc {
          vertical-align: top;
        }

        table td.grand {
          border-top: 1px solid #5D6975;;
        }
    </style>
        <h1>Usuarios Eliminados</h1>
            <table style="."width:100%".">
                <thead>
                  <tr>
                    <th width='150'>".'ID USUARIO ELIMINA'."</th>
                    <th width='150'>".'USUARIO QUE ELIMINA'."</th>
                    <th width='150'>".'FECHA DE MODIFICACION'."</th>
                    <th width='150'>".'USUARIO ELMINADO'."</th>
                  </tr>
                </thead>";


        for($i =0; $i < sizeof($historia);$i++){
            $html =$html."
            <tbody>
                <tr>
                      <td width='150'>".$historia[$i]['U_id_usuario_modifica']."</td>
                      <td width='150'>".$arrayUser[$i]."</td>
                      <td width='150'>".$historia[$i]['U_fecha_modificacion']."</td>
                      <td width='150'>".$historia[$i]['U_nombre_eliminado']."</td>
                </tr>
            </tbody>
            ";
        }
        $html = $html."</table>";
        $mpdf->WriteHTML($html);
        /*Fin de las páginas*/

        /*Se da la salida del PDF*/
        //$mpdf->Output();
        $mpdf->Output('Usuarios_eliminados.pdf','D'); //Para que descargue automaticamente
        exit;
    }

    public function actionSampleexcel(){

        $session = Yii::$app->session;
        $consulta = $session->get('query');
        $provider = new ActiveDataProvider([
            'query' => $consulta,
            'pagination' => [
                'pageSize' => 0,
              ],
        ]);
        $historia = $provider->getModels();
        //$historia = Historial::find()->asArray()->all();
        $arrayUser = array();
        for ($k = 0; $k< sizeof($historia); $k++){

            $sqlper = 'select nombre_funcionario from user where id='.$historia[$k]['U_id_usuario_modifica'];
            $arrayUser[$k] = Yii::$app->db->createCommand($sqlper)->queryScalar();
        }
        $html = "

            <title> HISTORIAL </title>

            <table style="."width:100%".">
            <tr>
                <td width='150'>".'ID USUARIO ELIMINA'."</td>
                <td width='150'>".'USUARIO QUE ELIMINA'."</td>
                <td width='150'>".'FECHA DE MODIFICACION'."</td>
                <td width='150'>".'USUARIO ELIMINADO'."</td>
            </tr> ";


        for($i =0; $i < sizeof($historia);$i++){
            $html =$html."
            <tr>
                <td width='150'>".$historia[$i]['U_id_usuario_modifica']."</td>
                <td width='150'>".utf8_decode($arrayUser[$i])."</td>
                <td width='150'>".$historia[$i]['U_fecha_modificacion']."</td>
                <td width='150'>".utf8_decode($historia[$i]['U_nombre_eliminado'])."</td>

            </tr>
            ";

        }

        $html = $html."</table>";
        
        header("Content-Type:application/vnd.ms-excelxls");
        header("Content-disposition:attachment; filename=Historial.xls");
        echo $html;
    }
    public function actionReportfiles(){
        
        switch (\Yii::$app->request->post('submit')) {
            case 'PDFSubmit':
                $this->actionSamplepdf();
            
            case 'ExcelSubmit':
                $this->actionSampleexcel();
        }
    }
}

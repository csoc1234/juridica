<?php
namespace app\controllers;
use Yii;
use app\models\Historial;
use app\models\Entidades;
use app\models\HistorialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use app\models\Dignatarios;
use app\models\Radicados;
use mPDF;
use yii\data\ActiveDataProvider;

class HistorialController extends Controller
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
        
        $permisos = ['index','index1','index2','index3','createMPDF','samplepdf','sampleexcel','reportfiles'];
  
        if(User::IsAdministrador()){    
          $permisos = $this->verificarElemento('create',$permisos);   
                
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
        $searchModel = new HistorialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $session = Yii::$app->session;
        $session->set('query',$dataProvider->query);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => "",
        ]);
    }

    public function actionIndex1($id)
    {
        $searchModel = new HistorialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['and', ['id_tabla_modificada'=>$id],['tabla_modificada' => 'ENTIDADES'] ]);
        $titulo = Entidades::findOne($id);
        $session = Yii::$app->session;
        $session->set('query',$dataProvider->query);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => ": ".$titulo['nombre_entidad']
        ]);
    }

    public function actionIndex2($id)
    {
        $searchModel = new HistorialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['and', ['id_tabla_modificada'=>$id],['tabla_modificada' => 'DIGNATARIOS'] ]);
        $titulo = Dignatarios::findOne($id);
        $session = Yii::$app->session;
        $session->set('query',$dataProvider->query);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => ": ".$titulo['nombre_dignatario']
        ]);
    }

    public function actionIndex3($id)
    {
        $searchModel = new HistorialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['and', ['id_tabla_modificada'=>$id],['tabla_modificada' => 'RADICADOS'] ]);
        $titulo = Radicados::findOne($id);
        $session = Yii::$app->session;
        $session->set('query',$dataProvider->query);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'titulo' => ": Radicado N°".$titulo['id_radicado']
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
        $model = new Historial();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_historial]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    protected function findModel($id)
    {
        if (($model = Historial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionCreateMPDF(){
        //$mpdf = new mPDF(['format' => 'Legal']);
        $mpdf = new mPDF();

        $mpdf-> writeHTML($this->renderPartial('mpdf'));
        $mpdf->Output();
        exit;
    }


    public function actionSamplepdf($selection, $Full = false) {

        $mpdf = new mPDF;
        //$mpdf = new mPDF;
        //Header ccs style
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

        $mpdf->SetTitle('Historias'); //Título

        /*Configuracion de las páginas a adicionar*/
        $mpdf->AddPageByArray(array(
            'sheet-size' => 'Letter',
            'resetpagenum' => '1',
            'pagenumstyle' => '1',
        ));

        /*Texto, aqui se escriben las páginas*/
        if($Full == false){

            $provider =  new \yii\data\ActiveDataProvider([
                'query' => Historial::find()->where(['id_historial' => $selection]),

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

        $historia = $provider->getModels();
        //$historia = Historial::find()->asArray()->all();
        $arrayUser = array();
        for ($k = 0; $k< sizeof($historia); $k++){

            $sqlper = 'select nombre_funcionario from user where id='.$historia[$k]['id_usuario_modifica'];
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
        <h1>Historial de los movimientos</h1>
            <table style="."width:100%".">
                <thead>
                  <tr>
                    <th width='150'>".'NOMBRE EVENTO'."</th>
                    <th width='150'>".'FECHA MODIFICACION'."</th>
                    <th width='150'>".'CAMPO MODIFICADO'."</th>
                    <th width='150'>".'USUARIO'."</th>
                    <th width='150'>".'TABLA MODIFICADA'."</th>
                  </tr>
                </thead>";


        for($i =0; $i < sizeof($historia);$i++){
            $html =$html."
            <tbody>
                <tr>
                      <td width='150'>".$historia[$i]['nombre_evento']."</td>
                      <td width='150'>".$historia[$i]['fecha_modificacion']."</td>
                      <td width='150'>".$historia[$i]['nombre_campo_modificado']."</td>
                      <td width='150'>".$arrayUser[$i]."</td>
                      <td width='150'>".$historia[$i]['tabla_modificada']."</td>
                </tr>
            </tbody>
            ";
        }
        $html = $html."</table>";
        $mpdf->WriteHTML($html);
        /*Fin de las páginas*/

        /*Se da la salida del PDF*/
        //$mpdf->Output();
        $mpdf->Output('Reporte_historial.pdf','D'); //Para que descargue automaticamente
        exit;
    }

    public function actionSampleexcel($selection, $Full = false){

      if($Full == false){

            $provider =  new \yii\data\ActiveDataProvider([
                'query' => Historial::find()->where(['id_historial' => $selection]),

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

        $historia = $provider->getModels();
        //$historia = Historial::find()->asArray()->all();
        $arrayUser = array();
        for ($k = 0; $k< sizeof($historia); $k++){

            $sqlper = 'select nombre_funcionario from user where id='.$historia[$k]['id_usuario_modifica'];
            $arrayUser[$k] = Yii::$app->db->createCommand($sqlper)->queryScalar();

        }
        $html = "

            <title> HISTORIAL </title>

            <table style="."width:100%".">
            <tr>
                <td width='150'>".'NOMBRE EVENTO'."</td>
                <td width='150'>".'FECHA MODIFICACION'."</td>
                <td width='150'>".'CAMPO MODIFICADO'."</td>
                <td width='150'>".'TABLA MODIFICADA'."</td>
                <td width='150'>".'ID USUARIO'."</td>
                <td width='150'>".'USUARIO'."</td>
            </tr> ";


        for($i =0; $i < sizeof($historia);$i++){
            $html =$html."
            <tr>
                <td width='150'>".utf8_decode($historia[$i]['nombre_evento'])."</td>
                <td width='150'>".$historia[$i]['fecha_modificacion']."</td>
                <td width='150'>".utf8_decode($historia[$i]['nombre_campo_modificado'])."</td>
                <td width='150'>".utf8_decode($historia[$i]['tabla_modificada'])."</td>
                <td width='150'>".$historia[$i]['id_usuario_modifica']."</td>
                <td width='150'>".utf8_decode($arrayUser[$i])."</td>
            </tr>
            ";

        }

        $html = $html."</table>";

        header("Content-Type:application/vnd.ms-excelxls");
        header("Content-disposition:attachment; filename=Historial.xls");
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
                    $this->redirect(["historial/index"]);
                  }

              break;

            case 'ExcelSubmit':
              if($selection != null){
                  $this->actionSampleexcel($selection);
                  }else{
                    Yii::$app->session->setFlash('Error', "No ha seleccionado ninguna casilla, intente generar el reporte completo.");
                    $this->redirect(["historial/index"]);
                  }

              break;

            case 'PDFSubmitFull':
                  $Full = true;
                  $this->actionSamplepdf(null, $Full);
              break;

            case 'ExcelSubmitFull':
                  $Full = true;
                  $this->actionSampleexcel(null, $Full);
              break;    
          }
        }

}

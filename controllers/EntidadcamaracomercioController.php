<?php

namespace app\controllers;

use Yii;
use app\models\Entidadcamaracomercio;
use app\models\EntidadcamaracomercioSearch;
use app\models\Certificados;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use kartik\select2\Select2;

class EntidadcamaracomercioController extends Controller
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
        
        $permisos = ['index','view',''];
        if(User::IsAdministrador()){
            $permisos = $this->verificarElemento('delete',$permisos); 
        }
        if(User::IsAdministrador() || User::IsTramitador()){    
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
        $searchModel = new EntidadcamaracomercioSearch();
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

    public function actionCreate($id)
    {
        $model = new Entidadcamaracomercio();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $certificado = Certificados::findOne($id);
            $certificado->id_entidad_camara = $model->id_entidad_camara;
            $certificado->save(false);
            return $this->redirect(['view', 'id' => $model->id_entidad_camara]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
 
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_entidad_camara]);
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
        if (($model = Entidadcamaracomercio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

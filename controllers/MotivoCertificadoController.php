<?php

namespace app\controllers;

use Yii;
use app\models\MotivoCertificado;
use app\models\MotivoCertificadoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

class MotivoCertificadoController extends Controller
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
        $searchModel = new MotivoCertificadoSearch();
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
    {
        $model = new MotivoCertificado();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_motivo]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_motivo]);
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
        if (($model = MotivoCertificado::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\PasswordForm;
use app\models\UserSearch;
use app\models\Uhistorial;
use app\assets\AppAsset;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Carbon\Carbon;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

class UserController extends Controller
{

     public function behaviors()
     {
            
         $only = ['index', 'roles', 'create', 'update', 'view','password','delete','Uhistorial'];
         return [
                 'access' => [
                     'class' => AccessControl::className(),
                     'only' => $only,
                     'rules' => [
                         [
                             'actions' => ['roles', 'index', 'registro', 'update', 'view', 'delete','Uhistorial'],
                             'allow' => true,
                             'roles' => ['@'],
                             'matchCallback' => function ($rule, $action) {
                   $valid_roles = [User::ROL_ADMINISTRADOR];
                   return User::roleInArray($valid_roles) && User::isActive();
                           }
                         ],
			[
                             'actions' => ['password'],
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
        $searchModel = new UserSearch();
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
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $this->saveModel($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function saveModel($model){

        if($model['id_privilegio'] != null){
            $model->id_privilegio = implode($delimiter = ',', $model->arrayPrivilegios);
          }  
        $model->save(false);
        return true;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model = User::findOne($model->id);
        if ($model->load(Yii::$app->request->post()) && $this->saveModel($model)){
            $msgreg = "Usuario actualizado correctamente";
            return $this->render('view', ['model' => $model, 'msgreg' => $msgreg]);             
  
        } else {
            $msgreg = "No se pudo actualizar el usuario";
            return $this->render('update', ['model' => $model, 'msgreg' => $msgreg]);   
        }
    }

    public function actionDelete($id){
        $model = new User();
        $nombre = User::findOne($id);
        $model2 = New Uhistorial();
        $model2->U_id_usuario_modifica = Yii::$app->user->identity->id;
        $model2->U_fecha_modificacion = Carbon::now('America/Bogota')->toDateTimeString();
        $model2->U_nombre_eliminado = $nombre['nombre_funcionario'] ;
        $model2->U_nombre_usuario_modifica = Yii::$app->user->identity->nombre_funcionario;
        $model2->save();
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

     public function actionPassword()
     {
         $model = new PasswordForm();
         $user = new User();
         $msgreg = null;
         if ($model->load(Yii::$app->request->post())) {
             if ($user = $model->contraseña()) {
                 $msgreg = 'El cambio de la contraseña ha sido exitoso';
                 Yii::$app->mailer->compose('contraseña.php',[
                   'nombres' => $user->nombre_funcionario,
                   'apellido1' => "",
                   'apellido2' => "",
                   'password' => $model->password,
                 ])
                 ->setTo($user->email)
                 ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                 ->setSubject("Portal personerias - Seguridad") 
                 ->send();
                 $model->password = "";
                 $model->password_anterior = "";
                 $model->password_copy = "";
                 return $this->render('password', ['model' => $model, 'msgreg' => $msgreg]);
             }

             return $this->render('password', [
             'model' => $model,
             'msgreg' => $msgreg,
         ]);
         }else{

             }

         return $this->render('password', [
             'model' => $model,
             'msgreg' => $msgreg,
         ]);
     }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}

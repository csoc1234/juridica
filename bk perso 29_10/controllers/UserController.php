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

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
     {
             //Aqui se agregan los sitios que tendran restricción de acceso
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
                   $valid_roles = [User::ROL_SUPERUSER];
                   return User::roleInArray($valid_roles) && User::isActive();
                           }
                         ],
			[
                             'actions' => ['password'],
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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model = User::findOne($model->id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //s$user = RegistroForm::findOne();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /** */
    public function actionDelete($id){
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



    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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
                 ->setTo($user->email) // para
                 ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name]) // de
                 ->setSubject("Portal personerias - Seguridad") // asunto
                 //->setTextBody($mensaje) // cuerpo del mensaje
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
             //  print_r($model->errors);

             }

         return $this->render('password', [
             'model' => $model,
             'msgreg' => $msgreg,
         ]);
     }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}

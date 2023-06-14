<?php

namespace app\controllers;

use Yii;
use app\models\GruposCargos;
use app\models\GruposCargosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * GruposCargosController implements the CRUD actions for GruposCargos model.
 */
class GruposCargosController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
     {
             //Aqui se agregan los sitios que tendran restricción de acceso
         $only = ['index','create', 'update', 'view'];
         return [
                 'access' => [
                     'class' => AccessControl::className(),
                     'only' => $only,
                     'rules' => [
                         [
                             'actions' => [ 'index', 'create', 'view'],
                             'allow' => true,
                             'roles' => ['@'],
                 'matchCallback' => function ($rule, $action) {
                   $valid_roles = [User::ROL_USER];
                   return User::roleInArray($valid_roles) && User::isActive();
                           }
                         ],
                         [
                             'actions' => [ 'index', 'create', 'update', 'view'],
                             'allow' => true,
                             'roles' => ['@'],
                 'matchCallback' => function ($rule, $action) {
                   $valid_roles = [User::ROL_SUPERUSER];
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
     * Lists all GruposCargos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GruposCargosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GruposCargos model.
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
     * Creates a new GruposCargos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GruposCargos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_grupo_cargos]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GruposCargos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_grupo_cargos]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GruposCargos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GruposCargos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GruposCargos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GruposCargos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

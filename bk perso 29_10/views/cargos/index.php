<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CargosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/web/img/favicon.ico']);
$this->title = 'Cargos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cargos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Cargo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php if( isset(Yii::$app->user->identity->id_rol) && Yii::$app->user->identity->id_rol == 1){  ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_cargo',
            'nombre_cargo',

            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view}{update}',],
        ],
    ]); ?>
</div>
<?php }else{  ?>

  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
          ['class' => 'yii\grid\SerialColumn'],

          //'id_cargo',
          'nombre_cargo',

          ['class' => 'yii\grid\ActionColumn',
          'template'=> '{view}',],
      ],
  ]); ?>
</div>
<?php }  ?>

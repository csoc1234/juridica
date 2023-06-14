<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PreciosTramitesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Precios Tramites';
$this->params['breadcrumbs'][] = $this->title;
if(isset(Yii::$app->user->identity->id_rol)&& Yii::$app->user->identity->id_rol != 1){
?>
<div class="precios-tramites-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Agregar Precios Tramites', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id_precio_tramite',
            'tramite:ntext',
            'precio',

           ['class' => 'yii\grid\ActionColumn',
                'header' => 'Opc',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template'=>'{view} ',

            ],
        ],
    ]); ?>
</div>
<?php 
    }else{
?>
<div class="precios-tramites-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Agregar Precios Tramites', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id_precio_tramite',
            'tramite:ntext',
            'precio',

           ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<?php
    }
?>
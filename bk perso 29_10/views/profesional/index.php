<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfesionalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profesionals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profesional-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Profesional', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nombre_profesional',
            'cargo_profesional',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

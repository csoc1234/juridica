<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GruposCargosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupos Cargos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupos-cargos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Grupo de Cargos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nombre_grupo_cargo',
            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view}',],
        ],
    ]); ?>
</div>


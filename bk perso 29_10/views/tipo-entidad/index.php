<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\TipoEntidad;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoEntidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Entidads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-entidad-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tipo Entidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_tipo_entidad',
            'tipo_entidad',
            'codigo_trd:ntext',
            //'revision',
            //'gaceta',
            'activo:boolean',

            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Opc',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template'=>'{view} {update}',

            ],
        ],
    ]); ?>
</div>

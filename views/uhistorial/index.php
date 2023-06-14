<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UhistorialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios eliminados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uhistorial-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?= Html::beginForm(['reportfiles'],'post'); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::submitButton('Generar Reporte PDF',['name' => 'submit' , 'value' => 'PDFSubmit', 'class'=> 'btn btn-danger', ]); ?>
        <?= Html::submitButton('Generar Reporte Excel',[ 'name' => 'submit' , 'value' => 'ExcelSubmit' ,'class'=> 'btn btn-success', ]);?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'U_id_usuario_modifica',
            'U_nombre_usuario_modifica',
            'U_fecha_modificacion',
            'U_nombre_eliminado',

            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view}',],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

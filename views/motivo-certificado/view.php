<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MotivoCertificado */

$this->title = $model->id_motivo;
$this->params['breadcrumbs'][] = ['label' => 'Motivo Certificados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-certificado-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_motivo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_motivo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Desea eliminar este motivo?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_motivo',
            'nombre_motivo',
            'descripcion_motivo:ntext',
        ],
    ]) ?>

</div>

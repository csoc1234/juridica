<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MotivoCertificado */

$this->title = 'Update Motivo Certificado: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Motivo Certificados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_motivo, 'url' => ['view', 'id' => $model->id_motivo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="motivo-certificado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

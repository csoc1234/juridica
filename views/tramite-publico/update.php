<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TramitePublico */

$this->title = 'Update Tramite Publico: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Tramite Publicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tramite_publico, 'url' => ['view', 'id' => $model->id_tramite_publico]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tramite-publico-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PreciosTramites */

$this->title = 'Actualizar Precios Tramites: ' . $model->id_precio_tramite;
$this->params['breadcrumbs'][] = ['label' => 'Precios Tramites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_precio_tramite, 'url' => ['view', 'id' => $model->id_precio_tramite]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="precios-tramites-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

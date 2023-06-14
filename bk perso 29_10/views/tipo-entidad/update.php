<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoEntidad */

$this->title = 'Actualizar Tipo Entidad: '.$model->tipo_entidad;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Entidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_entidad, 'url' => ['view', 'id' => $model->id_tipo_entidad]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-entidad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

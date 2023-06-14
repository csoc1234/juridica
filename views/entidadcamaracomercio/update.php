<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Entidadcamaracomercio */

$this->title = 'Actualizar Entidad:';
$this->params['breadcrumbs'][] = ['label' => 'Entidadcamaracomercios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entidad_camara, 'url' => ['view', 'id' => $model->id_entidad_camara]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entidadcamaracomercio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

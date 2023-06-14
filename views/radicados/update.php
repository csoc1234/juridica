<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Radicados */

$this->title = 'Actualizar Radicado #'. $model->id_radicado;
$this->params['breadcrumbs'][] = ['label' => 'Radicados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_radicado, 'url' => ['view', 'id' => $model->id_radicado]];
$this->params['breadcrumbs'][] = 'Actualizar';

if(!isset($msg)){
  $msg = null;
}

if(!isset($update)){
  $update = true;
}
?>
<div class="radicados-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'update' => $update,
        'msg'=> $msg,
        'entidades' => $entidades,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dignatarios */

$this->title = 'Modificar Dignatario: ' . $model->nombre_dignatario;
$this->params['breadcrumbs'][] = ['label' => 'Dignatarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dignatario, 'url' => ['view', 'id' => $model->id_dignatario]];
$this->params['breadcrumbs'][] = 'Update';
if(!isset($msg)){
  $msg = null;
}
?>
<div class="dignatarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'update' => true,
        'msg' => $msg,
    ]) ?>

</div>

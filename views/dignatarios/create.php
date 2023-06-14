<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dignatarios */

$this->title = 'Crear Dignatarios';
$this->params['breadcrumbs'][] = ['label' => 'Dignatarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!isset($msg)){
  $msg = null;
}
?>
<div class="dignatarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'update' => false,
        'msg' => $msg,
    ]) ?>

</div>

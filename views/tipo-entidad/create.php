<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoEntidad */

$this->title = 'Crear Tipo Entidad';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Entidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-entidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

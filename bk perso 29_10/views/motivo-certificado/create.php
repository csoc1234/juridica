<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MotivoCertificado */

$this->title = 'Create Motivo Certificado';
$this->params['breadcrumbs'][] = ['label' => 'Motivo Certificados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-certificado-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

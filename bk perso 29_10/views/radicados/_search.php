<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RadicadosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="radicados-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_radicado') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'id_tipo_tramite') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'id_usuario_tramita') ?>

    <?php // echo $form->field($model, 'sade') ?>

    <?php // echo $form->field($model, 'ubicacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

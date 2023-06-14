<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HistorialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="historial-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_historial') ?>

    <?= $form->field($model, 'nombre_evento') ?>

    <?= $form->field($model, 'id_tabla_modificada') ?>

    <?= $form->field($model, 'fecha_modificacion') ?>

    <?= $form->field($model, 'nombre_campo_modificado') ?>

    <?php // echo $form->field($model, 'valor_anterior_campo') ?>

    <?php // echo $form->field($model, 'valor_nuevo_campo') ?>

    <?php // echo $form->field($model, 'id_usuario_modifica') ?>

    <?php // echo $form->field($model, 'tabla_modificada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

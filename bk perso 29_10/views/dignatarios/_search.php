<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DignatariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dignatarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_dignatario') ?>

    <?= $form->field($model, 'cedula_dignatario') ?>

    <?= $form->field($model, 'nombre_dignatario') ?>

    <?= $form->field($model, 'estado')->checkbox() ?>

    <?= $form->field($model, 'id_municipio_expedicion') ?>

    <?php // echo $form->field($model, 'fecha_ingreso') ?>

    <?php // echo $form->field($model, 'id_entidad') ?>

    <?php // echo $form->field($model, 'id_cargo') ?>

    <?php // echo $form->field($model, 'id_grupo_cargos') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

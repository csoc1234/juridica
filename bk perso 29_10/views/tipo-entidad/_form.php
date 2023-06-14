<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoEntidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-entidad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_entidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_trd')->textarea(['rows' => 1]) ?>
    <?= $form->field($model, 'revision')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'gaceta')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, "activo")->dropDownList([0 =>"INACTIVO",1=>"ACTIVO"],["prompt"=>"Seleccione el estado"]) ?>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

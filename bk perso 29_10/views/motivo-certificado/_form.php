<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MotivoCertificado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="motivo-certificado-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_motivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion_motivo')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

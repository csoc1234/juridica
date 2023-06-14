<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profesional */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profesional-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_profesional')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cargo_profesional')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

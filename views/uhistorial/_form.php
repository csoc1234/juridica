<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Uhistorial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uhistorial-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'U_id_usuario_modifica')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'U_fecha_modificacion')->textInput() ?>

    <?= $form->field($model, 'U_nombre_eliminado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'U_nombre_usuario_modifica')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

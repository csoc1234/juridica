<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UhistorialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uhistorial-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_Uhistorial') ?>

    <?= $form->field($model, 'U_id_usuario_modifica') ?>

    <?= $form->field($model, 'U_fecha_modificacion') ?>

    <?= $form->field($model, 'U_nombre_eliminado') ?>

    <?= $form->field($model, 'U_nombre_usuario_modifica') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

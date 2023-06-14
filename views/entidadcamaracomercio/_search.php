<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadcamaracomercioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entidadcamaracomercio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_entidad_camara') ?>

    <?= $form->field($model, 'id_depertamento') ?>

    <?= $form->field($model, 'id_municipio') ?>

    <?= $form->field($model, 'id_depertamento_camara') ?>

    <?= $form->field($model, 'id_municipio_camara') ?>

    <?php // echo $form->field($model, 'nombre_entidad_camara') ?>

    <?php // echo $form->field($model, 'direccion_entidad') ?>

    <?php // echo $form->field($model, 'nombre_representante') ?>

    <?php // echo $form->field($model, 'cedula_representante') ?>

    <?php // echo $form->field($model, 'nit') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

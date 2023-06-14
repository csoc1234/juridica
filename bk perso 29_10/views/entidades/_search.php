<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadesSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="entidades-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_entidad') ?>

    <?= $form->field($model, 'personeria_year') ?>

    <?= $form->field($model, 'personeria_n') ?>

    <?= $form->field($model, 'nombre_entidad') ?>



    <?php // echo $form->field($model, 'fecha_reconocimiento') ?>

    <?php // echo $form->field($model, 'municipio_entidad') ?>

    <?php // echo $form->field($model, 'direccion_entidad') ?>

    <?php // echo $form->field($model, 'telefono_entidad') ?>

    <?php // echo $form->field($model, 'fax_entidad') ?>

    <?php // echo $form->field($model, 'email_entidad') ?>

    <?php // echo $form->field($model, 'id_tipo_entidad') ?>

    <?php // echo $form->field($model, 'id_clase_entidad') ?>

    <?php // echo $form->field($model, 'objetivos_entidad') ?>

    <?php // echo $form->field($model, 'fecha_estatutos') ?>

    <?php // echo $form->field($model, 'ubicacion_archivos_entidad') ?>

    <?php // echo $form->field($model, 'fecha_gaceta') ?>

    <?php // echo $form->field($model, 'datos_digitales') ?>

    <?php // echo $form->field($model, 'estado_entidad') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

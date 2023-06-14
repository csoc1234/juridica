<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Entidades Camara de Comercio';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['/user']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="camara-comercio">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nombre_entidad')->textInput() ?>
        <?= $form->field($model, 'id_municipio_entidad') ?>
        <?= $form->field($model, 'direccion_entidad')->textInput() ?>
        <?= $form->field($model, 'id_municipio_camara_comercio') ?>
        <?= $form->field($model, 'nit_entidad')->textInput() ?>
        <?= $form->field($model, 'nombre_representante_entidad')->textInput() ?>
        <?= $form->field($model, 'cedula_representante')->textInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- camara-comercio -->

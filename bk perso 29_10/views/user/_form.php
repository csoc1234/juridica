<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Roles;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'cedula_funcionario')->textInput(['disabled'=> true]) ?>
    <?= $form->field($model, 'nombre_funcionario')->textInput() ?>
    <?= $form->field($model, 'cargo_funcionario')->textInput() ?>
    <?= $form->field($model, "status")->dropDownList([0 =>"INACTIVO",10=>"ACTIVO"],["prompt"=>"Seleccione el estado"]); ?>
    <?php
     $roles = Roles::find()->asArray()->all();
     $tipoList=ArrayHelper::map($roles,'id_rol','rol');
     echo $form->field($model, "id_rol")->dropDownList($tipoList,['prompt'=>'Seleccione el tipo de rol']);
     ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

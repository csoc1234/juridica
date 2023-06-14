<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Standard;
use app\models\User;
//New
/*use yii\helpers\ArrayHelper;
use app\models\SignupForm;
use yii\bootstrap\ActiveField;*/
//Fin new

$this->title = 'Registro';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['/user']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($msgreg !== null){  ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times; </button>
            <h4> Información </h4>
            <?php print $msgreg; ?>
            <?php } ?>
        </div>
    <p>Por favor, rellene los siguientes campos para registrar un funcionario:</p>

    <div class="row">
        <div class="col-lg-5">
           <!--Antes id = form-signup-->
            <?php  $form = ActiveForm::begin(['id' => 'form-registro']);  ?>

                <?= $form->field($model, 'cedula_funcionario')->textInput() ?>
                <?= $form->field($model, 'nombre_funcionario')->textInput() ?>
                <?= $form->field($model, 'cargo_funcionario')->textInput() ?>
                <?php
                $form->field($model, 'cargo_funcionario')->textInput() 
                //echo echo $form->field($model, "cargo_funcionario")->dropDownList([2 =>"Tramite",3=>"Finalizado",4=>"Rechazado"],["prompt"=>"Seleccione un cargo"]);
                 ?>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                <?php
                $roles=Standard::find()->all();
                $rolesList=ArrayHelper::map($roles,'id_rol','rol');
                echo $form->field($model, 'id_rol')->dropDownList($rolesList,['prompt'=>'Por favor seleccione un rol']);
                ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Registrar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>

                    <!--?= Html::a( 'Restroceder', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>-->
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

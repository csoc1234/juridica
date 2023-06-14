<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Standard;
use app\models\User;
use app\models\Roles;
use app\models\Privilegio;
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
                 ?>
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

               

                <?php 
                    $roles= ArrayHelper::map(Roles::find()->all(),'id_rol','rol');
                    echo $form->field($model,'id_rol')
                    ->dropDownList($roles,['prompt'=>'Seleccione el rol',

                    'onchange' => 'onchangePrivilegio($(this).val())'        
                              
                    ]);       
                ?> 
               
               <div class="form-group" id = "privilegio" hidden = "true"> 
                    <?php 

                        $privilegio = ArrayHelper::map(Privilegio::find()->all(),'id_privilegio','nombre_privilegio');
                        echo $form->field($model,'id_privilegio')
                        ->checkBoxList($privilegio); 
                    ?> 
             </div>

                <?= $form->field($model, 'password')->passwordInput() ?>
                  <?= $form->field($model, 'password_copy')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Registrar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>
               </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>

function onchangePrivilegio(Opcion) {
   $.ajax({
   dataType:"html",
   type: "post",
   success: function(data){
     //Ocultar la entidad al ser Reconocimiento de personeria
    if(Opcion == 2) $('#privilegio').show(true);    
    else $('#privilegio').hide(true);     
   }
  });
}
</script>
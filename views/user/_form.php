<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Roles;
use app\models\Privilegio;
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

        $roles= ArrayHelper::map(Roles::find()->all(),'id_rol','rol');
        echo $form->field($model,'id_rol')
        ->dropDownList($roles,['prompt'=>'Seleccione el rol',

        'onchange' => 'onchangePrivilegio($(this).val())'        
                  
        ]);    
    ?> 

    <div class="form-group"> 
        <?php

            $disableForAdmin = false; 
            if($model['id_rol']==1) $disableForAdmin = true;
            if($model['id_privilegio'] != null || !empty($model['id_privilegio'])){
                $arrayPrivilegios = explode(",",$model['id_privilegio']);                
                $model['id_privilegio'] = $arrayPrivilegios;              
                
            }
            $privilegio = ArrayHelper::map(Privilegio::find()->all(),'id_privilegio','nombre_privilegio');
            echo $form->field($model,'id_privilegio')
            ->checkBoxList($privilegio,['separator' => '<br>', 'itemOptions' => [

                'class' => 'privilegio',
                'disabled' => $disableForAdmin
              
               ]]);
        ?> 
    </div>

    <div class="form-group">

        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>

function onchangePrivilegio(Opcion) {
   
   $.ajax({
   dataType:"html",
   type: "post",
   success: function(data){
     //Ocultar la entidad al ser Reconocimiento de personeria
    if(Opcion == 2) $('.privilegio').prop('disabled', false);     
    else $('.privilegio').prop('disabled', true);     
   }
  });
}
</script>
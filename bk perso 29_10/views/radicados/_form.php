<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TipoTramite;
use app\models\TipoResolucion;
use app\models\Radicados;
use app\models\Dignatarios;
use app\models\TipoCertificado;
use app\models\User;
use app\models\Entidades;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use kartik\file\FileInput;
use app\models\MotivoCertificado;
use app\models\TipoResolucionCombinada;
use app\models\TipoReformaEstatutaria;
use app\models\TipoRegistroLibro;
use app\models\TipoEntidadcg;
use app\models\Entidadcamaracomercio;


/* @var $this yii\web\View */
/* @var $model app\models\Radicados */
/* @var $form yii\widgets\ActiveForm */

// 'disabled' => true
?>

<?php if ($msg !== null){  ?>
<div class="row">
  <div class="box box-primary box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Información</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <?php print $msg; ?>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>
<?php } ?>
<?php 
$form = ActiveForm::begin();
$DisabledForRol = false;
$DisabledForRol2 = false;
$id_rol = Yii::$app->user->identity->id_rol;
if(($id_rol == 4 || $id_rol == 3 || $id_rol == 2) && $update){
  $DisabledForRol = true;
}
if(($id_rol == 3 || $id_rol == 2) && $update){
  $DisabledForRol2 = true;
}
?>

<div class="radicados-form">
  <div class="row">
    <div class="col-lg-7">
      <div class="box box-primary">
        <div class="col-lg-8">

          <?php            
              $tramite = TipoTramite::find()->asArray()->all();
              $tipoList=ArrayHelper::map($tramite,'id_tipo_tramite','descripcion');
              echo $form->field($model, 'id_tipo_tramite')->dropDownList($tipoList,['prompt'=>'Seleccione el tipo de tramite','disabled'=> $DisabledForRol,
                'onchange' => 'onchangeTipoTramite($(this).val())'
              ]);
            
            ?>
        </div>

      <?php Pjax::begin(); ?>

      <div class="col-lg-8" id = "GroupCheckCertificado" hidden = "true"> 
      <?php
      
           $var_2= ArrayHelper::map(TipoCertificado::find()->all(),'id_tipo_certificado','nombre_tipo_certificado');
           echo $form->field($model, 'id_tipo_certificado')->dropDownList($var_2, ['prompt'=>'Seleccione el tipo de tramite',
           'onchange' => 'onchangeCertificado($(this).val())',
           'disabled' => $DisabledForRol       
          ]); ?>   

      </div>
      <div class="col-lg-8" id = 'GrupCheckEntidadcg' hidden = "true">
      <?php
          $entidadcg = TipoEntidadcg::find()->asArray()->all();         
          $lista=ArrayHelper::map($entidadcg,'id_entidadcg','nombre');
          echo $form->field($model, 'id_entidadcg')->dropDownList($lista,['prompt'=>'Seleccione el tipo de tramite',
          'onchange' => 'onchangeCamara($(this).val())'
          ]);
      ?>
      </div>

      <div class="col-lg-8" id = "GroupCheckResolucion" hidden = "true"> 
      <?php     
           
           $var = ArrayHelper::map(TipoResolucion::find()->all(),'id_tipo_resolucion','nombre_tipo_resolucion');
           echo $form->field($model, 'id_tipo_resolucion')->dropDownList($var, ['prompt'=>'Seleccione el tipo de tramite',

           'onchange' => 'onchangeResolucion($(this).val())',           
           'disabled' => $DisabledForRol          
                     
           ]);      
           ?> 
      
      </div>

      <div id = "GroupCheckCombinada" class = "col-lg-5" hidden = "true">
      <?php 

      $var_1 = ArrayHelper::map(TipoResolucionCombinada::find()->all(),'id_tipo_resolucion_combinada','nombre_tipo_resolucion_combinada');
           echo $form->field($model, 'id_tipo_resolucion_combinada')->checkBoxList($var_1,['separator' => '<br>',
           'disabled' => $DisabledForRol 
         ]); 
         ?> 
        
      </div>

      <div id = "GroupCheckReformaEstatutos" class = "col-lg-5" hidden = "true">
      <?php 

      $var_2 = ArrayHelper::map(TipoReformaEstatutaria::find()->all(),'id_tipo_reforma_estatutaria','nombre_tipo_reforma_estatutaria');
           echo $form->field($model, 'id_tipo_reforma_estatutaria')->checkBoxList($var_2,['separator' => '<br>',
           'disabled' => $DisabledForRol ]); 
         ?> 
        
      </div>

      <div id = "GroupCheckRegistroLibro" class = "col-lg-5" hidden = "true">
      <?php 

      $var_3 = ArrayHelper::map(TipoRegistroLibro::find()->all(),'id_tipo_registro_libro','nombre_tipo_registro_libro');
           echo $form->field($model, 'id_tipo_registro_libro')->checkBoxList($var_3,['separator' => '<br>',
           'disabled' => $DisabledForRol ]); 
         ?> 
        
      </div>

      <div id="CrearEntidad" class="col-lg-8" >

        <?php 
            echo $form->field($model, 'id_entidad')->widget(Select2::classname(), [
            'disabled' => $DisabledForRol,
            'data' => ArrayHelper::map($entidades,'id_entidad','nombre_entidad'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'language' => 'es',
            'options' => [
              'placeholder' => 'Seleccione una Entidad', 
              'onchange' => '$.post("index.php?r=radicados/lists&id='.'"+$(this).val(), function(data){
              $("select#tiporesolucioncheckboxlist-id_dignatario_tramite").html(data);});'
              ],
                   
            'pluginOptions' => [
            
                'allowClear' => true,
                'minimumInputLength' => 4,
                
            ]
            ]);
        
        ?>
        
      </div>
      <div id="EntidadCamara" class="col-lg-8" hidden = "true" >

<?php 
    $entidadcamara = ArrayHelper::map(Entidadcamaracomercio::find()->all(),'id_entidad_camara','nombre_entidad_camara');
    echo $form->field($model, 'id_entidad_camara')->widget(Select2::classname(), [
    'disabled' => $DisabledForRol,
    'data' => $entidadcamara,
    'theme' => Select2::THEME_BOOTSTRAP,
    'language' => 'es',
    'options' => [
      'placeholder' => 'Seleccione una Entidad de la Cámara de Comercio'
      ],
           
    'pluginOptions' => [
    
        'allowClear' => true,
        'minimumInputLength' => 4,
        
    ]
    ]);

?>

</div>

      <div class="col-lg-8" id="dignatario_tramite_id" hidden = "true">
        <?php
          $dignatarios = Dignatarios::find()->where(['and', ['id_entidad'=>0],['estado' => 1] ])->all(); 
          $lista_dignatarios = ArrayHelper::map($dignatarios, 'id_dignatario','nombre_dignatario');
          echo $form->field($model,'id_dignatario_tramite')->dropDownList($lista_dignatarios,
            [
              'prompt'=>'Seleccione dignatario',
              'disabled' => $DisabledForRol 
          ]);
        ?>
      </div>

      <div class = "col-lg-8">
       <?php       
          echo $form->field($model, 'n_radicado_interno')->textInput();
        ?>
      </div>
      
      <?php Pjax::end(); ?>
      <div class="col-lg-8">
        <?php        
            echo $form->field($model, 'descripcion')->textarea(['rows' => 3]);
        ?>
      </div>

      <div class="col-lg-8">
        <?php  
        // FINALIZADO -> 3, VENCIDO -> 5
         
        $isDisabledForCreate = false;
        if(!$update){
          $model->estado = 1;
          $isDisabledForCreate = true;
        }
          echo $form->field($model, "estado")->dropDownList([1 =>"Reparto",2 =>"Tramite",4=>"Devolución"],["prompt"=>"Seleccione el estado",
          'disabled' => $isDisabledForCreate]);
              
          if( ($model->id_tipo_tramite == 4 || $model->id_tipo_tramite == 15 || $model->id_tipo_tramite == 17 || $model->id_tipo_tramite == 18|| $model->id_tipo_tramite == 19) &&
          (Yii::$app->user->identity->id_rol == 2 || Yii::$app->user->identity->id_rol == 1 || Yii::$app->user->identity->id_rol == 4 )){
              $motivos = MotivoCertificado::find()->asArray()->all();
              $motivoslist=ArrayHelper::map($motivos,'id_motivo','nombre_motivo');
              echo $form->field($model, "id_motivo")->dropDownList($motivoslist,["prompt"=>"Seleccione el estado"
              
              ]);
  
            }

        ?>
    </div>
   
    <div class="col-lg-8">
        <?php         
          echo $form->field($model,'sade')->textInput();          
         ?>
      </div>
  
    <div class="col-lg-8">
      <?php        
        $user = User::find()->where(['id_rol' => 2])->asArray()->all();
        $tipoList=ArrayHelper::map($user,'id','nombre_funcionario');        
        echo $form->field($model, 'id_usuario_tramita')->dropDownList($tipoList,['prompt'=>'Seleccione el usuario']); 
        //echo $form->field($model, 'id_usuario_tramita')->dropDownList($tipoList,['prompt'=>'Seleccione el usuario',
        //'disabled' => $DisabledForRol2 ]);               
      ?>
      <?= $form->field($model, 'file[]')->widget(FileInput::classname(), [
      'options'=>['multiple'=>true],
      'pluginOptions'=>[
      'allowedFileExtensions'=>['doc','pdf','docx'],
      'showUpload' => false,
      ]
      ]); ?>
    </div>
  
    <div class="col-lg-8">
      <div class="form-group">
          <center><?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data' => [
                        'confirm' => '¿Usted esta seguro que desea realizar este proceso?',
                        'method' => 'post'],]) ?></center>
      </div>
      </div>
    </div>

    <?php ActiveForm::end();  ?>
    </div> 
</div>

<script>

		     function onchangeTipoTramite(Opcion) {

		        $.ajax({
		        dataType:"html",
		        type: "post",
		        success: function(data){
		          if(Opcion == 1){
                $('#GroupCheckResolucion').hide(true);
		            $('#GroupCheckCertificado').show(true);
                $('#dignatario_tramite_id').hide(true);  
		          }else if (Opcion == 2){ 
                $('#GroupCheckResolucion').show(true);
		            $('#GroupCheckCertificado').hide(true);
                $('#dignatario_tramite_id').hide(true);  
		          }
		        }
		    });
		    }

		</script>

<script>
function onchangeResolucion(Opcion) {


   $.ajax({
   dataType:"html",
   type: "post",
   success: function(data){
     //Ocultar la entidad al ser Reconocimiento de personeria
    if(Opcion == 1) $('#CrearEntidad').hide(true);
    else $('#CrearEntidad').show(true);
     //Combinación de radicados resolucion
    if(Opcion == 9) {
      $('#GroupCheckCombinada').show(true);
      $('#GroupCheckReformaEstatutos').show(true);
      $('#GroupCheckRegistroLibro').show(true);
      }
    else {
      $('#GroupCheckCombinada').hide(true);
      $('#GroupCheckReformaEstatutos').hide(true);
      $('#GroupCheckRegistroLibro').hide(true);

    }

    if(Opcion == 8) $('#dignatario_tramite_id').show(true);  
    else $('#dignatario_tramite_id').hide(true); 
		                      
   }
  });
}
</script>

<script>
function onchangeCertificado(Opcion) {


   $.ajax({
   dataType:"html",
   type: "post",
   success: function(data){
     //Ocultar la entidad al ser Reconocimiento de personeria
    if(Opcion == 3) $('#dignatario_tramite_id').show(true);    
    else $('#dignatario_tramite_id').hide(true);
    if(Opcion == 5) $('#GrupCheckEntidadcg').show(true);    
    else $('#GrupCheckEntidadcg').hide(true);        
   }
  });
}
</script>

<script>
function onchangeCamara(Opcion) {

   $.ajax({
   dataType:"html",
   type: "post",
   success: function(data){
     //Ocultar la entidad al ser Reconocimiento de personeria
     if(Opcion == 1) $('#CrearEntidad').show(true);
    else $('#CrearEntidad').hide(true);
    if(Opcion == 2) $('#EntidadCamara').show(true) ;    
    else $('#EntidadCamara').hide(true);
    if(Opcion == 3) $('#CrearEntidad').hide(true);  
   }
  });
}
</script>




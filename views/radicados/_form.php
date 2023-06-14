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
use dosamigos\datepicker\DatePicker;

?>

<?php if ($msg !== null){  ?>
<div class="row">
  <div class="box box-primary box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Información</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>

    </div>

    <div class="box-body">
      <?php print $msg; ?>
    </div>

  </div>

</div>
<?php } ?>




<?php 

$form = ActiveForm::begin();

?>

<div class="radicados-form">
  <div class="row">
    <div class="col-lg-7">
      <div class="box box-primary">
        <div class="col-lg-8">

          <?php
              $disableForUpdate = false;    
              if($update && !User::IsAdministrador()) $disableForUpdate = true;        
              $tramite = TipoTramite::find()->asArray()->all();
              $tipoList=ArrayHelper::map($tramite,'id_tipo_tramite','descripcion');
              echo $form->field($model, 'id_tipo_tramite')->dropDownList($tipoList,['prompt'=>'Seleccione el tipo de acto administrativo',
                'onchange' => 'onchangeTipoTramite($(this).val())',
                'disabled' => $disableForUpdate               
              ]);
            
            ?>
        </div>

      <?php Pjax::begin(); ?>

        <div class="col-lg-8" id = "GroupCheckCertificado" hidden = "true"> 
          <?php          
              $var_2= ArrayHelper::map(TipoCertificado::find()->all(),'id_tipo_certificado','nombre_tipo_certificado');
              echo $form->field($model, 'id_tipo_certificado')->dropDownList($var_2, ['prompt'=>'Seleccione el tipo de tramite certificado',
              'onchange' => 'onchangeCertificado($(this).val())'                  
              ]); 
            ?>   

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
           echo $form->field($model, 'id_tipo_resolucion')->dropDownList($var, ['prompt'=>'Seleccione el tipo de tramite resolucion',
           'onchange' => 'onchangeResolucion($(this).val())'
           ]); 
           
           ?> 
      
      </div>

      <div id = "GroupCheckCombinada" class = "col-lg-5" hidden = "true">
        <?php 
          $var_1 = ArrayHelper::map(TipoResolucionCombinada::find()->all(),'id_tipo_resolucion_combinada','nombre_tipo_resolucion_combinada');
          echo $form->field($model, 'id_tipo_resolucion_combinada')->checkBoxList($var_1,['separator' => '<br>']); 
        ?>         
      </div>

      <div id = "GroupCheckReformaEstatutos" class = "col-lg-5" hidden = "true">
        <?php
          $var_2 = ArrayHelper::map(TipoReformaEstatutaria::find()->all(),'id_tipo_reforma_estatutaria','nombre_tipo_reforma_estatutaria');
          echo $form->field($model, 'id_tipo_reforma_estatutaria')->checkBoxList($var_2,['separator' => '<br>']); 
        ?>         
      </div>

      <div id = "GroupCheckRegistroLibro" class = "col-lg-5" hidden = "true">
        <?php 
          $var_3 = ArrayHelper::map(TipoRegistroLibro::find()->all(),'id_tipo_registro_libro','nombre_tipo_registro_libro');
          echo $form->field($model, 'id_tipo_registro_libro')->checkBoxList($var_3,['separator' => '<br>' ]); 
        ?>        
      </div>

      <div id="CrearEntidad" class="col-lg-8" >
        <?php 
         $disableForUpdate = false;    
         if($update && !User::IsAdministrador()){
           if($model['id_tipo_tramite'] == 2 && $model['id_tipo_resolucion'] == 1){
            $disableForUpdate = true;
           }
          
         }       
          echo $form->field($model, 'id_entidad')->widget(Select2::classname(), [
          'data' => ArrayHelper::map($entidades,'id_entidad','nombre_entidad'),
          'theme' => Select2::THEME_BOOTSTRAP,
          'language' => 'es',
          'options' => [
            'disabled' => $disableForUpdate,
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
          echo $form->field($model,'id_dignatario_tramite')->dropDownList($lista_dignatarios,['prompt'=>'Seleccione dignatario']);
        ?>
      </div>

      <div class = "col-lg-8">
       <?php       
          echo $form->field($model, 'n_radicado_interno')->textInput();
        ?>
      </div>
      
      
      <div class="col-lg-8">
        <?php        
            echo $form->field($model, 'descripcion')->textarea(['rows' => 3]);
        ?>
      </div>

      <div class="col-lg-8">
        <?php          
          $isDisabledForCreate = false;
          if(!$update){
            $model->estado = 1;
            $isDisabledForCreate = true;
          }
            echo $form->field($model, "estado")->dropDownList([1 =>"Reparto",2 =>"Tramite",3=>"Finalizado",4=>"Devolución",6=>"Cancelado"],["prompt"=>"Seleccione el estado",
            'onchange' => 'onchangeEstado($(this).val())',
            'disabled' => $isDisabledForCreate]);
        ?>
     </div>

    <?php Pjax::end(); ?>
    <div class="col-lg-8" id = "descripcionCancelado" hidden = "true">
        <?php        
            echo $form->field($model, 'descripcion_cancelado')->textarea(['rows' => 3]);
        ?>
      </div>
   
    <div class="col-lg-8">
        <?php         
          echo $form->field($model,'sade')->textInput();          
         ?>
      </div>
  
    <div class="col-lg-8">
      <?php        
        $arrayUsuarios = User::find()->asArray()->all();
        $arrayTramitadores  = array();
        foreach($arrayUsuarios as $usuario){
          if(in_array(User::PRIVILEGIO_TRAMITADOR,explode(",",$usuario['id_privilegio']))){
            array_push($arrayTramitadores,[$usuario['id']=>$usuario['nombre_funcionario']]);
          }
        }      
        echo $form->field($model, 'id_usuario_tramita')->dropDownList($arrayTramitadores,['prompt'=>'Seleccione el usuario']); 
               
      ?>
        <?= $form->field($model, 'file[]')->widget(FileInput::classname(), [
        'options'=>['multiple'=>true],
        'pluginOptions'=>[
        'allowedFileExtensions'=>['doc','pdf','docx'],
        'showUpload' => false,
        ]
        ]); ?>
      </div>

    <div class="col-lg-6">
      <?php
        echo $form->field($model, 'fecha_gaceta')->widget(
            DatePicker::className(), [
                'inline' => false,                  
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-m-d'
                ]
        ]);
      ?>

    </div>

    <div class="col-lg-6">
        <?php
          echo Html::tag('span', '<h3> <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span></h3>', [
          'title'=>'Formato fechas: año-mes-día
          2019-12-31',
          'data-toggle'=>'tooltip',
          'style'=>'text-decoration: underline; cursor:pointer;'
          ]);
        ?>
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
<script>
    function onchangeEstado(Opcion) {
    
      $.ajax({
      dataType:"html",
      type: "post",
      success: function(data){
        //Ocultar la entidad al ser Reconocimiento de personeria
        if(Opcion == 6) $('#descripcionCancelado').show(true);
        else $('#descripcionCancelado').hide(true);
      }
      });
    }
</script>

<script>

  function showTipoTramite(Opcion){ 
  
    $.ajax({
      dataType:"html",
      type: "post",
      success: function(data){
    
        if(Opcion == 1){
           $('#GroupCheckCertificado').show(true);
        else(Opcion == 2){
          
           $('#GroupCheckResolucion').show(true);
          }
        }
      }
          });
    
  }
</script>

<?php
  /*
  echo"
  <script type='text/javascript'>
  showTipoTramite('".$model['id_tipo_tramite']."');
  </script>";
  */
?>

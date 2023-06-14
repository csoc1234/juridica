<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipomotivosolicTramitepublico;
use app\models\ClaseSolicTramitePublico;
use app\models\TipocertTramitepublico;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TramitePublico */
/* @var $form yii\widgets\ActiveForm */
?>

<br>



<div class="row" style="height: 100%">

	<div class = "col-lg-12 col-lg-offset-4">

		<div class="tramite-publico-form">
		    <div class="row">
		    <div class="col-lg-4">

		    <div class="row">
		    <div class="col-lg-3"><img class="logo-mini" src="img/gobernacion.png" alt="Logo Gobernación" style="width: 100%"/></div>
		    <div class="col-lg-5">
		        <center>
		            <br>
		          DEPARTAMENTO ADMINISTRATIVO DE JURIDICA PERSONERIAS JURIDICAS
		        </center>
		    </div>
		    <div class="col-lg-4">
		        <br>
		        <div class="row">
		            <div class="col-lg-12">Codigo: FO-M4-P2-03</div>
		        </div>
		        <div class="row">
		            <div class="col-lg-12">Versión:1</div>
		        </div>
		        <div class="row">
		            <div class="col-lg-12">Fecha de aprobación:</div>
		        </div>
		        <div class="row">
		            <div class="col-lg-12">29/03/2017</div>
		        </div>

		    </div>
		    </div>

		    <br>

		    <?php $form = ActiveForm::begin(); ?>

		    <?= $form->field($model, 'fecha_tramitePublico')->widget(
		                                        DatePicker::className(), [
		                                        // inline too, not bad
		                                        'inline' => false,
		                                        'language'=> 'es',
		                                         // modify template for custom rendering
		                                        //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
		                                        'clientOptions' => [
		                                        'autoclose' => true,
		                                        'format' => 'yyyy-m-d'
		                                        ]
		                                ]);
		    ?>

		    <?= $form->field($model, 'dirigido_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'DRA IVONNE BEATRIZ CHAVERRA CARDONA']) ?>

		    <?= $form->field($model, 'nombre_solicitante_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite nombre']) ?>

		    <?= $form->field($model, 'cedula_tramitePublico')->textInput(['placeholder' => 'Digite cedula']) ?>

		    <?= $form->field($model, 'direccion_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite dirección']) ?>

		    <?= $form->field($model, 'telefono_tramitePublico')->textInput(['placeholder' => 'Digite telefono']) ?>

		    <?= $form->field($model, 'email_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite email']) ?>

		    <?= $form->field($model, 'nombre_entidad_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite nombre']) ?>

		    <?= $form->field($model, 'direccion_entidad_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite dirección']) ?>

		    <?= $form->field($model, 'telefono_entidad_tramitePublico')->textInput(['placeholder' => 'Digite telefono']) ?>

		    <?= $form->field($model, 'email_entidad_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite email']) ?>

		    <?= $form->field($model, 'nombre_represeLegal_tramitePublico')->textInput(['maxlength' => true, 'placeholder' => 'Digite nombre']) ?>

		    <div class="row">
		        <div class="col-lg-8">
		            <?php
		                $var = ArrayHelper::map(TipomotivosolicTramitepublico::find()->all(),'id_motivo_tramite_publico','nombreMotivo_tramite_publico');

		               echo $form->field($model, 'motivo_solicitud_tramitePublico')->dropDownList($var, ['maxlength' => true, 'prompt' => 'Seleccione el motivo de solicitud', 'onchange' => 'habilitar($(this).val())']) ?>

		        </div>

		        <div id = "CualID" class="col-lg-4" hidden="true">

		            <?= $form->field($model, 'otrosMotivoCert_tramite_publico')->textInput(['maxlength' => true, 'placeholder' => 'Digite otro']) ?>

		        </div>


		    </div>

		    <?php
		        $var = ArrayHelper::map(ClaseSolicTramitePublico::find()->all(),'id_clase_tramite_publico','nombreClase_tramite_publico');
		        echo $form->field($model, 'clase_solicitud_tramitePublico')->dropDownList($var, ['maxlength' => true, 'prompt' => 'Seleccione clase de solicitud']) ?>


		    <div class="row">
		        <div class="col-lg-10">
		            <?php
		                $var = ArrayHelper::map(TipocertTramitepublico::find()->all(),'id_tipocertificado_tramite_publico','nombreCert_tramite_publico');
		                echo $form->field($model, 'tipocertificado_tramite_publico')->dropDownList($var, ['maxlength' => true, 'prompt' => 'Seleccione tipo de certificado']) ?>

		        </div>

		        <div class="col-lg-2">
		            <?= $form->field($model, 'cantidad_tipocert_tramite_publico')->textInput() ?>
		        </div>
		    </div>

		    <div class="form-group">

		       <center><?= Html::submitButton('Imprimir tramite',['class' => 'btn btn-info']) ?></center>
		    </div>

		    <?php ActiveForm::end(); ?>

		</div>
		</div>
		</div>

		<script>

		     function habilitar(Opcion) {

		        $.ajax({
		        //url: "/radicados/prueba",
		        dataType:"html",
		        //data : valor,
		        type: "post",
		        success: function(data){
		          if(Opcion == 5){
		            $('#CualID').show(true);
		          }else{
		            $('#CualID').hide(true);
		          }
		        }
		    });
		    }

		</script>

		</div>

</div>

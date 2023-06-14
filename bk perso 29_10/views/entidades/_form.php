<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use app\models\Entidades;
use app\models\Radicados;
use app\models\Municipios;
use app\models\ClaseEntidad;
use app\models\TipoEntidad;
use yii\grid\GridView;
use app\models\Autocomplete;
use kartik\file\FileInput;
use app\models\Dignatarios;
use app\models\Cargos;
use app\models\GruposCargos;
use kartik\select2\Select2;
use app\models\TipoRegimen;


?>
<?php 
    $session = Yii::$app->session;
    $id_radicado = $session->get('radicado');
    $isSetRadicado = false;
    $id_tipo_radicado = null;
    $var_inscripcionDignatarios = false;
    $var_reformaEstatutos = false;
   

    $var_cambio_razon_social = false;
    $var_cambio_domicilio = false;
    $var_cambio_objeto_social = false;
    
    if($id_radicado != null){
        $radicado = Radicados::findOne($id_radicado);
        $id_tipo_tramite = $radicado['id_tipo_tramite'];
        $isSetRadicado = true;

        if($id_tipo_tramite == 1){
            $id_tipo_radicado = $radicado['id_tipo_certificado'];
            
        }else if($id_tipo_tramite == 2){
            $id_tipo_radicado = $radicado['id_tipo_resolucion'];
            if($id_tipo_radicado == 1 || $id_tipo_radicado == 3){
                $isSetRadicado = false;
            }
            if($id_tipo_radicado == 9){
                $TiposDeResolucion = explode(",",$radicado['id_tipo_resolucion_combinada']);
                $var_inscripcionDignatarios = in_array(1, $TiposDeResolucion);
                $var_reformaEstatutos = in_array(2, $TiposDeResolucion);

                if($var_reformaEstatutos){
                    $TiposDeReforma = explode(",",$radicado['id_tipo_reforma_estatutaria']);
                    $var_cambio_razon_social = in_array(1, $TiposDeReforma);
                    $var_cambio_domicilio = in_array(2, $TiposDeReforma);
                    $var_cambio_objeto_social = in_array(3, $TiposDeReforma);
                }
            }
        }
    }
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

<div class="entidades-form">
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-primary">
                <?php $form = ActiveForm::begin(); //  ?>

                <div class="rows">

                <div class="">
                    <div class="col-lg-12">
                        <div class="col-lg-10">
                           
                            <?php if(Yii::$app->session->hasFlash('ALERTA')): ?>
                                <div class = "alert alert-danger alert-dimissable">
                                    <button aria-hidden = "true" data-dismiss = "alert" class = "close" type = "button">x</button>
                                    <h4><i class = "icon fa fa-check"></i>ALERTA</h4>
                                    <?= Yii::$app->session->getFlash('ALERTA') ?>
                                </div>
                            <?php endif; ?>
                          <?php

                          if($file || $isSetRadicado){
                              echo $form->field($model, 'personeria_n')->textInput(['disabled' => true,'maxlength' => true,'placeholder' =>'Ingrese N° de la personeria (sin año)']);
                          }else{
                              echo $form->field($model, 'personeria_n')->textInput(['maxlength' => true,'placeholder' =>'Ingrese N° de la personeria (sin año)']);
                          }

                          ?>
                            <?php
                            if($var_cambio_razon_social){
                                echo $form->field($model, 'nombre_entidad')->textInput(['maxlength' => true,'placeholder' =>'Ingrese el nombre de la entidad.', 'style' => 'text-transform:uppercase']);
                            }
                            else if($file || $isSetRadicado){
                                echo $form->field($model, 'nombre_entidad')->textInput(['disabled' => true,'maxlength' => true,'placeholder' =>'Ingrese el nombre de la entidad.', 'style' => 'text-transform:uppercase']);	                                
			                }else{
                                echo $form->field($model, 'nombre_entidad')->textInput(['maxlength' => true,'placeholder' =>'Ingrese el nombre de la entidad.', 'style' => 'text-transform:uppercase']);                          
				            }

                            ?>
                            <?php
                                if($file || $isSetRadicado){
                                    $var = ArrayHelper::map(TipoRegimen::find()->all(),'id_tipoRegimen','nombre_tipoRegimen');
                                    echo $form->field($model, 'id_tipoRegimen')->dropDownList($var, ['disabled' => true,'maxlength' => true, 'prompt' => 'Seleccione el tipo de régimen']);
                                }else{
                                    $var = ArrayHelper::map(TipoRegimen::find()->all(),'id_tipoRegimen','nombre_tipoRegimen');
                                    echo $form->field($model, 'id_tipoRegimen')->dropDownList($var, ['maxlength' => true, 'prompt' => 'Seleccione el tipo de régimen']);
                                }
                            ?>
                        </div>
                        <div class="col-lg-12">

                        </div>
                        <div class="col-lg-6">
                            <?php
                             if($file  || $isSetRadicado){
                               echo $form->field($model, 'fecha_reconocimiento')->textInput(['disabled' => true]);
                            }else{
                               echo $form->field($model, 'fecha_reconocimiento')->widget(
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
                            }
                            ?>

                        </div>
                        <div class="col-lg-6">
                            <?php
                            echo Html::tag('span', '<h3> <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span></h3>', [
                                    'title'=>'Formato fechas: año-mes-día
                                    2018-12-31',
                                    'data-toggle'=>'tooltip',
                                    'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]);
                             ?>
                        </div>
                    </div>
                </div>

                </div>

                <div class="rows, col-lg-12">
                    <div class="col-lg-10">
                    <?php
                        $municipioEntidades=Entidades::find()->all();
                        $mun = Municipios::find()->where(['departamento_id' => 76])->asArray()->all();
                        for ($i=0; $i < count($mun) ; $i++) {
                          $mun[$i]['municipio'] = $mun[$i]['municipio'].' - '.Municipios::getNombreDepartamento(76);
                        }
                        $municipioEntidadesList=ArrayHelper::map($mun,'id_municipio','municipio');
                        if($var_cambio_domicilio){
                            echo $form->field($model, 'municipio_entidad')->dropDownList($municipioEntidadesList,['prompt'=>'Seleccione el municipio de  la entidad']);
                        }
                        else if($file || $isSetRadicado){
                            echo $form->field($model, 'municipio_entidad')->dropDownList($municipioEntidadesList,['prompt'=>'Seleccione el municipio de  la entidad','disabled' => true]);
                        }else{
                            echo $form->field($model, 'municipio_entidad')->dropDownList($municipioEntidadesList,['prompt'=>'Seleccione el municipio de  la entidad']);
                        }
                        ?>
                    </div>
                </div>

                <div class="rows">
                    <div class="col-lg-12">

                        <div class="col-lg-10">
                        <?php
                            if($var_cambio_domicilio){
                                echo $form->field($model, 'direccion_entidad')->textInput(['maxlength' => true, 'placeholder'=>'Ingrese la dirección de la entidad.']);
                            }
                            else if($file || $isSetRadicado){
                                echo $form->field($model, 'direccion_entidad')->textInput(['maxlength' => true, 'placeholder'=>'Ingrese la dirección de la entidad.','disabled' => true]);
                            }else{
                                echo $form->field($model, 'direccion_entidad')->textInput(['maxlength' => true, 'placeholder'=>'Ingrese la dirección de la entidad.']);
                            }
                        ?>
                        </div>
                        <div class="col-lg-10">
                            <?php                            
                                echo $form->field($model, 'telefono_entidad')->textInput(['maxlength' => true,'placeholder' =>'Ingrese Telefono de la Entidad']);
                             ?>

                        </div>

                        <div class="col-lg-10">
                            <?php                                
                                echo $form->field($model, 'email_entidad')->textInput(['maxlength' => true,'placeholder' =>'Ingrese Email de la Entidad']);
                            ?>
                        </div>
                    </div>
                </div>

                <div class="rows, col-lg-12">
                    <div class="col-lg-10">
                        <?php
                            $tipoEntidades=Entidades::find()->all();
                            $tipoEn =TipoEntidad::find()->where(['activo' => 1])->asArray()->all();
                            //[1,7,5,8,9,11,19]
                            $tipoEntidadesList=ArrayHelper::map($tipoEn,'id_tipo_entidad','tipo_entidad');
                            
                            if($var_cambio_objeto_social){
                                echo $form->field($model, 'id_tipo_entidad')->dropDownList($tipoEntidadesList,['prompt'=>'Seleccione el tipo de entidad']);
                             
                            }else if($file || $isSetRadicado){
                                echo $form->field($model, 'id_tipo_entidad')->dropDownList($tipoEntidadesList,['prompt'=>'Seleccione el tipo de entidad','disabled' => true]);
                            }else{
                                echo $form->field($model, 'id_tipo_entidad')->dropDownList($tipoEntidadesList,['prompt'=>'Seleccione el tipo de entidad']);
                            }
                            ?>

                        <?php
                            $clasesEntidades=Entidades::find()->all();
                            $clsse =ClaseEntidad::find()->asArray()->all();

                            $clasesEntidadesList=ArrayHelper::map($clsse,'id_clase_entidad','clase_entidad');
                            if($file || $isSetRadicado){
                              echo $form->field($model, 'id_clase_entidad')->dropDownList($clasesEntidadesList,['prompt'=>'Seleccione la clase de entidad','disabled' => true]);
                            }else{
                                echo $form->field($model, 'id_clase_entidad')->dropDownList($clasesEntidadesList,['prompt'=>'Seleccione la clase de entidad']);
                             }


                            ?>
                            </div>

                            <div class="col-lg-6">
                            <?php
                            if($var_reformaEstatutos){
                                echo $form->field($model, 'fecha_estatutos')->widget(
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
                            }
                             if($file || $isSetRadicado){
                               echo $form->field($model, 'fecha_estatutos')->textInput(['disabled' => true]);
                            }else{
                               echo $form->field($model, 'fecha_estatutos')->widget(
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
                            }
                            ?>

                        </div>

                        <div class="col-lg-6">
                            <?php
                            echo Html::tag('span', '<h3> <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span></h3>', [
                                    'title'=>'Formato fechas: año-mes-día
                                    2018-12-31',
                                    'data-toggle'=>'tooltip',
                                    'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]);
                             ?>
                        </div>

                    </div>

                <div class="rows">
                    <div class="col-lg-12">

                        <div class="col-lg-10">
                            <?php
                            if($file || $isSetRadicado){
                                 echo $form->field($model, 'ubicacion_archivos_entidad')->textInput(['maxlength' => true,'disabled' => true]);
                            }else{
                                echo $form->field($model, 'ubicacion_archivos_entidad')->textInput(['maxlength' => true]);
                            }
                             ?>
                        </div>

                        <div class="col-lg-6">

                            <?php
                                echo $form->field($model, 'fecha_gaceta')->widget(
                                    DatePicker::className(), [
                                        // inline too, not bad
                                         'inline' => false,
                                         // modify template for custom rendering
                                        //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                                        'clientOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-m-d'
                                        ]
                                ]);
                            ?>

                        </div>
                        <div class="col-lg-4">
                            <?php
                            echo Html::tag('span', '<h3> <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span></h3>', [
                                    'title'=>'Formato fechas: año-mes-día
                                    2018-12-31',
                                    'data-toggle'=>'tooltip',
                                    'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]);
                             ?>
                        </div>

                        <div class="col-lg-10">
                        <?php
                            if($file || $isSetRadicado){
                             $form->field($model, 'file')->widget(FileInput::classname(), [
                              'pluginOptions'=>[
                              'allowedFileExtensions'=>['doc','pdf','docx'],
                              'showUpload' => false,
                              ],
                              'options' => ['disabled' => true]
                            ]); }
                            else{
                                $form->field($model, 'file')->widget(FileInput::classname(), [
                                 'pluginOptions'=>[
                                 'allowedFileExtensions'=>['doc','pdf','docx'],
                                 'showUpload' => false,
                                 ]
                               ]); }
                        ?>
                        <?php
                        $var = [ 1 => 'Activa', 2 => 'Inactiva',  3 => 'Observación'];
                        $periodo = [ 0=>'∞ INDEFINIDO'];
                        if($file || $isSetRadicado){
                        echo $form->field($model, 'estado_entidad')->dropDownList($var, ['prompt' => 'Seleccione el estado','disabled' => true ]);
                        }else {
                          echo $form->field($model, 'estado_entidad')->dropDownList($var, ['prompt' => 'Seleccione el estado' ]);
                        }                        
                        echo $form->field($model, 'periodo_entidad')->dropDownList($periodo, ['prompt' => 'Seleccione el periodo de la entidad' ]);
                       
                        ?>

                        </div>
                    </div>
                <center><div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Crear') : Yii::t('app', 'Actualizar'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data' => [
                            'confirm' => '¿Usted esta seguro que desea realizar este proceso?',
                            'method' => 'post'],]) ?>

                </div></center>
                <br>

                <?php ActiveForm::end(); ?>
                    </div>
                </div>

                </div>
            </div>

        </div>

    </div>

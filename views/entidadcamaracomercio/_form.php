<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Municipios;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<div class="entidadcamaracomercio-form">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
                
                <div class="col-lg-10">
                        <h2>Información de la entidad</h2>
                        <div class="col-lg-4"><?= $form->field($model, 'nombre_entidad_camara')->textInput(['maxlength' => true]) ?> </div>
                        <div class="col-lg-2"><?= $form->field($model, 'nit_entidad')->textInput() ?> </div>
                        <div class="col-lg-6"></div>
                </div>
                <div class = "col-lg-10">
                        <div class="col-lg-6">
                                <?php       
                                        $mun = Municipios::find()->asArray()->all();
                                        for ($i=0; $i < count($mun) ; $i++) {
                                        $mun[$i]['municipio'] = $mun[$i]['municipio'].' - '.Municipios::getNombreDepartamento($mun[$i]['departamento_id']);
                                        }

                                        echo $form->field($model, 'id_municipio')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map($mun,'id_municipio','municipio'),
                                        'language' => 'es',
                                        'options' => [
                                        'placeholder' => 'Seleccione un municipio',
                                        ],
                                        'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        ],

                                        ])

                                ?>
                        </div>
                        <div class="col-lg-6"></div>
                </div>
                <div class ="col-lg-10">
                        <div class="col-lg-6"><?= $form->field($model, 'direccion_entidad')->textInput(['maxlength' => true]) ?> </div>
                        <div class="col-lg-6"></div>
                </div>
                
                <div class="col-lg-10">
                        <h2>Información de la Cámara de Comercio</h2>   
                        <div class="col-lg-6">
                                <?php       
                                        $mun = Municipios::find()->asArray()->all();
                                        for ($i=0; $i < count($mun) ; $i++) {
                                        $mun[$i]['municipio'] = $mun[$i]['municipio'].' - '.Municipios::getNombreDepartamento($mun[$i]['departamento_id']);
                                        }

                                        echo $form->field($model, 'id_municipio_camara')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map($mun,'id_municipio','municipio'),
                                        'language' => 'es',
                                        'options' => [
                                        'placeholder' => 'Seleccione un municipio',
                                        ],
                                        'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        ],

                                        ])

                                ?>
                        </div>
        
                        <div class="col-lg-2"></div>
                        <div class="col-lg-4"></div>
                </div>
                
                <div class="col-lg-10">
                        <h2>Información del representante</h2>
                        <div class="col-lg-4"><?= $form->field($model, 'nombre_representante')->textInput(['maxlength' => true]) ?></div>
                        <div class="col-lg-2"><?= $form->field($model, 'cedula_representante')->textInput() ?></div>
                        <div class="col-lg-6"></div>
                </div>
                <div class = "col-lg-10">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-2">
                                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success','data' => [
                                                'confirm' => '¿Usted esta seguro que desea realizar este proceso?',
                                                'method' => 'post']]) ?>
                        </div>
                        <div class="col-lg-2"></div>
                </div>
                
        <?php ActiveForm::end(); ?>
        </div>
        
</div>

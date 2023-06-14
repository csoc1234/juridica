<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Municipios;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<div class="entidadcamaracomercio-form">
<?php $form = ActiveForm::begin(); ?>

<div class="container">
<h1>Información de la entidad</h1>
<div class="row">
        <div class="col-lg-4"><?= $form->field($model, 'nombre_entidad_camara')->textInput(['maxlength' => true]) ?> </div>
        <div class="col-lg-2"><?= $form->field($model, 'nit_entidad')->textInput() ?> </div>
        </div>
<div class = "row">
        <div class="col-lg-6">
        <?php       $mun = Municipios::find()->asArray()->all();
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
        </div>
        <div class ="row">
        <div class="col-lg-6"><?= $form->field($model, 'direccion_entidad')->textInput(['maxlength' => true]) ?> </div>
</div>
<h1>Información de la Cámara de Comercio</h1>
<div class="row">
<div class="col-lg-6">
        <?php       $mun = Municipios::find()->asArray()->all();
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
       <!-- <div class="col-lg-6"> $form->field($model, 'id_municipio_camara')->textInput() ?></div>
        <div class="col-lg-6"><$form->field($model, 'id_depertamento_camara')->textInput() ?></div> -->
        <div class="col-lg-2"></div>
</div>
<h1>Información del representante</h1>
<div class="row">
        <div class="col-lg-4"><?= $form->field($model, 'nombre_representante')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-2"><?= $form->field($model, 'cedula_representante')->textInput() ?></div>
</div>
<div class = "row">
<div class="col-lg-5"></div>
<div class="form-group">
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-lg','data' => [
                        'confirm' => '¿Usted esta seguro que desea realizar este proceso?',
                        'method' => 'post']]) ?>
</div>
</div>
    <?php ActiveForm::end(); ?>
</div>
</div>

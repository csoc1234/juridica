<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\Validacion; 
use kartik\select2\Select2;
use kartik\file\FileInput;
use dosamigos\datepicker\DatePicker;


?>

<div class="validacion-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); 
    ?>
    <?= $form->field($model, 'id_radicado')->textInput(['disabled' => true]) ?>
    <?php
    
    if($model->IDT_tramite==2){
    ?>
    <div class="row">
    <div class="col-sm-4">
    <?= $form->field($model, 'numero_resolucion')->textInput() ?> 
    </div>

    <div class="col-sm-4">
    <?= $form->field($model, 'fecha_resolucion')->widget(
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
    </div>
    </div>
    <?php } ?>
    <?= $form->field($model, 'archivo')->widget(FileInput::classname(), [
           'pluginOptions'=>['allowedFileExtensions'=>['pdf'],
           'showUpload' => false,],
    ]);?>
 
    <br>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success','data' => [
                        'confirm' => 'Recuerde que una vez guardado no es posible modificar los datos sin autorizacion previa',
                        'method' => 'post']]) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

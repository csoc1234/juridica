<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use app\models\Entidades;
use app\models\Cargos;
use app\models\GruposCargos;
use app\models\Municipios;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Dignatarios;
/* @var $this yii\web\View */
/* @var $model app\models\Dignatarios */
/* @var $form yii\widgets\ActiveForm */
$session = Yii::$app->session;
$id = $session->get('id_entidad');
$representante = Dignatarios::find()->where(['and',['id_entidad' => $id],['id_cargo'=> 1]])->one();
$isAdhocPre = $session->get('ADHOC');
$isAdhocSec = $session->get('ADHOCSEC');
$model->id_entidad = $id;
$form = ActiveForm::begin();
?>

<div class="dignatarios-form">
    <div class="row">
        <?php
            $ent= Entidades::findOne($model->id_entidad);
            $model->estado = 1;
            $isnotConfig= false;
            if(empty($representante)){
                $model->id_cargo = 1;
                $isnotConfig = true;
                $session = Yii::$app->session;
                $session->set('repre',true);
                ?>
                <div class="col-lg-8">
                <h3>Representante Legal</h3>
                </div> 
                <?php           
            }
        ?>
        <?php
            if($isAdhocPre == true){
                $model->id_cargo = 119;
                $isnotConfig = true;
                $session = Yii::$app->session;
                $session->set('ADHOC',false);
                $session->set('ADHOCSEC',true);
                $session->set('Presidente_Adhoc',true);
                ?>
                <div class="col-lg-8">
                <h3>Presidente ADHOC</h3>
                </div> 
                <?php           
            }
        ?>
        <?php
            if($isAdhocSec == true){
                $model->id_cargo = 1064;
                $isnotConfig = true;
                $session = Yii::$app->session;
                $session->set('ADHOC',false);
                $session->set('ADHOCSEC',false);
                $session->set('Secretario_Adhoc',true);
                ?>
                <div class="col-lg-8">
                <h3>Secretario ADHOC</h3>
                </div>
                <?php           
            }
        ?>
  
        <div class="col-lg-8">
            <?php if(Yii::$app->session->hasFlash('ALERTA')): ?>
                <div class = "alert alert-info alert-dimissable">
                    <button aria-hidden = "true" data-dismiss = "alert" class = "close" type = "button">x</button>
                    <h4><i class = "icon fa fa-check"></i>Aviso</h4>
                    <?= Yii::$app->session->getFlash('ALERTA') ?>
                </div>
            <?php endif; ?>
        </div>

            <div class="col-lg-8">
                <div class="col-lg-8">
                    <?= $form->field($model, 'cedula_dignatario')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-8">
                    <?= $form->field($model, 'nombre_dignatario')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-8">
                    <?php
                    $var = [ 1 => 'Activo', 0 => 'Inactivo'];
                    if($update){
                    echo $form->field($model, 'estado')->dropDownList($var, ['prompt' => 'Seleccione el estado' ]);
                    }else {
                    echo $form->field($model, 'estado')->dropDownList($var, ['prompt' => 'Seleccione el estado','disabled' => $isnotConfig ]);
                    }
                    ?>
                </div>
                <div class="col-lg-8">
                    <?php
                    $mun = Municipios::find()->asArray()->all();
                    for ($i=0; $i < count($mun) ; $i++) {
                    $mun[$i]['municipio'] = $mun[$i]['municipio'].' - '.Municipios::getNombreDepartamento($mun[$i]['departamento_id']);
                    }

                    echo $form->field($model, 'id_municipio_expedicion')->widget(Select2::classname(), [
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


                    <?php
                    $car = Cargos::find()->asArray()->all();
 
                    echo $form->field($model, 'id_cargo')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($car,'id_cargo','nombre_cargo'),
                    'language' => 'es',
                    'options' => [
                    'placeholder' => 'Seleccione un cargo',
                    ],
                    'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'disabled' => $isnotConfig,
                    ],

                    ])
                    ?>

                    <?php
                    $grup = GruposCargos::find()->asArray()->all();

                    echo $form->field($model, 'id_grupo_cargos')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($grup,'id_grupo_cargos','nombre_grupo_cargo'),
                    'language' => 'es',
                    'options' => [
                    'placeholder' => 'Seleccione un cargo',
                    ],
                    'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    ],

                    ])  
                    ?>
                </div>
                <div class ="col-lg-8">
                    <?= $form->field($model, 'tarjeta_profesiona')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-8">
                    <?= $form->field($model, 'inicio_periodo')->widget(
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
                    ]);?>
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


                <div class="col-lg-8">
                    <?= $form->field($model, 'fin_periodo')->widget(
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
                    ]);?>
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

                <div class = "row col-lg-10">
                    <?php
                        $disabled1 = false;
                        if($isAdhocPre == true){
                            $disabled1 = true;
                        }
                    ?>
                    <div class="col-lg-4">                 
                        <?= Html::submitButton($model->isNewRecord ? 'Crear Dignatario y Continuar' : 'Actualizar Dignatario', ['name' => 'create', 'value' => '1', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data' => [
                        'confirm' => '¿Usted esta seguro que desea realizar este proceso?',
                        'method' => 'post'],'disabled' => $disabled1]) ?>
                    </div>
                    

                    <div class="col-lg-4">                        
                    </div>                    

                    <div class="col-lg-4">
                    <?php
                        $disabled2 = false;
                        if($isAdhocSec == true){
                            $disabled2 = true;
                        }
                    ?>
                        <?= Html::submitButton('Añadir Dignatario',['name' => 'create', 'value' => '2','class'=> 'btn btn-info','data' => [
                        'confirm' => '¿Usted esta seguro que quiere añadir otro dignatario?',
                        'method' => 'post'],'disabled' => $disabled2]);?>
                    </div>

                </div>
  
            </div>      
    </div>

</div>
<?php
ActiveForm::end();

?>

<script>

    document.getElementById("dignatarios-cedula_dignatario").addEventListener("change", buscar);

    var elemento = document.getElementById("dignatarios-cedula_dignatario");
    var valor = document.getElementById("dignatarios-cedula_dignatario").value;

    function act(valor)
    {
        $("#dignatarios-id_municipio_expedicion").prop('selectedIndex',valor);
    }


    function buscar()
    {
        var valor = document.getElementById("dignatarios-cedula_dignatario").value;


        $.ajax({
            //http://localhost:8080/index.php?r=radicados%2Fview&id=8
            url: "?r=dignatarios%2Fbuscar",
            dataType: 'json',
            data : 
          {
                dignatario:valor,
            },
          type: "post",
          success: function(data){
          
           var respuesta = data.split(",");
           if(respuesta.length > 0){
            $("#dignatarios-nombre_dignatario").val(respuesta[0]);
            act(respuesta[1]);
            $("#dignatarios-fin_periodo").focus();

          }

          },
          error: function (request, status, error) {
        
            console.log("No se pudo realizar la operación error '"+request.responseText+"' Comuniquese con un administrador");
            }
        });
   
     }
</script>

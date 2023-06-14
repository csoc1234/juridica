<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\TipoTramite;
use app\models\User;
use dosamigos\datepicker\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Radicados;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RadicadosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Radicados';
$this->params['breadcrumbs'][] = $this->title;

$array = [
    ['estado' => '1', 'nombre' => 'Reparto'],
    ['estado' => '2', 'nombre' => 'Tramite'],
    ['estado' => '3', 'nombre' => 'Finalizado'],
    ['estado' => '4', 'nombre' => 'Devolución'],
    ['estado' => '5', 'nombre' => 'Vencido'],
    ['estado' => '6', 'nombre' => 'Cancelado'],
    ['estado' => '7', 'nombre' => 'Validado'],

];

$arrayUsuarios = User::find()->orderBy(['nombre_funcionario'=>SORT_ASC])->asArray()->all();
$arrayTramitadores  = array();
foreach($arrayUsuarios as $usuario){
  if(in_array(User::PRIVILEGIO_TRAMITADOR,explode(",",$usuario['id_privilegio']))){
    array_push($arrayTramitadores,[$usuario['id']=>$usuario['nombre_funcionario']]);
  }
}

if(User::IsTramitador() || User::IsRepartidor() || User::IsRadicador() || User::IsAdministrador()) $OptionsForRol = '{view} {update} {historial}';
else $OptionsForRol = '{view} {historial}';

if(!isset($msg)){
  $msg = null;
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

<div class="radicados-index">  
    <div style="position:relative; left: 70%;   ">
        <table style="width: 300px;" border="1">
            <tbody>
                <tr>
                    <td style="width: 150px;" rowspan="7"><center><b>Estado del <br>Trámite</b></center></td>
                    <td style="width: 150px; background:#AED6F1;"><center><b>Reparto</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:#D5DBDB;"><center><b>Tramite</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:#90EE90;"><center><b>Finalizado</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:yellow;"><center><b>Devolución</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:#F08080;"><center><b>Vencido</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:white;"><center><b>Cancelado</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:#FC78FE;"><center><b>Validado</b></center></td>
                </tr>
               
            </tbody>
        </table>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>   
</div>


<?php 
  if(User::IsAdministrador() || User::IsRepartidor() || User::IsRadicador()){
  ?>
  <div class="col-md-7">
    <?= Html::a(Yii::t('app', 'Crear Radicado'), ['create'], ['class' => 'btn btn-info']) ?>  
  </div>
<?php } ?>

<br>
<br>

<?= Html::beginForm(['reportfiles'],'post');?>
<div class="col-md-7">
    <?= Html::submitButton('Generar Reporte PDF',['name' => 'submit' , 'value' => 'PDFSubmit', 'class'=> 'btn btn-danger', ]);?>
    <?= Html::submitButton('Generar Reporte Excel',[ 'name' => 'submit' , 'value' => 'ExcelSubmit' ,'class'=> 'btn btn-success', ]);?>
</div>
    <?= Html::submitButton('Generar Reporte Completo PDF',[ 'name' => 'submit' , 'value' => 'PDFSubmitFull' ,'class'=> 'btn btn-danger', ]);?>
    <?= Html::submitButton('Generar Reporte Completo Excel',[ 'name' => 'submit' , 'value' => 'ExcelSubmitFull' ,'class'=> 'btn btn-success', ]);?>
<br>
<br> 

<?php if(Yii::$app->session->hasFlash('Error')): ?>
    <div class = "alert alert-danger alert-dimissable">
        <button aria-hidden = "true" data-dismiss = "alert" class = "close" type = "button">x</button>
        <h4><i class = "icon fa fa-check"></i>Error</h4>
        <?= Yii::$app->session->getFlash('Error') ?>
    </div>
<?php endif; ?>

<?php Pjax::begin(); ?> 

    <meta http-equiv="refresh" content="60">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($dataProvider, $key, $index, $column) {
                  return array('array_count_values()' => $dataProvider->id_radicado);
                }                
            ],

            //'id_radicado',
            [ 'attribute' =>'id_radicado',
              //'header' => ' ',
              'contentOptions'=>['style'=>'width:10px; text-align:center;']
               ],
            [ 'attribute' =>'n_radicado_interno',
              //'header' => ' ',
              'contentOptions'=>['style'=>'width:10px; text-align:center;']
              ],

              [
                'attribute' => 'rango_fecha',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'header' => 'Rango fecha '.Html::tag('span', '<small>
                            <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span>
                            </small>',
                            [
                                'title'=>'Usted debe seleccionar un rango de fecha:
                                Ej: 2019-01-01 – 2019-12-31',
                                'data-toggle'=>'tooltip',
                                'style'=>'text-decoration: underline; cursor:pointer;'
                            ]),
                'value' => 'fecha_creacion',
                'format'=>'raw',
                'options' => ['style' => 'width: 25%;'],
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'rango_fecha',
                    'useWithAddon'=>false,
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'locale'=>['format'=>'Y-m-d']
                    ],
                ])
            ],
            [   'header' => 'Tipo de Trámite',
                'headerOptions' => ['style' => 'color:#337ab7; width:40px;'],
                'value'=> function($model){
                    return $model->getTipoTramite();
                },
                 'filter'=>
                Html::activeDropDownList($searchModel, 'id_tipo_tramite', ArrayHelper::map(TipoTramite::find()->all(),'id_tipo_tramite','descripcion'),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],
            //'estado',
             [   'attribute'=>'estado',
                'label'=> 'Estado',
                'contentOptions'=>function($model){
                if ($model->estado == 1) {
                    return ['style'=> 'background-color:#AED6F1;width:50px;'];
                }elseif ($model->estado ==2) {
                    return ['style'=> 'background-color:#D5DBDB;width:50px;'];
                }elseif ($model->estado ==3) {
                    return ['style'=> 'background-color:#90EE90;width:50px;'];
                }elseif($model->estado ==4){
                    return ['style'=> 'background-color:yellow;width:50px;'];
                }elseif($model->estado ==5){
                  return ['style'=> 'background-color:red;width:50px;'];
              }elseif($model->estado ==6){
                  return ['style'=> 'background-color:white;width:50px;'];
              }elseif($model->estado ==7){
                return ['style'=> 'background-color:#FC78FE;width:50px;'];
              }
                else{
                return ['style'=> 'background-color:#F08080;width:50px;'];
                }
              },

                'value'=> function($model){

                    switch ($model->estado) {
                      case 1:
                        return 'Reparto';
                        break;
                      case 2:
                        return 'Tramite';
                        break;
                      case 3:
                        return 'Finalizado';
                        break;
                      case 4:
                        return 'Devolución';
                        break;
                      case 5:
                        return 'Vencido';
                        break;
                      case 6:
                        return 'Cancelado';
                        break;
                      case 7:
                        return 'Validado';
                        break;

                    }
                        },
                'filter'=>
                Html::activeDropDownList($searchModel, 'estado', ArrayHelper::map( $array,'estado', 'nombre' ),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],
          
            [   'header' => 'Usuario tramita',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=> function($model){
                    return $model->getUser();
                },
                
                 'filter'=>                 
                  Html::activeDropDownList($searchModel, 'id_usuario_tramita', $arrayTramitadores,
                  [ 'prompt'=>'-- > Seleccione <--',])
            ],
                      

            ['class' => 'yii\grid\ActionColumn',
            'header' => 'Opc',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template'=>$OptionsForRol,
            'buttons' => [
                'historial' => function ($url, $model, $key) {
                  return $model->id_radicado !=  '' ? Html::a(
                  '<span title="Historial" class="fa fa-clock-o"</span>',

                  ['historial', 'id' => $model->id_radicado]):' ';
                },

            ]
            ],
        ],
    ]); ?>
</div>

<?php Pjax::end(); ?>

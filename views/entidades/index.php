<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\TipoEntidad;
use app\models\ClaseEntidad;
use yii\helpers\Url;
use app\models\User;
use kartik\daterange\DateRangePicker;


$this->title = 'Entidades';

$this->params['breadcrumbs'][] = $this->title;
if(!isset($msg)){
  $msg = null;
}

if(User::IsAdministrador() || User::IsTramitador()) $OptionsForRol = '{view} {update} {dignatario} {historial}';
else $OptionsForRol = '{view} {dignatario} {historial}';
?>

<div class="entidades-index">
    <div style="position:absolute; left: 70%;   ">
        <table style="width: 300px;" border="1">
            <tbody>
                <tr>
                    <td style="width: 150px;" rowspan="3"><center><b>Estado de <br>la Entidad</b></center></td>
                    <td style="width: 150px; background:#90EE90;"><center><b>Activa</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:#F08080;"><center><b>Inactiva</b></center></td>
                </tr>
                <tr>
                    <td style="width: 150px; background:#F4FA58;"><center><b>Inspección</b></center></td>
                </tr>
            </tbody>
        </table>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-md-7">
        <?= Html::a('Entidades Cámara de comercio',['entidadcamaracomercio/index'],['class'=> 'btn btn-warning']) ?>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
   <p>
   <br>
     <br> 

  
        <?= Html::beginForm(['reportfiles'],'post'); 
        ?>
        
       <div class="col-md-7">
      
             <?= Html::submitButton('Generar Reporte PDF',['name' => 'submit' , 'value' => 'PDFSubmit', 'class'=> 'btn btn-danger', ]);
         ?>
            <?= Html::submitButton('Generar Reporte Excel',[ 'name' => 'submit' , 'value' => 'ExcelSubmit' ,'class'=> 'btn btn-success', ]);
            ?>
       
       
        </div>
     
        <?= Html::submitButton('Generar Reporte Completo PDF',[ 'name' => 'submit' , 'value' => 'PDFSubmitFull' ,'class'=> 'btn btn-danger', ]);
        ?>

         <?= Html::submitButton('Generar Reporte Completo Excel',[ 'name' => 'submit' , 'value' => 'ExcelSubmitFull' ,'class'=> 'btn btn-success', ]);
        ?>
 
        <br>
        <br> 
        <?php if(Yii::$app->session->hasFlash('Error')): ?>
            <div class = "alert alert-danger alert-dimissable">
                <button aria-hidden = "true" data-dismiss = "alert" class = "close" type = "button">x</button>
                <h4><i class = "icon fa fa-check"></i>Error</h4>
                <?= Yii::$app->session->getFlash('Error') ?>
            </div>
        <?php endif; ?>

<br><br>
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
<?php Pjax::begin(); ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'class' => 'yii\grid\CheckboxColumn',

                'checkboxOptions' => function ($dataProvider, $key, $index, $column) {


                             return array('array_count_values()' => $dataProvider->id_entidad);

                }

                
                
            ],

            'id_entidad',
            'personeria_year',
            'personeria_n',
            'nombre_entidad',

            [
                'attribute' => 'rango_fecha',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'header' => 'Reconocimiento '.Html::tag('span', '<small>
                            <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span>
                            </small>',
                            [
                                'title'=>'Usted debe seleccionar un rango de fecha:
                                Ej: 2018-01-01 – 2018-12-31',
                                'data-toggle'=>'tooltip',
                                'style'=>'text-decoration: underline; cursor:pointer;'
                            ]),
                'value' => 'fecha_reconocimiento',
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

            [   'header' => 'Tipo Entidad',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=> function($model){
                    return $model->NombreTipoEntidad();
                },
                 'filter'=>
                Html::activeDropDownList($searchModel, 'id_tipo_entidad', ArrayHelper::map(TipoEntidad::find()->all(),'id_tipo_entidad','tipo_entidad'),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],
            [   'header' => 'Clase Entidad',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=> function($model){
                    return $model->NombreClaseEntidad();
                },
                 'filter'=>
                Html::activeDropDownList($searchModel, 'id_clase_entidad', ArrayHelper::map(ClaseEntidad::find()->all(),'id_clase_entidad','clase_entidad'),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],


            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Opc',
                'headerOptions' => ['style' => 'color:#337ab7'],

                'contentOptions'=>function($model){
                    if ($model->estado_entidad == 1) {
                        return ['style'=> 'background-color:#90EE90'];
                    }elseif ($model->estado_entidad==2) {
                        return ['style'=> 'background-color:#F08080'];
                    }else{

                        return ['style'=> 'background-color:#F4FA58'];
                    }
                  },
                'template'=>$OptionsForRol,
                'buttons' => [
                    'dignatario' => function ($url, $model, $key) {
                        return $model->id_entidad !=  '' ? Html::a(
                    '<span title="Ver Dignatarios" class="fa fa-users"</span>',

                    ['dignatario', 'id' => $model->id_entidad]):' ';
                 
                    },

                    'historial' => function ($url, $model, $key) {
                        return $model->id_entidad !=  '' ? Html::a(
                    '<span title="Historial Cambios" class="fa fa-clock-o"</span>',

                    ['historial', 'id' => $model->id_entidad]):' ';

                    },

                ]
            ],


        ],
    ]); ?>
</div>
<?php Pjax::end(); ?>

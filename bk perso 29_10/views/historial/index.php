<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\HistorialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historial'.$titulo;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

        <?php
        if( isset(Yii::$app->user->identity->id_rol) && Yii::$app->user->identity->id_rol == User::ROL_SUPERUSER){
                 ?>
       
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
        
            <?php


            
            }
        ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

              [   'class' => 'yii\grid\CheckboxColumn',
                //'header' => Html::checkBox('selection_all', false, [
                //    'class' => 'select-on-check-all',
                //    'label' => 'Check all'
                    
                //]),
                //'headerOptions' => ['style' => 'color:#337ab7']
                'checkboxOptions' => function ($dataProvider, $key, $index, $column) {

                             return array('array_count_values()' => $dataProvider->id_historial);

                }
                
            ],


            //'id_historial',
            'nombre_evento',
            //'id_tabla_modificada',
            //'fecha_modificacion',
            /*[
                'attribute'=> 'fecha_modificacion',
                'value' =>'fecha_modificacion',
                'format'=> 'raw',
                'filter' =>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'fecha_modificacion',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                ]),

            ],
            */
            [
            'attribute' => 'rango_fecha',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'header' => 'Rango fecha '.Html::tag('span', '<small>
                        <span class="fa fa-info-circle" tool-tip-toggle="tooltip-demo"</span>
                        </small>',
                        [
                            'title'=>'Usted debe seleccionar un rango de fecha:
                            Ej: 2018-01-01 – 2018-12-31',
                            'data-toggle'=>'tooltip',
                            'style'=>'text-decoration: underline; cursor:pointer;'
                        ]),
            'value' => 'fecha_modificacion',
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

          //  'nombre_campo_modificado',
            // 'valor_anterior_campo:ntext',
            // 'valor_nuevo_campo:ntext',
            //'id_usuario_modifica',
            [   'header' => 'Usuario',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=> function($model){
                    return $model->user();
                },
                'filter'=>
                Html::activeDropDownList($searchModel, 'id_usuario_modifica', ArrayHelper::map(User::find()->all(),'id','nombre_funcionario'),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],
             'tabla_modificada',

            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view}',],
        ],
    ]); ?>
</div>
<?php Pjax::end(); ?>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\TipoTramite;
use app\models\User;
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

];
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
                    <td style="width: 150px;" rowspan="5"><center><b>Estado del <br>Trámite</b></center></td>
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
            </tbody>
        </table>
    </div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
        <?php
        if( isset(Yii::$app->user->identity->id_rol) && (Yii::$app->user->identity->id_rol == 2 || Yii::$app->user->identity->id_rol == 1 || Yii::$app->user->identity->id_rol == 3)){
       /*   echo "
          <a class='btn btn-primary' href='?r=radicados%2Fcreate'>Crear Radicado</a>
          <br><br>
          ";

        }*/
         ?>

       </div>

     <div class="col-md-7">
        <?= Html::a(Yii::t('app', 'Crear Radicado'), ['create'], ['class' => 'btn btn-info']) ?>
        
     </div>
        <?php
       
    }
        

        if( isset(Yii::$app->user->identity->id_rol) && (Yii::$app->user->identity->id_rol == 3 || Yii::$app->user->identity->id_rol == 1 || Yii::$app->user->identity->id_rol == 2)){

          //Html::a(' Reporte', ['samplepdf','id' => $model->cod_equipo], ['class' => 'fa fa-file-pdf-o btn btn-danger']);
 //onclick='window.open('http://www.foracure.org.au');return false;'
         // <a class='btn btn-danger' href='?r=radicados%2Fsamplepdf' >Generar PDF</a>
         // <a class='btn btn-success' target='_blank' href='?r=radicados%2Freporte'>Generar Reporte</a>

          ?>
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
    
        <?php
               
    }
        ?>
    </p>
    <?php Pjax::begin(); ?>

 

    <meta http-equiv="refresh" content="60">


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
            //'sade',
          /*  [
              'attribute'=> 'sade',
              'contentOptions'=> ['style' => 'width:100px;']
            ], */
            //'descripcion:ntext',
            //'id_tipo_tramite',
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
                    return ['style'=> 'background-color:#yellow;width:50px;'];
                }else{
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

                    }
                        },
                'filter'=>
                Html::activeDropDownList($searchModel, 'estado', ArrayHelper::map( $array,'estado', 'nombre' ),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],
          //  'id_usuario_tramita',
          [   'header' => 'Usuario crea',
              'headerOptions' => ['style' => 'color:#337ab7'],
              'value'=> function($model){
                  return $model->getUserr();
              },
               'filter'=>
              Html::activeDropDownList($searchModel, 'id_usuario_tramita', ArrayHelper::map(User::find()->where(['id_rol'=>3])->all(),'id','nombre_funcionario'),
              [ 'prompt'=>'-- > Seleccione <--',])
          ],
            [   'header' => 'Usuario tramita',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=> function($model){
                    return $model->getUser();
                },
                 'filter'=>
                Html::activeDropDownList($searchModel, 'id_usuario_tramita', ArrayHelper::map(User::find()->where(['id_rol'=>2])->all(),'id','nombre_funcionario'),
                [ 'prompt'=>'-- > Seleccione <--',])
            ],
            // 'sade',
           

            ['class' => 'yii\grid\ActionColumn',
            'header' => 'Opc',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template'=>'{view} {update} {historial}',
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

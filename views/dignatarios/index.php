<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Cargos;
use app\models\Radicados;
use app\models\GruposCargos;
use kartik\select2\Select2;
use app\models\User;

$this->title = 'Dignatarios: '.$titulo;
$this->params['breadcrumbs'][] = $this->title;
if(!isset($msg)){
  $msg = null;
}

if(User::IsTramitador() || User::IsAdministrador()) $OptionsForRol = '{view} {update} {historial}';
else $OptionsForRol = '{view} {historial}';
  
$cargos = Cargos::find()->asArray()->all();
$gcargos = GruposCargos::find()->asArray()->all();
?>
<?php Pjax::begin(); ?>

<div class= "row">
<div class= "col-lg-9"></div>
<div class= "col-lg-3">

      <table style="width: 200px;" border="1">
          <tbody>
              <tr>
                    <tr>
                  <td style="width: 150px;" rowspan="3"><center><b>Estado del <br> Dignatario</b></center></td>
                        <td style="width: 150px; background:#90EE90;"><b><center>Activo</center></b></td>
                    </tr>
              </tr>
              <tr>
                  <td style="width: 150px; background:#F08080;"><center><b>Inactivo</b></center></td>
              </tr>
          </tbody>
      </table>
</div>
</div>


    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
		
	<div class="col-lg-2">
              <a href="?r=dignatarios%2Fentidad" class="btn btn-block btn-social btn-linkedin">
                <i class="fa fa-bank"></i> Ver Entidad
              </a>
  </div>
  <a onClick="window.print()"><i class='btn btn-info'><span>imprimir</span></i></a>
  <br>
  <br>
    </p>
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

  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_dignatario',
            'cedula_dignatario',
            'nombre_dignatario',
            'fecha_ingreso',
            'fin_periodo',
            //  'estado:boolean',
            [
             'header' => 'Cargo',
                 'headerOptions' => ['style' => 'color:#337ab7'],
                 'value'=> function($model){
                     return $model->NombreCargo();
                 },

             'attribute' => 'id_cargo',
             'filter'=> Select2::widget([
               'model' => $searchModel,
               'attribute' => 'id_cargo',
               'data' => ArrayHelper::map($cargos,'id_cargo','nombre_cargo'),
               'language' => 'es',
                   'options' => [
                     'placeholder' => 'Seleccione un cargo',

                     ],
                     'pluginOptions' => [
                         'allowClear' => true,

                     ],
             ]),


           ],
             [
             'header' => 'Grupo Cargos',
                 'headerOptions' => ['style' => 'color:#337ab7'],
                 'value'=> function($model){
                     return $model->NombreGrupoCargo();
                 },

             'attribute' => 'id_grupo_cargos',
             'filter'=> Select2::widget([
               'model' => $searchModel,
               'attribute' => 'id_grupo_cargos',
               'data' => ArrayHelper::map($gcargos,'id_grupo_cargos','nombre_grupo_cargo'),
               'language' => 'es',
                   'options' => [
                     'placeholder' => 'Seleccione un grupo cargo',

                     ],
                     'pluginOptions' => [
                         'allowClear' => true,

                     ],
             ]),


           ],

            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Opc',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'contentOptions'=>function($model){
                if ($model->estado == true) {
                    return ['style'=> 'background-color:#90EE90'];
                }else{

                    return ['style'=> 'background-color:#F08080' ];
                }
              },
                'template'=> $OptionsForRol,
                'buttons' => [
                    'historial' => function ($url, $model, $key) {
                      return $model->id_dignatario !=  '' ? Html::a(
                      '<span title="Historial" class="fa fa-clock-o"</span>',

                      ['historial', 'id' => $model->id_dignatario]):' ';
                    },

                ]

            ],

        ],
    ]); ?>
  
<?php Pjax::end(); ?></div>

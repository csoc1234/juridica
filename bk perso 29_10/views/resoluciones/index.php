<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResolucionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Resoluciones: ".$titulo;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resoluciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        //    'id_resolucion',
            //'ano_resolucion',
          //  'numero_resolucion',
            //'fecha_creacion',
            [
                'attribute'=> 'fecha_creacion',
                'value' =>'fecha_creacion',
                'format'=> 'raw',
                'filter' =>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'fecha_creacion',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                ]),

            ],
//            'id_tipo_resolucion',
             'nombre_entidad:ntext',
            // 'id_entidad',
            // 'id_historial',

            ['class' => 'yii\grid\ActionColumn',
            'header' => 'Doc',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template' => '{view}',
            'buttons' => [
              'view' => function ($url, $model, $key) {
                return $model->id_resolucion !=  '' ? Html::a(
                '<span class="fa fa-file-word-o"</span>',
                ['view', 'id' => $model->id_resolucion]):'';
                },

            ]
            ],
        ],
    ]); ?>
</div>

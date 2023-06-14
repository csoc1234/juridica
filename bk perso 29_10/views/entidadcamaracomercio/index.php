<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Municipios;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Entidadcamaracomercio;
/* @var $this yii\web\View */


$this->title = 'Entidades de la camara de comercio';
$this->params['breadcrumbs'][] = ['label' => 'Entidades de la Camara de comercio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$mun = Municipios::find()->asArray()->all();
for ($i=0; $i < count($mun) ; $i++) {
$mun[$i]['municipio'] = $mun[$i]['municipio'];
}
?>
<div class="entidadcamaracomercio-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nombre_entidad_camara',
            'direccion_entidad',


            [
                'header' => 'Municipio de la entidad',
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'value'=> function($model){
                        return $model->Municipios();
                    },
   
                'attribute' => 'id_municipio',
                'filter'=> Select2::widget([
                  'model' => $searchModel,
                  'attribute' => 'id_municipio',
                  'data' => ArrayHelper::map($mun,'id_municipio','municipio'),
                  'language' => 'es',
                      'options' => [
                        'placeholder' => 'Seleccione la ciudad',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
   
                        ],
                ]),
                    ],
            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view} {update}',
                ],
        ],
    ]); ?>


</div>
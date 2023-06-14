<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Cargos;
use app\models\GruposCargos;
use app\models\Municipios;
use app\models\Entidades;

/* @var $this yii\web\View */
/* @var $model app\models\Dignatarios */

$this->title = $model->nombre_dignatario;
$this->params['breadcrumbs'][] = ['label' => 'Dignatarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-lg-12">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    <?php
        $car = Cargos::findOne($model->id_cargo);
        $gCar = Gruposcargos::findOne($model->id_grupo_cargos);
        $mun = Municipios::findOne($model->id_municipio_expedicion);
        $ent = Entidades::findOne($model->id_entidad);
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_dignatario',
            'cedula_dignatario',
            'nombre_dignatario',
            [   'attribute'=> 'estado',
                'label'=> 'Estado',
                'value'=> function ($model){
                    if ($model->estado == true) {
                            return 'Activo';
                    }else{
                        return 'Inactivo';
                   }
                },
            ],
           // 'estado:boolean',
           // 'id_municipio_expedicion',
           // 'fecha_ingreso',
           [   'attribute'=> 'id_municipio_expedicion',
                'label' => 'Municipio de Expedición',
                'value'=> $mun['municipio'],

            ],
            'fecha_ingreso',
            [   'attribute'=>'id_entidad',
                'label'=> 'Entidad',
                'value'=> $ent['nombre_entidad'],

            ],
            [   'attribute' => 'id_cargo',
                'label' => 'Cargo',
                'value'=> $car['nombre_cargo'],

               ],
            [   'attribute'=> 'id_grupo_cargos',
                'label'=> 'Grupo Cargo',
                'value'=> $gCar['nombre_grupo_cargo'],

            ],
              'tarjeta_profesiona',
              'inicio_periodo',
              'fin_periodo',
            //'id_entidad',
            //'id_cargo',
            //'id_grupo_cargos',
        ],
    ]) ?>

    <div class="col-lg-6"></div>
   
	     <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h4>Consultar</h4>

              <p>Entidad Diganatario</p>
            </div>
            <a href="?r=dignatarios%2Fentidad"><div class="icon">
              <i class="fa fa-bank"></i>
            </div></a>
            <a href="?r=dignatarios%2Fentidad" class="small-box-footer">
              Acceder <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h4>Consultar</h4>

              <p>Diganatarios Registrados</p>
            </div>
            <a href="?r=dignatarios%2Findex"><div class="icon">
              <i class="fa fa-eye"></i>
            </div></a>
            <a href="?r=dignatarios%2Findex" class="small-box-footer">
              Acceder <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>

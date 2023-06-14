<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\fieldConfig;

/* @var $this yii\web\View */
use  app\models\ValidacionSearch;
use  app\models\Validacion;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Validacion de certificados';
$this->params['breadcrumbs'][] = $this->title;
$model = new Validacion();

?>
<div class="validacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
<div class="row">
<div class="col-lg-5">

        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1,
                'class' => 'form-horizontal',
            ],
            'fieldConfig' => [
             'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
             'horizontalCssClasses' => [
             'label' => 'col-sm-2',
             'offset' => 'col-sm-offset-2',
             'wrapper' => 'col-sm-10',
             'error' => '',
             'hint' => '',
         ],
       ],
        ]); ?>
  

        <?= $form->field($searchModel, 'codigo_cons' , ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control transparent']])->input('view',['placeholder' => "Inserta el codgio de consulta"]) ?>
        <?= $form->field($searchModel, 'id_radicado' , ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control transparent']])->input('view',['placeholder' => "Inserta el codgio de consulta"]) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            <button value="Refresh Page" onClick="window.location.href='?r=validacion'" class="btn btn-success">Reiniciar</button>
        </div>

        <?php ActiveForm::end(); ?>

</div>

<div>
<br>
<br>
<?php
if (!empty($searchModel->codigo_cons) || !empty($searchModel->id_radicado) ){ ?>
<div style="text-align:center">
<?=
     ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'showOnEmpty'=>true,
        'itemView' => function ($model, $key, $index, $widget) {
            echo "<a class='btn btn-lg btn-default' style='width:250px;  height:75px;  font-size: 15px; font-weight: 550;' href='?r=validacion%2Fview&id=$model->id_validacion'>Visualizar <br> Radicado</a> &nbsp";
        },
    ])
       ?>
<?php    } ?>
</div>
</div>
    <?php Pjax::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\fieldConfig;
use yii\models\validacion;
use app\models\CertificarSearch;
use yii\data\ActiveDataProvider;

$this->title = 'Certificar';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="validacion-create">
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
  

        <?= $form->field($searchModel, 'id_radicado' , ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control transparent']])->input('view',['placeholder' => "Inserta el N° de Radicado"]) ?>
        </div>
        <div class="col-lg-2">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Reiniciar',['index'], ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>

<div>
<br>
<br>
<?php
if (!empty($searchModel->id_radicado)){ ?>
<?=
     ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'showOnEmpty'=>true,
        'itemView' => function ($model, $key, $index, $widget) {
            echo $this->render('view', ['model' => $model]);
        },
    ])
       ?>
<?php    } ?>

</div>
    <?php Pjax::end(); ?>

</div>



</div>



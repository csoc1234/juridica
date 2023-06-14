<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cargos */
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/web/img/favicon.ico']); 
$this->title = 'Crear Cargo';
$this->params['breadcrumbs'][] = ['label' => 'Cargos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cargos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

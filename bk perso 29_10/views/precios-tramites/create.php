<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PreciosTramites */

$this->title = 'Agregar Precios Tramites';
$this->params['breadcrumbs'][] = ['label' => 'Precios Tramites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="precios-tramites-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

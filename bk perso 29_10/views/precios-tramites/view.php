<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PreciosTramites */

$this->title = $model->id_precio_tramite;
$this->params['breadcrumbs'][] = ['label' => 'Precios Tramites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="precios-tramites-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_precio_tramite], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_precio_tramite',
            'tramite:ntext',
            'precio',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Valores */

$this->title = $model->id_valores;
$this->params['breadcrumbs'][] = ['label' => 'Valores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valores-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_valores], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_valores], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_valores',
            'Descripcion_valor',
            'valor',
        ],
    ]) ?>

</div>

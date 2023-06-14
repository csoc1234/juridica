<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TipoEntidad */

$this->title = $model->tipo_entidad;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Entidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
switch ($model->activo) {
    case 1:
        $model->activo = "Sí";
        break;
    case 2:
        $model->activo = "No";
        break;
    
    default:
        # code...
        break;
}
?>
<div class="tipo-entidad-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_tipo_entidad], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_tipo_entidad',
            'tipo_entidad',
            'codigo_trd:ntext',
            'revision:ntext',
            'gaceta:ntext',
            'activo',
        ],
    ]) ?>

</div>

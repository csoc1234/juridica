<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TramitePublico */

$this->title = $model->id_tramite_publico;
$this->params['breadcrumbs'][] = ['label' => 'Tramite Publicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tramite-publico-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_tramite_publico], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_tramite_publico], [
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
            'id_tramite_publico',
            'fecha_tramitePublico',
            'dirigido_tramitePublico',
            'nombre_solicitante_tramitePublico',
            'cedula_tramitePublico',
            'direccion_tramitePublico',
            'telefono_tramitePublico',
            'email_tramitePublico:email',
            'nombre_entidad_tramitePublico',
            'direccion_entidad_tramitePublico',
            'telefono_entidad_tramitePublico',
            'email_entidad_tramitePublico:email',
            'nombre_represeLegal_tramitePublico',
            'motivo_solicitud_tramitePublico',
            'otrosMotivoCert_tramite_publico',
            'clase_solicitud_tramitePublico',
            'tipocertificado_tramite_publico',
            'cantidad_tipocert_tramite_publico',
        ],
    ]) ?>

</div>

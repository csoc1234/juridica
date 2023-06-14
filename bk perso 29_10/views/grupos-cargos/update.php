<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GruposCargos */

$this->title = 'Update Grupos Cargos: ' . $model->id_grupo_cargos;
$this->params['breadcrumbs'][] = ['label' => 'Grupos Cargos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_grupo_cargos, 'url' => ['view', 'id' => $model->id_grupo_cargos]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grupos-cargos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

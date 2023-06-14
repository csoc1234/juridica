<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GruposCargos */

$this->title = 'Create Grupos Cargos';
$this->params['breadcrumbs'][] = ['label' => 'Grupos Cargos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupos-cargos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

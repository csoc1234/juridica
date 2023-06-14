<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Departamentos */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Departamentos',
]) . $model->id_departamento;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Departamentos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_departamento, 'url' => ['view', 'id' => $model->id_departamento]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="departamentos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

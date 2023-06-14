<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Validacion */

$this->title = 'Subir archivo';
$this->params['breadcrumbs'][] = ['label' => 'Certificar', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Subir archivo';
?>
<div class="validacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

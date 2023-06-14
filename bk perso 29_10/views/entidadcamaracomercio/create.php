<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Entidadcamaracomercio */

$this->title = 'Generar Tramite';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entidadcamaracomercio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

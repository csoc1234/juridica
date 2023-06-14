<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TramitePublico */

$this->title = 'Crear Tramite Publico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tramite-publico-create">

    <center><h1><?= Html::encode($this->title) ?></h1></center>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

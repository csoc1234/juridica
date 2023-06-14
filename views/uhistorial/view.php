<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Uhistorial */

$this->title = "usuario elimando: ".$model->U_nombre_eliminado;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios eliminados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uhistorial-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          'U_id_usuario_modifica',
          'U_nombre_usuario_modifica',
          'U_fecha_modificacion',
          'U_nombre_eliminado',
        ],

    ]) ?>

</div>

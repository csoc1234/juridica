<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Validacion */

$this->title = $model->getTipoTramite();;
$this->params['breadcrumbs'][] = ['label' => 'Validacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="validacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

        <?= Html::a('Subir archivo', ['update', 'id' => $model->id_validacion], ['class' => 'btn btn-primary']) ?>
         
<?php
        if ($model->archivo!=null) {
            echo "<a class='btn btn-success' href='?r=validacion%2Findex&ValidacionSearch[codigo_cons]=$model->codigo_cons'>Validar</a>"; 
    }?>
    </p>
<?php 
if ($model->archivo!=null) {

?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_radicado',
              [
                 'attribute'=>'IDT_tramite',
                    'value'=> function($model){
                        return $model->getTipoTramite();
                    },
               ],
               [
                'attribute' => 'codigo_cons',
               ],
        ],
    ]) ?>

<?php } else { ?>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_radicado',
              [
                 'attribute'=>'IDT_tramite',
                    'value'=> function($model){
                        return $model->getTipoTramite();
                    },
               ],
        ],
    ]) ?>
<?php 
}
?>
</div>

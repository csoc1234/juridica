<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->nombre_funcionario;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!isset($msgreg)){
    $msgreg = null;
  }
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    <?php
         $us= User::findOne($model->id);
         ?>
    </p>

    <?php if ($msgreg != null){  ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times; </button>
                <h4> Información </h4>
                <?php print $msgreg; ?>
                <?php } ?>
            </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            ['label'=>'Cedula Funcionario',
             'value'=>$us['cedula_funcionario']
            ],
            ['label'=>'Nombre Funcionario',
             'value'=>$us['nombre_funcionario']
            ],
            ['label'=>'Cargo Funcionario',
              'value'=>$us['cargo_funcionario']
            ],
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            //'status',
            [   'attribute'=>'status',
                'label'=> 'Estado',
                'value'=> function($model){

                        switch ($model->status) {
                          case 10:
                            return 'ACTIVO';
                            break;
                          case 0:
                            return 'INACTIVO';
                            break;
                        }
                },
            ],
            //'id_rol',
            [
                'attribute'=>'id_rol',
                'label' => 'Rol del usuario',
                'value' => function($model){
                    return $model->getrol();
                }
            ],
            //'created_at',
            //'updated_at',
            [
                'attribute'=>'id_privilegio',
                'label' => 'Privilegios',
                'value' => function($model){
                    return $model->id_privilegio;
                }
            ],
        ],
    ]) ?>

</div>

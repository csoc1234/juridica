<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Registrar Usuario', ['site/registro'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Historial Usuarios',['uhistorial/index'],['class'=> 'btn btn-warning']) ?>
    </p>


    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'auth_key',
            //'password_hash',
            'cedula_funcionario',
            'nombre_funcionario',
            'email:email',
            'cargo_funcionario',
            // 'status',
            // 'id_rol',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view} {update} {delete}',
                ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

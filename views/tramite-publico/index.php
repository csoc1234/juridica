<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TramitePublicoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crear Tramite Publico';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tramite-publico-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear tramite', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>

<?php

use yii\helpers\Html;
use app\models\Radicados;
/* @var $this yii\web\View */
/* @var $model app\models\Entidades */

$this->title = 'Actualizar Entidad: ' . $model->id_entidad;
$this->params['breadcrumbs'][] = ['label' => 'Actualizar Entidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entidad, 'url' => ['view', 'id' => $model->id_entidad]];
$this->params['breadcrumbs'][] = 'Update';
if(!isset($msg)){
  $msg = null;
}
if(!isset($update)){
	$update = true;
}
if(!isset($file)){
	$file = false;
}


?>
<div class="entidades-update">

    <h1><?= Html::encode($this->title)?></h1>
	
    <?=
	
	    	$this->render('_form', [
	        'model' => $model,
	        'update' => $update,
	        'msg'=> $msg,
	        'file' => $file,
	    	])
    	
     ?>
    

</div>

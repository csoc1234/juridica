<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Certificados;
use app\models\Municipios;


/* @var $this yii\web\View */
/* @var $model app\models\Entidadcamaracomercio */

$this->title = $model->id_entidad_camara;
$this->params['breadcrumbs'][] = ['label' => 'Entidadcamaracomercios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$certificados_all  = Certificados::find()->where(['id_entidad_camara' => $model->id_entidad_camara])->all();

$certificados = array();
for($i=0; $i<5; $i++){

  if(end($certificados_all)){
    array_push($certificados, array_pop($certificados_all));
  }
}
?>
<div class="entidadcamaracomercio-view">
<?php
$mun= Municipios::findOne($model->id_municipio);
$mun2= Municipios::findOne($model->id_municipio_camara);
?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_entidad_camara], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nombre_entidad_camara',
            'nit_entidad',
            [   'attribute' => 'id_municipio',
            'label' => 'Municipio entidad',
            'value'=> $mun['municipio'],
           ],
           [   'attribute' => 'id_municipio_camara',
           'label' => 'Municipio de la camara',
           'value'=> $mun2['municipio'],
          ],
            'direccion_entidad',
            'nombre_representante',
            'cedula_representante',

        ],
    ]) ?>

</div>
<?php
    $usuario = null;
    try {
    $usuario = Yii::$app->user->identity->nombre_funcionario;
    
    } catch (Exception $e) {
    
    }
    if($usuario != null){
        echo "<h4> Certificados: </h4> ";
        if (!empty($certificados)){

            if(isset($certificados)){
            for ($i=0; $i <count($certificados) ; $i++) {
                
            echo "
                <div class='col-md-12'>
                    <div class='col-md-10'>                   
                        <a href='?r=resoluciones%2Fview&id=".urlencode($certificados[$i]['id_certificado'])."&tipoRadicado=".urlencode(1)."'>
                            <div class='info-box'>
                                <span class='info-box-icon bg-aqua'><i class='fa fa-download'></i></span>
                                <div class='info-box-content'>
                                    <span class='info-box-number'>Certificado ".$certificados[$i]['ano_certificado']. " - "." fecha expedición ".$certificados[$i]['fecha_creacion']."</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                ";
                
            }
            }
        }
            else{
            echo "<h5> No hay certificados creados para esta entidad </h5> ";
            }
    }
 ?>   
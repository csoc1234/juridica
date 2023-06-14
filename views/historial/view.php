<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Entidades;
use app\models\Dignatarios;
use app\models\Radicados;
use app\models\TipoEntidad;
use app\models\ClaseEntidad;
use app\models\Municipios;
use app\models\GruposCargos;
use app\models\Cargos;
use app\models\TipoTramite;
/* @var $this yii\web\View */
/* @var $model app\models\Historial */
switch ($model->tabla_modificada) {
    case 'ENTIDADES':
      $entidad = Entidades::findOne($model->id_tabla_modificada);
      $this->title = $model->nombre_evento." ".$entidad['nombre_entidad'];
      break;

    case 'RADICADOS':
      $radicado = Radicados::findOne($model->id_tabla_modificada);
      $this->title = $model->nombre_evento." N°".$radicado['id_radicado'];
      break;

    case 'DIGNATARIOS':
      $dignatario = Dignatarios::findOne($model->id_tabla_modificada);
      $this->title = $model->nombre_evento." ".$dignatario['nombre_dignatario'];
      break;
}
$campo1 = $model->valor_anterior_campo;
$campo2 = $model->valor_nuevo_campo;
switch ($model->nombre_campo_modificado) {
  case 'id_tipo_entidad':
    $tipo = TipoEntidad::FindOne($model->valor_anterior_campo);
    $campo1 = $tipo['tipo_entidad'];
    $tipo = TipoEntidad::FindOne($model->valor_nuevo_campo);
    $campo2 = $tipo['tipo_entidad'];
    break;

  case 'id_usuario_tramita':
      $usuario = User::findOne($model->valor_anterior_campo);
      $campo1 = $usuario['nombre_funcionario'];
      $usuario1 = User::findOne($model->valor_nuevo_campo);
      $campo2 = $usuario1['nombre_funcionario'];
      break;

  case 'id_clase_entidad':
    $clase = ClaseEntidad::FindOne($model->valor_anterior_campo);
    $campo1 = $clase['clase_entidad'];
    $clase = ClaseEntidad::FindOne($model->valor_nuevo_campo);
    $campo2 = $clase['clase_entidad'];
    break;

  case 'id_entidad_radicado':
    $ent = Entidades::FindOne($model->valor_anterior_campo);
    $campo1 = $ent['nombre_entidad'];
    $ent = Entidades::FindOne($model->valor_nuevo_campo);
    $campo2 = $ent['nombre_entidad'];
    break;

  case 'municipio_entidad':
    $municipio = Municipios::findOne($model->valor_anterior_campo);
    $campo1 = $municipio['municipio'];
    $municipio = Municipios::findOne($model->valor_nuevo_campo);
    $campo2 = $municipio['municipio'];
    break;

  case 'id_municipio_expedicion':
    $municipio = Municipios::findOne($model->valor_anterior_campo);
    $campo1 = $municipio['municipio'];
    $municipio = Municipios::findOne($model->valor_nuevo_campo);
    $campo2 = $municipio['municipio'];
    break;

  case 'id_cargo':
    $cargo = Cargos::findOne($model->valor_anterior_campo);
    $campo1 = $cargo['nombre_cargo'];
    $cargo = Cargos::findOne($model->valor_nuevo_campo);
    $campo2 = $cargo['nombre_cargo'];
    break;

  case 'id_grupo_cargos':
    $cargo = GruposCargos::findOne($model->valor_anterior_campo);
    $campo1 = $cargo['nombre_grupo_cargo'];
    $cargo = GruposCargos::findOne($model->valor_nuevo_campo);
    $campo2 = $cargo['nombre_grupo_cargo'];
    break;

  case 'id_tipo_tramite':
    $tramite = TipoTramite::findOne($model->valor_anterior_campo);
    $campo1 = $tramite['descripcion'];
    $tramite = TipoTramite::findOne($model->valor_nuevo_campo);
    $campo2 = $tramite['descripcion'];
    break;

  case 'estado':
    switch ($model->valor_anterior_campo) {
      case '1':
        $campo1 = "Reparto";
        break;
      case '2':
        $campo1 = "Tramite";
        break;
      case '3':
        $campo1 = "Finalizado";
        break;
      case '4':
        $campo1 = "Rechazado";
        break;
    }
    switch ($model->valor_nuevo_campo) {
      case '1':
        $campo2 = "Reparto";
        break;
      case '2':
        $campo2 = "Tramite";
        break;
      case '3':
        $campo2 = "Finalizado";
        break;
      case '4':
        $campo2 = "Rechazado";
        break;
    }

    break;
}

//$this->params['breadcrumbs'][] = ['label' => 'Historials', 'url' => ['index3']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

     $user= User::findOne($model->id_usuario_modifica);

?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_historial',
            'nombre_evento',
            //'id_tabla_modificada',
            'fecha_modificacion',
            'nombre_campo_modificado',
            //'valor_anterior_campo:ntext',
            [  'attribute'=> 'valor_anterior_campo',
                'label'=>'Valor anterior',
                'value'=>$campo1,

            ],
            //'valor_nuevo_campo:ntext',
            [  'attribute'=> 'valor_nuevo_campo',
                'label'=>'Valor nuevo',
                'value'=>$campo2,

            ],
            //'id_usuario_modifica',
            [  'attribute'=> 'id_usuario_modifica',
                'label'=>'Usuario modifica',
                'value'=>$user['nombre_funcionario'],

            ],
            'tabla_modificada',
        ],
    ]) ?>

</div>

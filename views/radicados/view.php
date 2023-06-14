<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\TipoTramite;
use app\models\User;
use app\models\Entidades;
/* @var $this yii\web\View */
/* @var $model app\models\Radicados */

$this->title = $model->id_radicado;
$this->params['breadcrumbs'][] = ['label' => 'Radicados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!isset($msg)){
  $msg = null;
}
?>
<div class="radicados-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_radicado], ['class' => 'btn btn-primary']) ?>
        <?php if(( User::IsAdministrador() || User::IsTramitador()) && $model->estado == 2){
          echo "<a class='btn btn-success' href='?r=radicados%2Ftramitar&id=".$model->id_radicado."'>Tramitar</a>";
        } ?>
        <?php if(( User::IsAdministrador() || User::IsTramitador()) && $model->estado == 4){
          echo "<a class='btn btn-warning' href='?r=radicados%2Fdevolucion&id=".$model->id_radicado."'>Devolución</a>";
        } ?>
    </p>
<?php if (isset($mensaje) && $mensaje !== null){  ?>
<div class="row">
      <div class="box box-primary box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Información</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php print $mensaje; ?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      </div>
<?php } ?>
    <?php if ($msg !== null){  ?>
    <div class="row">
          <div class="box box-primary box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Información</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php print $msg; ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </div>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_radicado',
            'n_radicado_interno',
            'descripcion:ntext',
            [
                'attribute'=>'id_tipo_tramite',
		'label'=>'Tipo Trámite',
                'value'=> function($model){
                    return $model->getTipoTramite();
                },
            ],
	    [
		'attribute'=>'id_tipo_tramite',
                'label'=>'Nombre Trámite',
                'value'=> function($model){
                    switch ($model->id_tipo_tramite){
			case 2:
				return "Resolucion: ".$model->getTipoResolucion();
				break;
			case 1:
				return "Certificado: ".$model->getTipoCertificado();
				break;
		    }
                },
            ],


            [   'attribute'=>'estado',
                'label'=> 'Estado del Tamite',
                'value'=> function($model){

                        switch ($model->estado) {
                          case 1:
                            return 'Reparto';
                            break;
                          case 2:
                            return 'Tramite';
                            break;
                          case 3:
                            return 'Finalizado';
                            break;
                          case 4:
                            return 'Devolucion';
                            break;
                          case 5: 
                            return 'Vencido';
                            break;
                          case 6: 
                            return 'Cancelado';
                            break;
                          case 7: 
                            return 'Validado';
                            break;
                        }
                },
            ],
            'descripcion_cancelado:ntext',
            //'estado',
            [
                'attribute'=>'id_motivo',
                'value' => function($model){
                    return $model->getMotivo();
                }
            ],
            [
                'attribute'=>'id_usuario_tramita',
                'value' => function($model){
                    return $model->getUser();
                }
            ],
            [
                'attribute'=>'id_entidad',
                'value' => function($model){
                    return $model->getEntidad();
                }
            ],
            'sade',
            [
                'attribute'=>'id_usuario_creacion',
                'value' => function($model){
                    return $model->getUserr();
                }
            ],
            'fecha_creacion',

        ],
    ]) ?>
    <?php

    echo " <div>";
    $model1 = Entidades::findOne($model->id_entidad);
    if (!file_exists("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado)){
      //mkdir($model->id_entidad);
    }else{
      $archivos = scandir("/var/DocJuridica/".$model->id_entidad.'/Radicado'.$model->id_radicado);
      unset($archivos[0],$archivos[1]);
      $i=0;
      foreach ($archivos as $key => $value) {
        $i=$i+1;
        if($value == "Documento del Radicado $model->id_radicado-$i.pdf"){
          echo
         "<a class='btn btn-lg btn-default fa fa-download ' style='width:250px; height:75px;  font-size: 15px; font-weight: 550; ' href='?r=entidades%2Fdownload&file=$value&radicado=$model->id_radicado'>    Descargar <br>  Documento del radicado $model->id_radicado.pdf</a> &nbsp"
          ;
        }
        if($value == "Documento del Radicado $model->id_radicado-$i.doc"){
          echo
         "<a class='btn btn-lg btn-default fa fa-download ' style='width:250px; height:75px;  font-size: 15px; font-weight: 550; ' href='?r=entidades%2Fdownload&file=$value&radicado=$model->id_radicado'>    Descargar <br>  Documento del radicado $model->id_radicado.doc</a> &nbsp"
          ;
        }
        if($value == "Documento del Radicado $model->id_radicado-$i.docx"){
          echo
         "<a class='btn btn-lg btn-default fa fa-download ' style='width:250px; height:75px;  font-size: 15px; font-weight: 550; ' href='?r=entidades%2Fdownload&file=$value&radicado=$model->id_radicado'>    Descargar <br>  Documento del radicado $model->id_radicado.docx</a> &nbsp"
          ;
        }

      }
    }


    if (!file_exists("/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado)){
    }else{
      $archivos = scandir("/var/DocJuridica/Reconocimientos/Radicado".$model->id_radicado);
      unset($archivos[0],$archivos[1]);
      $i=0;
      foreach ($archivos as $key => $value) {
        $i=$i+1;
        if($value == "Documento del Radicado $model->id_radicado-$i.pdf"){
          echo
         "<a class='btn btn-lg btn-default fa fa-download ' style='width:250px; height:75px;  font-size: 15px; font-weight: 550; ' href='?r=radicados%2Fdownload&file=$value&radicado=$model->id_radicado'>    Descargar <br>  Documento del radicado $model->id_radicado.pdf</a> &nbsp"
          ;
        }
        if($value == "Documento del Radicado $model->id_radicado-$i.doc"){
          echo
         "<a class='btn btn-lg btn-default fa fa-download ' style='width:250px; height:75px;  font-size: 15px; font-weight: 550; ' href='?r=radicados%2Fdownload&file=$value&radicado=$model->id_radicado'>    Descargar <br>  Documento del radicado $model->id_radicado.doc</a> &nbsp"
          ;
        }
        if($value == "Documento del Radicado $model->id_radicado-$i.docx"){
          echo
         "<a class='btn btn-lg btn-default fa fa-download ' style='width:250px; height:75px;  font-size: 15px; font-weight: 550; ' href='?r=radicados%2Fdownload&file=$value&radicado=$model->id_radicado'>    Descargar <br>  Documento del radicado $model->id_radicado.docx</a> &nbsp"
          ;
        }

      }
    }
   ?>

</div>

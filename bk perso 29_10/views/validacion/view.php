<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use Carbon\Carbon;

/* @var $this yii\web\View */
/* @var $model app\models\Validacion */

$this->title = 'Validacion de certificados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">


  <?php

    $fechaactual = Carbon::now('America/Bogota')->toDateTimeString();
      $año =(int)substr($model->fecha_sistem,0,4);
      $mes =(int)substr($model->fecha_sistem,5,2);
      $dia =(int)substr($model->fecha_sistem,8,2);
    if ($mes==13) {
      $mes2=00;
      $año2=$año+1;
    }else {
      $mes2=$mes+1;
      $año2=$año;
    }
  ?>
<div class="container">


<div class="row">
  <div class="col-lg-2"><img class="logo-mini" src="img/gobernacion.png" alt="Logo Gobernación" style="height: 150px; width: 150px;"></div>
  <div class="col-lg-9"><h1 style="text-align: right"><?= Html::encode($this->title) ?></h1></div>
  <div class="col-lg-9"><h4 style="text-align: right">software personería juridíca</h4></div>
  
  
</div>
<br>
<br>
<div class="row">
<div class="col-lg-8">
        <?= DetailView::widget([
            'model' => $model,
            'options' =>['class' => 'table'],
            'attributes' => [

              [   'attribute'=>'estado',
                  'label'=> 'Estado del Tamite',
                  'value'=> function($model){

                          switch ($model->estado) {
                            case 1: //reparto
                              return 'Tramite';
                              break;
                            case 2:
                              return 'Tramite';
                              break;
                            case 3:
                              return 'Finalizado';
                              break;
                            case 4: //rechazado
                              return 'Tramite';
                              break;
                            case 5:
                              return 'Vencido';
                              break;
                          }
                  },
              ],
                [
                    'attribute'=>'IDT_tramite',
                    'value'=> function($model){
                        return $model->getTipoTramite();
                    },
                  ],
                'fecha_sistem',
                'codigo_cons',
            ],
        ]) ?>
</div>


<div class="col-lg-4" >

    <?php

      switch ($model->estado) {
          case 1:
          ?>
          <h2 style="text-align: center"><?php echo "Certificado en Tramite " ?></h2>
          <br>
          <h1 style="text-align: center;font-size:80px;text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;">
          <i class="glyphicon glyphicon-exclamation-sign" style="color:#FFAF03 "></i>
          </h1>
          <h4 style="text-align: center;font-weight:bold;text-decoration-line: underline;">Recuerda revisar el hash</h4>
          <?php
          break;

          case 2:
          ?>
          <h2 style="text-align: center"><?php echo "Certificado en Tramite " ?></h2>
          <br>
          <h1 style="text-align: center;font-size:80px;text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;">
          <i class="glyphicon glyphicon-exclamation-sign" style="color:#FFAF03 "></i>
          </h1>
          <h4 style="text-align: center;font-weight:bold;text-decoration-line: underline;">Recuerda revisar el hash</h4>
          <?php
          break;

          case 3:
          ?>
          <h2 style="text-align: center"><?php echo "certificado valido hasta: ".$año2."-".$mes2."-".$dia." "; ?></h2>
          <h1 style="text-align: center;font-size:80px;text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;">
          <i class="glyphicon glyphicon-exclamation-sign" style="color:#2DFF03;aling:center;"></i>
          </h1>
          <h4 style="text-align: center;font-weight:bold;text-decoration-line: underline;">Recuerda revisar el hash</h4>
          <?php
          break;
          
          case 4:
          ?>
          <h2 style="text-align: center"><?php echo "Certificado en Tramite " ?></h2>
          <br>
          <h1 style="text-align: center;font-size:80px;text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;">
          <i class="glyphicon glyphicon-exclamation-sign" style="color:#2DFF03"></i></h1>
          <h4 style="text-align: center;font-weight:bold;text-decoration-line: underline;">Recuerda revisar el hash</h4>
          <?php
          break;

          case 5:
          ?>
          <h2 style="text-align: center"><?php echo "Certificado Vencido " ?></h2>
          <br>
          <h1 style="text-align: center;font-size:80px;text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;">
          <i class="fa fa-bell-o" style="color:#FF0000"></i></h1>
          <?php
          break;
      }

?>
    </div>
</div>
</div>
<?php
if($model->estado==3 && $model->archivo!=null){
  echo
 "<a class='btn btn-default btn-lg glyphicon glyphicon-download-alt' style='width:250px; height:100px;  font-size: 15px; font-weight: 550;font-size:20px;' href='?r=validacion%2Fdownload&file=$model->archivo'><br>Descargar <br>Certificado</a> &nbsp";
}else {
  echo "<h2>Documento no disponible</h2>";
}
?> 
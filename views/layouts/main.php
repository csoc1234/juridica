<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Nav;
use app\assets\AppAsset;
use app\models\Radicados;
use app\models\Dignatarios;
use app\models\Historial;
use app\models\User;
use yii\widgets\ActiveForm;
use app\models\TipoTramite;
use Carbon\Carbon;



AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
   <link rel="stylesheet" type="text/css" href="css/site.css" screen="print"/>
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/web/img/favicon.ico']); ?>
</head>

<body class="hold-transition skin-black-light sidebar-mini">
  <!--Pag init -->
  <?php $this->beginBody() ?>

  <div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <!--<span class="logo-mini"><b>P.</b>J</span> -->
        <img class="logo-mini" src="img/escudovallenew.png" alt="Logo Gobernación"  />
        <!-- logo for regular state and mobile devices -->
       <!-- <span class="logo-lg"><b>Gobernación</b>DelValle</span> -->
        <img class="logo-lg" src="img/logoGoberNew.png" alt="Logo Gobernación"  />
      </a>
      <!-- Header Navbar: style can be found in header.less -->

      <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->

          <a id='miboton' href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <?php
                try {
                  $idRol = Yii::$app->user->identity->id_rol;
                  if ($idRol != null) {
                    // SuperUser Index Menu Up
                    
                      $session = Yii::$app->session;
                    
                      $radicados_reparto = array();
                      $radicados_tramite = array();                  
                      $radicados_devolucion = array();

                      $radicados_reparto_fecha = array();
                      $radicados_tramite_fecha = array();                  
                      $radicados_devolucion_fecha = array();

                      $radicados_all = Radicados::find()->where(['and',['id_usuario_tramita' => Yii::$app->user->identity->id],['or',['estado'=>1],['estado'=>2],['estado'=>4]]])->asArray()->all();
                      
                      for($i=0; $i<count($radicados_all); $i++){
                        $estado = $radicados_all[$i]['estado'];
                        
                        switch($estado){
                          case 1:
                          array_push($radicados_reparto, $radicados_all[$i]['id_radicado']);
                          array_push($radicados_reparto_fecha, $radicados_all[$i]['fecha_creacion']);
                          
                          break;

                          case 2:
                          array_push($radicados_tramite,$radicados_all[$i]['id_radicado']);
                          array_push($radicados_tramite_fecha, $radicados_all[$i]['fecha_creacion']);
                          break;

                          case 4:
                          array_push($radicados_devolucion,$radicados_all[$i]['id_radicado']);
                          array_push($radicados_devolucion_fecha, $radicados_all[$i]['fecha_creacion']);
                          break;
                        }
                      }

                      $nradicado_reparto = count($radicados_reparto);
                      $nradicado_tramite = count($radicados_tramite);
                      $nradicado_devolucion = count($radicados_devolucion);
                      $nradicados = $nradicado_reparto+$nradicado_tramite+$nradicado_devolucion;

                      $tiempo = Carbon::now('America/Bogota');
                      $dias_vencer = 38;

                      ////////////////////////////////////////////// BORRAR /////////////////////////////////
                    /* $allDignatarios = Dignatarios::find()->where(['representante_legal'=> null])->all();
                      
                      foreach($allDignatarios as $dignatario){

                        if($dignatario['representante_legal'] == null){
                          if($dignatario['id_cargo'] == 1 || $dignatario['id_cargo'] == 9999){
                            $dignatario['representante_legal'] = 1;
                            $dignatario->save(false);

                        }else{
                          $dignatario['representante_legal'] = 0;
                          $dignatario->save(false);
                        }
                        }else continue;
                     
                      }
                      */
                      ////////////////////////////////////////////  BORRAR ////////////////////////////////////////
                      ?>

                      <?php
                        echo "
                        <li class='dropdown tasks-menu'>
                          <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                            <i class='fa fa-bell-o'></i>
                            <span class='label label-info'>$nradicados</span>
                          </a>
                          <ul class='dropdown-menu'>
                        <li class='header'>Usted tiene $nradicado_reparto radicados en reparto</li>"
                      ?>

                        <li>                                          
                          <ul class="menu">
                            <?php                                
                              for($i = 0; $i <$nradicado_reparto; $i++ ){                            
                                $fecha_creacion = new DateTime($radicados_reparto_fecha[$i]);                                
                                $fecha_actual = new DateTime((new Datetime($tiempo->toDateString()))->format('Y-m-d'));
                                $fecha_vencimiento = new DateTime(date('Y-m-d', strtotime($fecha_creacion->format('Y-m-d'). ' + '.$dias_vencer.' days')));
                                $dias_restantes = $fecha_actual->diff($fecha_vencimiento);
                                
                                $radicado = Radicados::findOne($radicados_reparto[$i]);
                                $dias_restantes_int = (int)$dias_restantes->days;
                                
                                if($dias_restantes_int <= 0 && $radicado['estado'] != 5){
                                  $radicado->estado = 5;
                                  $radicado->save(false);
                                  $model = $radicado;
  
                                  $historial = new Historial();
                                  $historial->nombre_evento = "CAMBIO DE ESTADO RADICADO";
                                  $historial->id_tabla_modificada = $model->id_radicado;
                                  $tiempo = Carbon::now('America/Bogota');
                                  $historial->fecha_modificacion = $tiempo->toDateTimeString();
                                  $historial->nombre_campo_modificado = "estado";
                                  $historial->valor_anterior_campo = 1;
                                  $historial->valor_nuevo_campo = $model['estado'];
                                  $historial->id_usuario_modifica = Yii::$app->user->identity->id;
                                  $historial->tabla_modificada = "RADICADOS";
                                  $historial->save(false);
                                  
                                  $destinatario = User::findOne($model->id_usuario_tramita);
                                  if($destinatario != null){
                                    
                                    $TipoTramite = TipoTramite::findOne($model->id_tipo_tramite)['descripcion'];
                                    $estadoRadicado = "Vencido";
                                    
                                    Yii::$app->mailer->compose('radicadomail.php',[
                                      'nradicado' => $model->id_radicado,
                                      'tipoRadicado' => $TipoTramite,
                                      'estadoRadicado' =>$estadoRadicado,
                                      
                                    ])          
                                        ->setTo($destinatario->email) 
                                        ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])          
                                        ->setSubject("Portal Personerias - Vencimiento de radicado")              
                                        ->send();
                                  }

                                  header("Refresh:0");
                                }
 
                                echo"
                                <li>
                                  <a href='?r=radicados%2Fview&id=$radicados_reparto[$i]'>
                                    <h3>
                                      <i class='fa  fa-circle-o-notch text-aqua'></i> Radicado No. <strong> $radicados_reparto[$i] - Quedan $dias_restantes->days dias </strong>
                                        <small class='pull-right'>                                       
                                        </small>
                                    </h3>
                                  </a>
                                </li>";
                                  
                                }
                              ?>
                            </ul>
                        </li>

                        <?php
                          echo "
                          <li class='header'>Usted tiene $nradicado_tramite radicados en trámite</li>"
                        ?>

                        <li>                      
                          <ul class="menu">
                            <?php
                              for($i = 0; $i <$nradicado_tramite; $i++ ){                            
                                
                                $fecha_creacion = new DateTime($radicados_tramite_fecha[$i]);                                
                                $fecha_actual = new DateTime((new Datetime($tiempo->toDateString()))->format('Y-m-d'));
                                $fecha_vencimiento = new DateTime(date('Y-m-d', strtotime($fecha_creacion->format('Y-m-d'). ' + '.$dias_vencer.' days')));
                                $dias_restantes = $fecha_actual->diff($fecha_vencimiento);

                                $radicado = Radicados::findOne($radicados_tramite[$i]);
                                $dias_restantes_int = (int)$dias_restantes->days;
                                
                                if($dias_restantes_int <= 0 && $radicado['estado'] != 5){
                                  $radicado->estado = 5;
                                  $radicado->save(false);
                                  $model = $radicado;
  
                                  $historial = new Historial();
                                  $historial->nombre_evento = "CAMBIO DE ESTADO RADICADO";
                                  $historial->id_tabla_modificada = $model->id_radicado;
                                  $tiempo = Carbon::now('America/Bogota');
                                  $historial->fecha_modificacion = $tiempo->toDateTimeString();
                                  $historial->nombre_campo_modificado = "estado";
                                  $historial->valor_anterior_campo = 2;
                                  $historial->valor_nuevo_campo = $model['estado'];
                                  $historial->id_usuario_modifica = Yii::$app->user->identity->id;
                                  $historial->tabla_modificada = "RADICADOS";
                                  $historial->save(false);
                                  
                                  $destinatario = User::findOne($model->id_usuario_tramita);
                                  if($destinatario != null){
                                    
                                    $TipoTramite = TipoTramite::findOne($model->id_tipo_tramite)['descripcion'];
                                    $estadoRadicado = "Vencido";
                                    
                                    Yii::$app->mailer->compose('radicadomail.php',[
                                      'nradicado' => $model->id_radicado,
                                      'tipoRadicado' => $TipoTramite,
                                      'estadoRadicado' =>$estadoRadicado,
                                      
                                    ])          
                                        ->setTo($destinatario->email) 
                                        ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])          
                                        ->setSubject("Portal Personerias - Vencimiento de radicado")              
                                        ->send();
                                  }

                                  header("Refresh:0");
                                }

                                echo"
                                <li>
                                  <a href='?r=radicados%2Fview&id=$radicados_tramite[$i]'>
                                    <h3>
                                      <i class='fa  fa-circle-o-notch text-aqua'></i> Radicado No. <strong> $radicados_tramite[$i] - Quedan $dias_restantes->days dias </strong>
                                        <small class='pull-right'>                                       
                                        </small>
                                    </h3>
                                  </a>
                                </li>";
                                  
                                }
                              ?>
                            </ul>
                        </li>

                        <?php
                          echo "
                          <li class='header'>Usted tiene $nradicado_devolucion radicados en devolución</li>"
                        ?>

                        <li>                      
                          <ul class="menu">
                            <?php
                              for($i = 0; $i <$nradicado_devolucion; $i++ ){                            
                                
                                $fecha_creacion = new DateTime($radicados_devolucion_fecha[$i]);                                
                                $fecha_actual = new DateTime((new Datetime($tiempo->toDateString()))->format('Y-m-d'));
                                $fecha_vencimiento = new DateTime(date('Y-m-d', strtotime($fecha_creacion->format('Y-m-d'). ' + '.$dias_vencer.' days')));
                                $dias_restantes = $fecha_actual->diff($fecha_vencimiento);

                                $radicado = Radicados::findOne($radicados_devolucion[$i]);
                                $dias_restantes_int = (int)$dias_restantes->days;
                                
                                if($dias_restantes_int <= 0 && $radicado['estado'] != 5){
                                  $radicado->estado = 5;
                                  $radicado->save(false);
                                  $model = $radicado;
  
                                  $historial = new Historial();
                                  $historial->nombre_evento = "CAMBIO DE ESTADO RADICADO";
                                  $historial->id_tabla_modificada = $model->id_radicado;
                                  $tiempo = Carbon::now('America/Bogota');
                                  $historial->fecha_modificacion = $tiempo->toDateTimeString();
                                  $historial->nombre_campo_modificado = "estado";
                                  $historial->valor_anterior_campo = 4;
                                  $historial->valor_nuevo_campo = $model['estado'];
                                  $historial->id_usuario_modifica = Yii::$app->user->identity->id;
                                  $historial->tabla_modificada = "RADICADOS";
                                  $historial->save(false);
                                  
                                  $destinatario = User::findOne($model->id_usuario_tramita);
                                  if($destinatario != null){
                                    
                                    $TipoTramite = TipoTramite::findOne($model->id_tipo_tramite)['descripcion'];
                                    $estadoRadicado = "Vencido";
                                    
                                    Yii::$app->mailer->compose('radicadomail.php',[
                                      'nradicado' => $model->id_radicado,
                                      'tipoRadicado' => $TipoTramite,
                                      'estadoRadicado' =>$estadoRadicado,
                                      
                                    ])          
                                        ->setTo($destinatario->email) 
                                        ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])          
                                        ->setSubject("Portal Personerias - Vencimiento de radicado")              
                                        ->send();
                                  }

                                  header("Refresh:0");
                                }

                                echo"
                                <li><!-- Task item -->
                                  <a href='?r=radicados%2Fview&id=$radicados_devolucion[$i]'>
                                    <h3>
                                      <i class='fa  fa-circle-o-notch text-aqua'></i> Radicado No. <strong> $radicados_devolucion[$i] - Quedan $dias_restantes->days dias </strong>
                                        <small class='pull-right'>                                       
                                        </small>
                                    </h3>
                                  </a>
                                </li>";
                                  
                                }
                              ?>
                            </ul>
                        </li>
                              
                        <li class="footer">
                          <a href="?r=radicados">Ir a Radicados.</a>
                        </li>
                          </ul>
                        </li>
                      
                      <?php
                        $session = Yii::$app->session;
                        $id = $session->get('id');

                        if(isset($id) && $id != 'x'){
                          echo Nav::widget([
                              'options' => ['class' => 'navbar-nav navbar-right'],
                              'items' => [
                                  ['label' => 'Acerca de', 'url' => ['/site/about']],
                                  ['label' => 'Contáctenos', 'url' => ['/site/contact']],
                                  Yii::$app->user->isGuest ? (
                                      ['label' => 'Iniciar sesión', 'url' => ['/site/index']]
                                  ) : (
                                      '<li>'
                                      . Html::beginForm(['/site/logout'], 'post')
                                      . Html::submitButton(
                                          'Cerrar sesión (' . Yii::$app->user->identity->nombre_funcionario . ')',
                                          ['class' => 'btn btn-default logout',
                                          'data' => [
                                              //'confirm' => "se encuentra realizando el radicado N° $id ¿Esta seguro que desea salir?",
                                              'method' => 'post',
                                          ],
                                          ]
                                      )
                                      . Html::endForm()
                                      . '</li>'
                                  ),
                                  ['label'=>'   '],
                              ],
                          ]);
                        }else{
                            echo Nav::widget([
                                'options' => ['class' => 'navbar-nav navbar-right'],
                                'items' => [
                                    ['label' => 'Acerca de', 'url' => ['/site/about']],
                                    ['label' => 'Contáctenos', 'url' => ['/site/contact']],
                                    Yii::$app->user->isGuest ? (
                                        ['label' => 'Iniciar sesión', 'url' => ['/site/index']]
                                    ) : (
                                        '<li>'
                                        . Html::beginForm(['/site/logout'], 'post')
                                        . Html::submitButton(
                                            'Cerrar sesión (' . Yii::$app->user->identity->nombre_funcionario . ')',
                                            ['class' => 'btn btn-default logout']
                                        )
                                        . Html::endForm()
                                        . '</li>'
                                    ),
                                    ['label'=>'   '],
                                ],
                            ]);
                          }
                        
                      ?>
                      
                
                <?php                      
                  }
                ?>
              <?php  
                } 
                catch (Exception $e) {
                  echo Nav::widget([
                      'options' => ['class' => 'navbar-nav navbar-right'],
                      'encodeLabels' => false,
                      'items' => [
                          //['label' => 'Inicio', 'url' => ['/site/index']],
                    //New

                    //Fin New
                          ['label' => 'Acerca de', 'url' => ['/site/about']],
                          ['label' => 'Contáctenos', 'url' => ['/site/contact']],
                          Yii::$app->user->isGuest ? (
                              ['label' => '<span class="glyphicon glyphicon-log-in"></span> Iniciar sesión', 'url' => ['/site/index']]
                          ) : (
                              '<li>'
                              . Html::beginForm(['/site/logout'], 'post')
                              . Html::submitButton(
                                  'Cerrar sesión (' . Yii::$app->user->identity->nombre_funcionario . ')',
                                  ['class' => 'btn btn-default logout']
                              )
                              . Html::endForm()
                              . '</li>'
                          ),['label'=>'   '],
                      ],
                  ]);
                }
              ?>

            </ul>
          </div>
      </nav>
<!-- So much iimportant // aside axis-->
    </header>

     <?php
        try {    
          User::IsAdministrador();          
        ?>
          <aside class="main-sidebar">
            <section class="sidebar">
              <ul class="sidebar-menu" data-widget="tree">
                <script type="text/javascript">
                  $time = (45*60+1)*1000; //Debe ser igual al authTimeout del User
                  setTimeout(function(){
                    alert('Su sesión ha expirado por inactividad en la página');
                    window.location.href = '';
                  }, $time); //
                </script>

                <li class="header">MENÚ DE NAVEGACIÓN</li>

                <?php if(User::IsAdministrador()){?>
                  <li><a href="?r=user"><i class="glyphicon glyphicon-user"></i> <span>Usuarios</span></a></li>
                  <li><a href="?r=historial"><i class="glyphicon glyphicon-hourglass"></i> <span>Historial</span></a></li>
                <?php }?>

                <?php if(User::IsAdministrador() || User::IsRepartidor() || User::IsRadicador() || User::IsTramitador() || User::IsCertificador()){?>
                
                  <li><a href="?r=radicados"><i class="glyphicon glyphicon-folder-open"></i> <span>Radicados</span></a></li>
                  <li><a href="?r=entidades"><i class="glyphicon glyphicon-tower"></i> <span>Entidades</span></a></li>

                  <li class="treeview">
                    <a href="#">
                      <i class="glyphicon glyphicon-cog"></i>
                      <span>Configuraciones</span>
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="?r=cargos"><i class="glyphicon glyphicon-cog"></i>Cargos</a></li>
                      <li><a href="?r=grupos-cargos"><i class="fa glyphicon glyphicon-cog"></i>Grupo de Cargos</a></li>
                      <li><a href="?r=tipo-entidad"><i class="glyphicon glyphicon-tower"></i> <span>Tipo de Entidades</span></a></li>
                      <li><a href="?r=profesional%2Fview&id=1"><i class="glyphicon glyphicon-user"></i>Profesional</a></li>
                      <li><a href="?r=motivo-certificado"><i class="glyphicon glyphicon-question-sign"></i>Motivos Certificados</a></li>
                    </ul>
                  </li>

                  <li class="header">VALORES</li>
                  <li class="treeview">
                    <a href="#">
                      <i class="glyphicon glyphicon-credit-card"></i>
                      <span>Valores</span>
                      <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="?r=valores"><i class="glyphicon glyphicon-certificate"></i> <span>Estampillas</span></a></li>
                      <li><a href="?r=precios-tramites"><i class="glyphicon glyphicon-usd"></i>Precios Tramites</a></li>
                    </ul>
                  </li>

                  <li class="header">VALIDACION</li>
                  <li class="treeview">
                    <a href="#">
                      <i class="glyphicon glyphicon-file"></i>
                      <span>Validacion</span>
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="?r=tramite-publico%2Fcreate"><i class="glyphicon glyphicon-file"></i> <span>Generar tramite</span></a></li>
                      <li><a href="?r=validacion"><i class="glyphicon glyphicon-file"></i>Validacion</a></li>
                      <?php if(User::IsAdministrador() || User::IsCertificador()){?>
                        <li><a href="?r=certificar"><i class="glyphicon glyphicon-floppy-open"></i>Certificar</a></li>
                      <?php }?>
                    </ul>
                  </li>

                  <li class="header">DOCUMENTACIÓN</li>                
                  <li ><a href="?r=user%2Fpassword"><i class="glyphicon glyphicon-lock "></i><span>Cambiar Contraseña </span></a></li>
                  <?php
                    $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
                    echo "<li class='bg-green'><a href=".$url." style='background-color: rgb(50, 137, 255);'><i class='glyphicon glyphicon-book' style='color: rgb(255, 255, 255);'></i> <span style='color: rgb(255, 255, 255);'>Documentación</span></a></li>"
                  ?>
                  <li class="header">NAVEGACION</li>
                  <li><a href="javascript:history.back()"><i class="glyphicon glyphicon-chevron-left"></i> <span>Pagina Anterior</span></a></li>
                  <li><a href="index.php"><i class="fa fa-home"></i> <span>Home</span></a>
                  <li><a href="javascript:history.forward()"><i class="glyphicon glyphicon-chevron-right"></i> <span>Pagina Siguiente</span></a></li>
                <?php }?>
                
              </ul>
            </section>
          </aside>

          <div class="content-wrapper">
          
            <section class="content-header">
              <div class="top-bar">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4">
                            </div>
                            <div class="col-xs-8 col-sm-3 col-md-3 col-lg-3 col-md-offset-1">
                              <img src="img/escudocolombiano.png" alt="Escudo" width="25%" />
                            </div>
                            <div class="col-xs-4 col-md-4 col-lg-4">
                              <div class="text-right">
                                  <a href="https://www.elvalleestaenvos.com/" target="_blank" target="_blank" class="logo">
                                    <img src="img/valle.png" alt="Logo Gobernación" width="40%" class="text-center" />
                                  </a>
                                  <a href="https://www.facebook.com/GobValle/" target="_blank" title="Facebook" class="text-right btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                                  <a href="https://twitter.com/GobValle" target="_blank" title="Twitter" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
                                  <a href="https://www.youtube.com/user/VideosGobValle" target="_blank" title="Youtube" class="btn btn-social-icon btn-google"><i class="fa fa-youtube-square"></i></a>
                              </div>
                            </div>
                        </div>
                    </div><!--/.container-->
              </div><!--/.top-bar-->

              <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) 
                ?>
              </div>
            </section>

            <section class="content">
              <?= $content ?>
            </section>
          </div>
        <?php
          }
        catch (Exception $e) {
        ?>

          <aside class="main-sidebar">
            <section class="sidebar">
              <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MENÚ DE NAVEGACIÓN</li>
                    
                    <li><a href="?r=entidades"><i class="glyphicon glyphicon-tower"></i> <span>Entidades</span></a></li>
                    <li class="header">VALIDACION</li>
                      <li class="treeview">
                        <a href="#">
                          <i class="glyphicon glyphicon-file"></i>
                          <span>Validacion</span>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="?r=tramite-publico%2Fcreate"><i class="glyphicon glyphicon-file"></i> <span>Generar tramite</span></a></li>
                          <li><a href="?r=validacion"><i class="glyphicon glyphicon-file"></i>Validacion</a></li>
                        </ul>
                      </li>

                      <?php
                        $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
                        echo "<li class='bg-green'><a href=".$url." style='background-color: rgb(50, 137, 255);'><i class='glyphicon glyphicon-book' style='color: rgb(255, 255, 255);'></i> <span style='color: rgb(255, 255, 255);'>Documentación</span></a></li>"
                      ?>
                      <li class="header">NAVEGACION</li>
                      <li><a href="javascript:history.back()"><i class="glyphicon glyphicon-chevron-left"></i> <span>Pagina Anterior</span></a></li>
                      <li><a href="index.php"><i class="fa fa-home"></i> <span>Home</span></a>
                      <li><a href="javascript:history.forward()"><i class="glyphicon glyphicon-chevron-right"></i> <span>Pagina Siguiente</span></a></li>
              </ul>
            </section>
        </aside>

        <div class="content-wrapper">
         
            <section class="content-header">
                    <div class="top-bar">
                      <div class="container-fluid">
                          <div class="row">
                              <div class="col-lg-4">
                              </div>
                              <div class="col-xs-8 col-sm-3 col-md-3 col-lg-3 col-md-offset-1">
                                <img src="img/escudocolombiano.png" alt="Escudo" width="25%" />
                              </div>
                              <div class="col-xs-4 col-md-4 col-lg-4">
                                  <div class="text-right">
                                      <a href="https://www.elvalleestaenvos.com/" target="_blank"  target="_blank" class="logo">
                                        <img src="img/valle.png" alt="Logo Gobernación" width="40%" class="text-center" />
                                      </a>
                                      <a href="https://www.facebook.com/GobValle/"  target="_blank" title="Facebook" class="text-right btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                                      <a href="https://twitter.com/GobValle" target="_blank"  title="Twitter" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
                                      <a href="https://www.youtube.com/user/VideosGobValle" target="_blank" title="Youtube" class="btn btn-social-icon btn-google"><i class="fa fa-youtube-square"></i></a>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>

                    <div class="container">
                      <?= Breadcrumbs::widget([
                          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],])
                      ?>
                    </div>
            </section>

            <section class="content">
              <?= $content ?>
            </section>

        </div>

      <?php
          }
      ?>

    <!-- End Content Header (Page header) -->
</div>

  <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <!--<b>Version</b> 2.1.0-->
        <br>
        <img class="logo-lg" src="img/logoGoberNew.png" alt="Logo Gobernación" />
      </div>
      <!-- <p class="pull-left">&copy; Personeria juridica <?= date('Y') ?></p> -->

      <div class="container">
        <div class=" col-sm-12 col-md-8">
          <div class="pull-right">
              <div class="contenido1">
                <p style="line-height: 15px"><strong>Gobernación del Valle del Cauca, Santiago de Cali - Colombia</strong><br>
                  Dirección: Carrera 6 entre calles 9 y 10 Edificio Palacio de San Francisco <br>Codigo Postal: 760045<br>
                  Conmutador: (602) 620 00 00 - 886 00 00 - Fax: 886 0150<br> Línea Gratuita: 01-8000972033<br>
                  Correo: Contactenos@valledelcauca.gov.co</p>
              </div>
          </div>
        </div>
      </div>
  </footer>
  <?php $this->endBody() ?>
</body>
</html>
  <?php $this->endPage() ?>

  <script>
   document.getElementById("miboton").click();
  function desactivar(element){
      //$(element).attr('disabled', true);
    //  alert($(element).val());  // asi se obtiene el valor
    //  alert(element.id);     //  asi se obtiene el id
      // valor es la id del radicado y el id es a/b 1 o -1, para identificarlos cual se presiona
      if(element.id == "a" || element.id > 0){
          $.ajax({
            //http://localhost:8080/index.php?r=radicados%2Fview&id=8
            url: "?r=radicados%2Ffinalizado",
            dataType:"html",
            data : {
                  id:$(element).val(),
                },
            type: "post",
            success: function(data){
              var x = element.getAttribute('id');
              if(x == "a" || x == "b"){
                document.getElementById("a").disabled = true;
                document.getElementById("b").disabled = true;
              }else{
                document.getElementById(x).disabled = true;
                x = x * -1;
                document.getElementById(x).disabled = true;
              }
            },
            error: function (request, status, error) {
              alert("No se pudo realizar la operación error '"+request.responseText+"' Comuniquese con un administrador");
              }
          });
      }else{

        $.ajax({
          //http://localhost:8080/index.php?r=radicados%2Fview&id=8 http://localhost:8080/index.php?r=radicados%2Findex
          url: "?r=radicados%2Frechazado",
          dataType:"html",
          data : {
                id:$(element).val(),
              },
          type: "post",
          success: function(data){
            var x = element.getAttribute('id');
            if(x == "a" || x == "b"){
              document.getElementById("a").disabled = true;
              document.getElementById("b").disabled = true;
            }else{
              document.getElementById(x).disabled = true;
              x = x * -1;
              document.getElementById(x).disabled = true;
            }
          },
          error: function (request, status, error) {
            alert("No se pudo realizar la operación error '"+request.responseText+"' Comuniquese con un administrador");
            }
        });

      }

  }
  </script>

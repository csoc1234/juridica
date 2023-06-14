<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Nav;
use app\assets\AppAsset;
use app\models\Radicados;
use yii\widgets\ActiveForm;

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
                      /*
                      $radicados_reparto = array();
                      try{
                        $radicados_reparto = $session->get('radicados_reparto');  
                                           
                      }
                      catch (Exception $e){
        
                      }

                      $radicados_tramite = array();
                      try{
                        $radicados_tramite = $session->get('radicados_tramite');                                             
                      }
                      catch (Exception $e){

                      }

                      $radicados_devolucion = array();
                      try{
                        $radicados_devolucion = $session->get('radicados_devolucion');                       
                      }
                      catch (Exception $e){

                      }
                      $nradicado_reparto = count($radicados_reparto);
                      $nradicado_tramite = count($radicados_tramite);
                      $nradicado_devolucion = count($radicados_devolucion);

                      $nradicados = $nradicado_reparto+$nradicado_tramite+$nradicado_devolucion;

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
                                
                                echo"
                                <li>
                                  <a href='?r=radicados%2Fview&id=$radicados_reparto[$i]'>
                                    <h3>
                                      <i class='fa  fa-circle-o-notch text-aqua'></i> Radicado No. <strong> $radicados_reparto[$i]</strong>
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
                                
                                echo"
                                <li>
                                  <a href='?r=radicados%2Fview&id=$radicados_tramite[$i]'>
                                    <h3>
                                      <i class='fa  fa-circle-o-notch text-aqua'></i> Radicado No. <strong> $radicados_tramite[$i]</strong>
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
                                
                                echo"
                                <li><!-- Task item -->
                                  <a href='?r=radicados%2Fview&id=$radicados_devolucion[$i]'>
                                    <h3>
                                      <i class='fa  fa-circle-o-notch text-aqua'></i> Radicado No. <strong> $radicados_devolucion[$i]</strong>
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
                        **/
                        ?>
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
        $idRol = Yii::$app->user->identity->id_rol;

        switch ($idRol) {
          case 4:
          ?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <script type="text/javascript">
    $time = (20*60+1)*1000; //Debe ser igual al authTimeout del User
    setTimeout(function(){
      alert('Su sesión ha expirado por inactividad en la página');
      window.location.href = '';

    }, $time); //
  </script>
      <li class="header">MENÚ DE NAVEGACIÓN</li>
       <!-- Entidades -->
       <li><a href="?r=radicados"><i class="glyphicon glyphicon-folder-open"></i> <span>Radicados</span></a></li>
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
      
      </li>

      <li class="header">DOCUMENTACIÓN</li>
      <!--Doc-->
      <li ><a href="?r=user%2Fpassword"><i class="glyphicon glyphicon-lock"></i><span>Cambiar Contraseña </span></a>
        <?php
        $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
        echo "<li class='bg-green'><a href=".$url." style='background-color: rgb(50, 137, 255);'><i class='glyphicon glyphicon-book' style='color: rgb(255, 255, 255);'></i> <span style='color: rgb(255, 255, 255);'>Documentación</span></a></li>"
        ?>
      <li><a href="javascript:history.back()"><i class="glyphicon glyphicon-chevron-left"></i> <span>Pagina Anterior</span></a></li>
      <li><a href="index.php"><i class="fa fa-home"></i> <span>Home</span></a>
      <li><a href="javascript:history.forward()"><i class="glyphicon glyphicon-chevron-right"></i> <span>Pagina Siguiente</span></a></li>
    </ul>

  </section>
    <!-- /.sidebar -->
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
                          <a href="https://www.elvalleestaenvos.com/" target="_blank" class="logo">

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
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
          ]) ?>
        </div>
        <section class="content">
          <?= $content ?>
        </section>
      </section>
   <!-- Main content -->
    </div>
<?php
          break;
          case 3:
          ?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <script type="text/javascript">
    $time = (20*60+1)*1000; //Debe ser igual al authTimeout del User
    setTimeout(function(){
      alert('Su sesión ha expirado por inactividad en la página');
      window.location.href = '';

    }, $time); //
  </script>
      <li class="header">MENÚ DE NAVEGACIÓN</li>
       <!-- Entidades -->
       <li><a href="?r=radicados"><i class="glyphicon glyphicon-folder-open"></i> <span>Radicados</span></a></li>
      <!--/. Entidades -->
      <!--Resoluciones-->

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
      
      <li class="header">Validacion</li>
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
            <li><a href="?r=certificar"><i class="glyphicon glyphicon-floppy-open"></i>Certificar</a></li>
          </ul>
        </li>
      <li class="header">DOCUMENTACIÓN</li>
      <!--Doc-->
      <li ><a href="?r=user%2Fpassword"><i class="glyphicon glyphicon-lock"></i><span>Cambiar Contraseña </span></a></li>
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
    <!-- /.sidebar -->
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
                          <a href="https://www.elvalleestaenvos.com/" target="_blank" class="logo">

                          <img src="img/valle.png" alt="Logo Gobernación" width="40%" class="text-center" />
                          </a>
                          <a href="https://www.facebook.com/GobValle/"  target="_blank" title="Facebook" class="text-right btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                          <a href="https://twitter.com/GobValle" target="_blank" title="Twitter" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
                          <a href="https://www.youtube.com/user/VideosGobValle" target="_blank" title="Youtube" class="btn btn-social-icon btn-google"><i class="fa fa-youtube-square"></i></a>
                      </div>
                    </div>

                </div>
            </div><!--/.container-->
        </div><!--/.top-bar-->

      <div class="container">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
      </div>
        <section class="content">
          <?= $content ?>
        </section>
    </section>
 <!-- Main content -->
  </div>

<?php
          break;
          case 2:
    ?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
  <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <script type="text/javascript">
    $time = (20*60+1)*1000; //Debe ser igual al authTimeout del User
    setTimeout(function(){
      alert('Su sesión ha expirado por inactividad en la página');
      window.location.href = '';

    }, $time); //
  </script>
      <li class="header">MENÚ DE NAVEGACIÓN</li>
     <!-- Entidades -->
      <li><a href="?r=radicados"><i class="glyphicon glyphicon-folder-open"></i> <span>Radicados</span></a></li>
      <li><a href="?r=entidades"><i class="glyphicon glyphicon-tower"></i> <span>Entidades</span></a></li>
    <!--/. Entidades -->
    <!--Resoluciones-->

      <li class="header">CONFIGURACIÓN </li>
      <li class="treeview">
        <a href="#">
          <i class="glyphicon glyphicon-cog"></i>
          <span>Cargos</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="?r=cargos"><i class="glyphicon glyphicon-cog"></i>Cargos</a></li>
          <li><a href="?r=grupos-cargos"><i class="glyphicon glyphicon-cog"></i>Grupo de Cargos</a></li>
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
      <!--/.Anexos-->
    <!--Informes
    <li><a href="#"><i class="fa fa-line-chart"></i> <span>Informes</span></a></li> -->
    
    <li class="header">Validacion</li>
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
            <li><a href="?r=certificar"><i class="glyphicon glyphicon-floppy-open"></i>Certificar</a></li>
          </ul>
        </li>
      <li class="header">DOCUMENTACIÓN</li>
    <!--Doc-->
    <li ><a href="?r=user%2Fpassword"><i class="glyphicon glyphicon-lock "></i><span>Cambiar Contraseña </span></a></li>
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
  <!-- /.sidebar -->
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
                          <a href="https://www.elvalleestaenvos.com/" target="_blank" class="logo">

                          <img src="img/valle.png" alt="Logo Gobernación" width="40%" class="text-center" />
                          </a>
                          <a href="https://www.facebook.com/GobValle/" target="_blank"  title="Facebook" class="text-right btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
                          <a href="https://twitter.com/GobValle" target="_blank" title="Twitter" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
                          <a href="https://www.youtube.com/user/VideosGobValle" target="_blank" title="Youtube" class="btn btn-social-icon btn-google"><i class="fa fa-youtube-square"></i></a>


                    </div>
                    </div>

                </div>
            </div><!--/.container-->
        </div><!--/.top-bar-->

      <div class="container">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
      </div>
      <section class="content">
        <?= $content ?>
      </section>
    </section>
  <!-- Main content -->
  </div>

  <?php

          break;
          case 1:
?>

<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
      <script type="text/javascript">
    $time = (20*60+1)*1000; //Debe ser igual al authTimeout del User
    setTimeout(function(){
      alert('Su sesión ha expirado por inactividad en la página');
      window.location.href = '';

    }, $time); //
  </script>
      <li class="header">MENÚ DE NAVEGACIÓN</li>
      <!-- Documentacion-->
      <li><a href="?r=user"><i class="glyphicon glyphicon-user"></i> <span>Usuarios</span></a></li>
      <li><a href="?r=historial"><i class="glyphicon glyphicon-hourglass"></i> <span>Historial</span></a></li>
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
            <li><a href="?r=certificar"><i class="glyphicon glyphicon-floppy-open"></i>Certificar</a></li>
          </ul>
        </li>
      <li class="header">DOCUMENTACIÓN</li>
          <!-- Documentacion-->
          <li ><a href="?r=user%2Fpassword"><i class="glyphicon glyphicon-lock "></i><span>Cambiar Contraseña </span></a></li>
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
  <!--Contenido del destino-->
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
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <?= $content ?>
    </section>
    </div>
<?php
  break;
            }//fin Try
          }//fin sswitch
        catch (Exception $e) {
?>
           <aside class="main-sidebar">

        <section class="sidebar">

<ul class="sidebar-menu" data-widget="tree">
  <li class="header">MENÚ DE NAVEGACIÓN</li>
       <!-- Documentacion-->
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
        <li><a href="javascript:history.back()"><i class="glyphicon glyphicon-chevron-left"></i> <span>Pagina Anterior</span></a></li>
        <li><a href="index.php"><i class="fa fa-home"></i> <span>Home</span></a>
        <li><a href="javascript:history.forward()"><i class="glyphicon glyphicon-chevron-right"></i> <span>Pagina Siguiente</span></a></li>

</ul>
        </section>
        </aside>
        <div class="content-wrapper">
          <!--Contenido del destino-->
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
                      </div><!--/.container-->
                    </div><!--/.top-bar-->
                    <div class="container">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    </div>
            </section>

    <!-- Main content -->

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
                Conmutador: (57-2) 620 00 00 - 886 00 00 - Fax: 886 0150<br> Línea Gratuita: 01-8000972033<br>
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

<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Iniciar sesión';

$this->params['breadcrumbs'][] = $this->title;
?>

<center><h1>Personería Jurídica</h1></center>


<div class="site-login">
  <br>
  
    <div class="row">
        <div class="col-lg-4">
           <h1><?= Html::encode($this->title) ?></h1>
           <p>Por favor, complete los siguientes campos para ingresar:</p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'cedula')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div style="color:#999;margin:1em 0">
                    <!--If you forgot your password you can <?//= Html::a('reset it', ['site/request-password-reset']) ?>.-->
                    <!--Register <?//= Html::a('here', ['site/signup']) ?> or--> Si olvidó su contraseña, puede <?= Html::a('restablecerla', ['site/request-password-reset']) ?>.
            </div>

            <div class="form-group">
                    <?= Html::submitButton('Iniciar sesión', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <br>
            <br>

    <div class="body-content">
        <p>Sistema de información personería jurídica</p>

         <h2>Enlaces de interés</h2>

        <p><a href="http://www.valledelcauca.gov.co/">Gobernación del Valle del Cauca</a></p>

    </div>
    </div>

        <div id="carousel-example-generic" class="carousel slide col-md-8" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
                  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                  <!--
                  <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                  <li data-target="#carousel-example-generic" data-slide-to="3" class=""></li>
                  -->
                </ol>
                <div class="carousel-inner"> 
                  <div class="item active">
                    <center><img src="img/imagen.jpg" alt="First slide"></center>

                    <div class="carousel-caption">
                     Primera Diapositiva
                    </div>
                  </div>
                  <!--                  
                  <div class="item">
                    <center><img src="img/imagen2.jpg"  alt="Second slide"></center>

                    <div class="carousel-caption">
                      Segunda Diapositiva
                    </div>
                  </div>
                  <div class="item">
                    <center><img src="img/imagen3.jpg" alt="Third slide"></center>

                    <div class="carousel-caption">
                      Tercera Diapositiva
                    </div>
                  </div>
                  <div class="item">
                    <center><img src="img/imagen4.jpg" alt="Four slide"></center>

                    <div class="carousel-caption">
                      Cuarta Diapositiva
                    </div>
                  </div>
                  -->
                  <div class="item">
                    <center><img src="img/imagen5.jpg" alt="Second slide"></center>

                    <div class="carousel-caption">
                      Segunda Diapositiva
                    </div>
                  </div>
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>
              </div>
    </div>
</div>


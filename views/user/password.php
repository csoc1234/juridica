<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use kartik\password\PasswordInput;

$this->title = 'Actualizar Contraseña';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>
     <?php if ($msgreg !== null){  ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times; </button>
            <h4> Información </h4>
            <?php print $msgreg; ?>
            <?php } ?>
        </div>
    <p>Elija su nueva contraseña:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                 <?= $form->field($model, 'password_anterior')->passwordInput(['autofocus' => true]) ?>
                  <?= $form->field($model, 'password')->passwordInput() ?>
                  <?= $form->field($model, 'password_copy')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Actualizar contraseña', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

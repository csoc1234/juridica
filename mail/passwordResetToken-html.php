<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */


?>
<div class="password-reset">
    <h1>Hola <?= Html::encode($user->email) ?>,</h1>

    <p>Siga el siguiente enlace para restablecer su contraseña:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

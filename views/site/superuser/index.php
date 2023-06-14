<?php

    /* @var $this yii\web\View */
    use yii\helpers\Html;
    use app\models\User;

    $this->title = 'Software personería jurídica';

?>

<div class="site-index">
    <div class="j">
        <h1>Personería Jurídica</h1>
    </div>

    <div class="body-content">
        <div>
            <h3>Bienvenido <?= Yii::$app->user->identity->nombre_funcionario?></h3>            
        </div>
    </div>

    <div style="text-align: center;">
        <br>
        <br>
            <table style="margin: 0 auto;" width="80%">
                <tr>
                    <?php if(User::IsAdministrador()){?>
                        <td><a href="?r=user"><span class="glyphicon glyphicon-user fa-2x"><br> Usuarios</span>
                        <td><a href="?r=historial"><span class="glyphicon glyphicon-hourglass fa-2x"><br>Historial</span>
                    <?php }?>
					
					<?php if(User::IsAdministrador()|| User::IsModificador()){?>
                        <td><a href="?r=actualizar-entidades"><span class="glyphicon glyphicon-saved fa-2x"><br> Actualizar<br>Entidades</span>
                    <?php }?>

                    <?php if(User::IsAdministrador()|| User::IsRepartidor() || User::IsRadicador() || User::IsTramitador() || User::IsCertificador()){?>
                    <td><a href="?r=radicados"><span class="glyphicon glyphicon-folder-open fa-2x"><br> Radicados</span>
                    <td><a href="?r=entidades"><span class="glyphicon glyphicon-tower fa-2x"><br> Entidades</span>
                </tr>
            </table>
        <br>
        <br>
        <br>
            <table style="margin: 0 auto;" width="90%">
                <tr>
                    <td>
                        <div class="dropdown">
                            <a id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                <span class="glyphicon glyphicon-cog fa-2x"><br>Configuraciones</span>
                            </a>
                            <div class="dropdown-menu" style="background: transparent;">
                                <li><a class="dropdown-item" href="?r=cargos"><span class="glyphicon glyphicon-cog"></span>Cargos</a></li>
                                <li><a class="dropdown-item" href="?r=grupos-cargos"><span class="glyphicon glyphicon-cog"></span>Grupo de Cargos</a></li>
                                <li><a class="dropdown-item" href="?r=tipo-entidad"><span class="glyphicon glyphicon-tower"></span>Tipo de Entidades</a></li>
                                <li><a class="dropdown-item" href="?r=profesional%2Fview&id=1"><span class="glyphicon glyphicon-user"></span>Profesional</a></li>
                                <li><a class="dropdown-item" href="?r=motivo-certificado"><span class="glyphicon glyphicon-question-sign"></span>Motivo Certificado</a></li>
                            </div>
                        </div>  

                    </td>
                    <td>
                        <div class="dropdown">
                            <a id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                <span class="glyphicon glyphicon-credit-card fa-2x"><br>Valores</span>
                            </a>
                            <div class="dropdown-menu" style="background: transparent;">
                                <li><a class="dropdown-item" href="?r=valores"><span class="glyphicon glyphicon-certificate"></span>Estampillas</a></li>
                                <li><a class="dropdown-item" href="?r=precios-tramites"><span class="glyphicon glyphicon-usd"></span>Precios Tramites</a></li>
                            </div>   
                        </div> 
                    </td>
                    <td>
                        <div class="dropdown">
                            <a id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                <span class="glyphicon glyphicon-file fa-2x"><br>Certificar</span>
                            </a>
                            <div class="dropdown-menu" style="background: transparent;">
                                <li><a class="dropdown-item" href="?r=tramite-publico%2Fcreate"><span class="glyphicon glyphicon-file"></span>Generar Tramite</a></li>
                                <li><a class="dropdown-item" href="?r=validacion"><span class="glyphicon glyphicon-file"></span>Validacion</a></li>
                                <?php if(User::IsAdministrador()|| User::IsCertificador()){?>
                                    <li><a class="dropdown-item" href="?r=certificar"><span class="glyphicon glyphicon-floppy-open"></span>Certifcar</a></li>
                                <?php }?>
                            </div>   
                        </div> 
                    </td>
                    <td class="noBorder">
                        <a href="?r=user%2Fpassword"><span class="glyphicon glyphicon-lock fa-2x"><br>Cambiar<br>Contraseña</span>
                    </td>
                    <td>
                        <?php
                            $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
                            echo "<a href=".$url."><span class='glyphicon glyphicon-book fa-2x'><br>Documentación</span></a>"
                        ?>
                    </td>
                    <?php }?>
                </tr>
    </div>

            </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

</div>

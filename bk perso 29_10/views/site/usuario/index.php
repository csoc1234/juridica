<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Software personería jurídica';
?>

<div class="site-index">
    <div class="j2">
    <h1>Personería Jurídica</h1> 
    </div>

    <div class="body-content">
        <div>
            <h3>Bienvenido <?= Yii::$app->user->identity->nombre_funcionario?></h3>
            <h4>(<?php
                    $sqlwcm = 'select roles.rol from roles, user where roles.id_rol = user.id_rol and user.email = "'.Yii::$app->user->identity->email.'";';
                    print Yii::$app->db->createCommand($sqlwcm)->queryScalar();/*execute();*//*queryColumn();*//*queryRow();*//*queryAll();*/
                //print $printed[0];
                ?>)</h4>
        </div>
    </div>

<!-- Cambio de index por tipo de usuario -->
    <?php
    $idRol = Yii::$app->user->identity->id_rol;
    switch ($idRol) {
            case 4:
    ?>
    <div style="text-align: center;">
    <table style="margin: 0 auto;" width="60%">
    <tr>
        
        <td><a href="?r=radicados"><span class="glyphicon glyphicon-folder-open fa-2x"><br> Radicados</span></button>
        <td><a href="?r=entidades"><span class="glyphicon glyphicon-lock fa-2x"><br>Cambiar<br>Contraseña</span></td>
    </tr>
    </table>
    <br>
    <br>
    <br>
    <table style="margin: 0 auto;" width="55%">
    <tr>
<td>
            <div class="dropdown">
            <a href="#" id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-credit-card fa-2x"><br>valores
            </span></a>
            <div class="dropdown-menu" style="background: transparent;">
            <li><a class="dropdown-item" href="?r=valores"><span class="glyphicon glyphicon-certificate"></span>Estampillas</a></li>
            <li><a class="dropdown-item" href="?r=precios-tramites"><span class="glyphicon glyphicon-usd"></span>Precios Tramites</a></li>
            </div>

</td>

<td>
    <?php
        $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
        echo "<a href=".$url."><span class='glyphicon glyphicon-book fa-2x'><br>Documentación</span></a>"
        ?>
            
</td>
    </tr>
</div>
</table>  


    <?php
            break;
            case 3:
    ?>
    <div style="text-align: center;">
    <table style="margin: 0 auto;" width="60%">
    <tr>
        
        <td><a href="?r=radicados"><span class="glyphicon glyphicon-folder-open fa-2x"><br> Radicados</span></button>
        <td><a href="?r=entidades"><span class="glyphicon glyphicon-lock fa-2x"><br>Cambiar<br>Contraseña</span></td>
    </tr>
    </table>
    <br>
    <br>
    <br>
    <table style="margin: 0 auto;" width="70%">
    <tr>
<td>
            <div class="dropdown">
            <a href="#" id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-credit-card fa-2x"><br>valores
            </span></a>
            <div class="dropdown-menu" style="background: transparent;">
            <li><a class="dropdown-item" href="?r=valores"><span class="glyphicon glyphicon-certificate"></span>Estampillas</a></li>
            <li><a class="dropdown-item" href="?r=precios-tramites"><span class="glyphicon glyphicon-usd"></span>Precios Tramites</a></li>
            </div>

</td>
<td>
        <div class="dropdown">
            <a id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><span class="glyphicon glyphicon-file fa-2x"><br>certificar
            </span></a>
            <div class="dropdown-menu" style="background: transparent;">
            <li><a class="dropdown-item" href="?r=tramite-publico%2Fcreate"><span class="glyphicon glyphicon-file"></span>Generar Tramite</a></li>
            <li><a class="dropdown-item" href="?r=validacion"><span class="glyphicon glyphicon-file"></span>Validacion</a></li>
            <li><a class="dropdown-item" href="?r=certificar"><span class="glyphicon glyphicon-floppy-open"></span>Certifcar</a></li>
            </div>   
        </div> 
</td>
<td>
    <?php
        $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
        echo "<a href=".$url."><span class='glyphicon glyphicon-book fa-2x'><br>Documentación</span></a>"
        ?>
            
</td>
    </tr>
</div>

    </table> 
    <br>
    <br>
    <br>
    <br>   

    <?php
            break;
            case 2:
    ?>
    
    <div style="text-align: center;">
    <table style="margin: 0 auto;" width="65%">
    <tr>
        
        <td><a href="?r=radicados"><span class="glyphicon glyphicon-folder-open fa-2x"><br> Radicados</span></button>
        <td><a href="?r=entidades"><span class="glyphicon glyphicon-tower fa-2x"><br> Entidades</span></button>
        <td>
    <?php
        $url = Yii::$app->request->baseUrl . '/documentacion/ManualJuridica.pdf';
        echo "<a href=".$url."><span class='glyphicon glyphicon-book fa-2x'><br>Documentación</span></a>"
        ?>
    </td>
    </tr>
    </table>
    <br>
    <br>
    <br>    
    <table style="margin: 0 auto;" width="70%">
    <tr>
<td>
        <div class="dropdown">
            <a href="#" id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog fa-2x"><br>Cargos 
            </span></a></button>
            <div class="dropdown-menu" style="background: transparent;">
            <li><a class="dropdown-item" href="?r=cargos"><span class="glyphicon glyphicon-cog"></span>Cargos</a></li>
            <li><a class="dropdown-item" href="?r=grupos-cargos"><span class="glyphicon glyphicon-cog"></span>Grupo de Cargos</a></li>
        </div>

</td>
<td>
        <div class="dropdown">
            <a id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><span class="glyphicon glyphicon-file fa-2x"><br>certificar
            </span></a>
            <div class="dropdown-menu" style="background: transparent;">
            <li><a class="dropdown-item" href="?r=tramite-publico%2Fcreate"><span class="glyphicon glyphicon-file"></span>Generar Tramite</a></li>
            <li><a class="dropdown-item" href="?r=validacion"><span class="glyphicon glyphicon-file"></span>Validacion</a></li>
            <li><a class="dropdown-item" href="?r=certificar"><span class="glyphicon glyphicon-floppy-open"></span>Certifcar</a></li>
            </div>   
        </div> 
</td>
<td>
            <div class="dropdown">
            <a href="#" id="dropdownMenuButton" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-credit-card fa-2x"><br>valores
            </span></a>
            <div class="dropdown-menu" style="background: transparent;">
            <li><a class="dropdown-item" href="?r=valores"><span class="glyphicon glyphicon-certificate"></span>Estampillas</a></li>
            <li><a class="dropdown-item" href="?r=precios-tramites"><span class="glyphicon glyphicon-usd"></span>Precios Tramites</a></li>
            </div>
            
</td>
<td>
    <a href="?r=user%2Fpassword"><span class="glyphicon glyphicon-lock fa-2x"><br>Cambiar<br>Contraseña</span>
</td>
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

    <?php
            break;
            }//fin switch
    ?>

</div>

<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

?>
<div class="correo-reget">
  <div><div class="adM">
  </div><table width="600" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff">
    <tbody><tr align="center">
      <td style="vertical-align:top" colspan="3">
  		<font color="#ffffff">.</font><br>
  	  <center>
		<img src="<?= $message->embed($imageFileName); ?>" width="50%" alt="Imagen">
	</center>
      <br>
      <br>
      </td>
    </tr>

    <tr>
      <td width="600" bgcolor="#F2F3F7">
      	<table width="600" border="0" cellpadding="0" cellspacing="0">
  	  		<tbody>
  	  			<tr>
  					<td ></td>
  					<td >
  						<p style="font-family:arial,helvetica,verdana;font-weight:normal;font-size:32px;line-height:1.2;color:#00a8df;display:block;text-align:left;margin:0;padding:0;text-align:center">Información de radicado</p>
  					</td>
  					</tr>
  			</tbody>
  		</table>
      </td>
    </tr>

    <tr>
      <table width="600" border="0" cellpadding="0" cellspacing="10" align="center">
        	  		<tbody>
        	  			<tr>
        					<td><span style="font-family:arial,helvetica,verdana;font-weight:normal;font-size:14px;line-height:1.2;color:#50525a;display:block;margin:0;padding:0;text-align:justify">Usted tiene asignado el radicado No. <?php echo $nradicado;?>. </spam></td>
        				</tr>
        			</tbody>
      </table>
    </tr>
    <br>
    <tr align="center">
    	<td width="600">
      	<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
  	  		<tbody>
  	  			<tr>
  					<td width="20"></td>
  					<td width="560" valign="middle">
  						<table width="560" border="0" cellpadding="10" cellspacing="2" align="center">
  					  		<tbody>
	  			        <tr bgcolor="#00A8DF"><td colspan="2">
  									<p style="font-family:arial,helvetica,verdana;font-weight:bold;font-size:16px;line-height:1.2;color:#ffffff;display:block;margin:0">
  										Información del radicado </p></td>
  								</tr>
                  <tr bgcolor="#f1f4f6" style="font-family:arial,helvetica,verdana;font-weight:bold;font-size:14px;line-height:1.2;color:#50525a;margin:0">
                    <td width="185">Tipo de radicado</td>
                    <td width="185"><?php echo $tipoRadicado; ?></td>
                  </tr>

				  <tr bgcolor="#f1f4f6" style="font-family:arial,helvetica,verdana;font-weight:bold;font-size:14px;line-height:1.2;color:#50525a;margin:0">
                    <td width="185">Estado del radicado</td>
                    <td width="185"><?php echo $estadoRadicado; ?></td>
                  </tr>

					<tr bgcolor="#f1f4f6" style="font-family:arial,helvetica,verdana;font-weight:normal;font-size:14px;line-height:1.2;color:#50525a;margin:0">
						<td>
							Recuerda ingresar al portal de personerias, con tu N° de cédula y contraseña, para tramitar el radicado. Omita si el radicado se encuentra en estado finalizado.<span style="color:#1988EA"></span></td>
						<td width="190">
						<table>
						<tbody><tr>
						<td width="150" bgcolor="5AC400" valign="middle" class="m_6913142342396236600link" height="35" style="margin-bottom:0px;font-family:Arial,Helvetica,sans-serif;margin-top:0px;font-weight:100;font-size:18px;color:#ffffff;vertical-align:middle;text-align:center">
						<font color="#ffffff">
						<a href= "http://personerias.valledelcauca.gov.co" style="background:#1B8EF3;color:#ffffff;font-family:arial,helvetica,verdana;font-weight:bold;font-size:14px;line-height:2.5;text-decoration:none;width:150px;height:35px;display:block;text-align:center" target="_blank" data-saferedirecturl = "https://http://danubio.valledelcauca.gov.co/personerias/web">Entrar ahora</a></font></td>
						</tr>
						</tbody></table>
						</td>
					</tr>
                    <tr bgcolor="#f1f4f6" style="font-family:arial,helvetica,verdana;font-weight:bold;font-size:14px;line-height:1.2;color:#000000;margin:0"><td colspan="2">
    									<p style="font-family:arial,helvetica,verdana;font-weight:normal;font-size:14px;line-height:1.2;color:#000000;display:block;margin:0;padding:0;text-align:justify">
    										Este envío es confidencial y está destinado únicamente a la persona a la que ha sido enviado. Puede contener información privada y confidencial. Si usted no es el destinatario al que ha sido remitida, no puede copiarla, distribuirla ni emprender con ella ningún tipo de acción. Si cree que lo ha recibido por error, por favor, notifíquelo al remitente. </p></td>
    								</tr>
  							</tbody>
  						</table>
  						<br>
  						<table width="560" border="0" cellpadding="0" cellspacing="0">
  						</table>
  						<br>
  					</td>
  					<td width="20"></td>
  	  			</tr>
  			</tbody>
  		</table>
      </td>
    </tr>
    <tr align="center">
      <td width="560">
      	<table width="560" border="0" cellpadding="0" cellspacing="10" align="center">
  	  		<tbody>
  	  			<tr>
  					<td><span style="font-family:arial,helvetica,verdana;font-weight:normal;font-size:18px;line-height:1.2;color:#00a8df;display:block;margin:0;padding:0;text-align:justify">PD: Antes de imprimir este e-mail piense bien si es necesario hacerlo. El medio ambiente es cosa de todos.</span></td>
  	  			</tr>
  			  </tbody>
  		  </table>
      </td>
    </tr>
    <tr align="center">
    	<td width="600">
        <br>
      </td>
    </tr>

    <tr>
    	<td>
    		<br>
    		<p style="font-family:arial,helvetica,verdana;font-weight:normal;font-size:14px;line-height:1.2;color:#50525a;display:block;text-align:center;margin:0;padding:0"><strong><span class="il">Gobernación del Valle del Cauca, Santiago de Cali - Colombia</span></strong> </p>
    	</td>
    </tr>
  </tbody></table><div class="yj6qo"></div><div class="adL">
  </div></div>

</div>

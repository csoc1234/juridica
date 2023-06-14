<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $cedula_funcionario;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['cedula_funcionario', 'trim'],
            ['cedula_funcionario', 'required'],
          //  ['email', 'email'],
            ['cedula_funcionario', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'No existe un usuario registrado con ese número de identificación o nit.'
            ],
        ];
    }

	public function attributeLabels()
	{
    	return [
			'cedula_funcionario' => 'N° de cédula:',
    	];
	}

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'cedula_funcionario' => $this->cedula_funcionario,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
         Yii::$app
            ->mailer
            ->compose(
              //  ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
              //  ['user' => $user]
              ['html' =>'reestablecer.php'],
              ['nombres'=> $user->nombre_funcionario,
              'apellido1' => "",
              'apellido2' => "",
              'username' => $user->cedula_funcionario,
              'url' => $resetLink,
              ]
            )
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name]) // de
			/*->setFrom('xxxxxx')*/
            ->setTo($user->email)
            ->setSubject('Restablecer contraseña - Personerias')
            ->send();

            return $this->hiddenString($user->email);
    }
    function hiddenString($str, $start = 2, $end = 3){
    $len = strlen($str);
    $arroba = strpos($str,'@');
    if($arroba > 0){
      $end = $len -$arroba ;
    }
    return substr($str, 0, $start) . str_repeat('*', $len - ($start + $end)) . substr($str, $len - $end, $end);
    }

}

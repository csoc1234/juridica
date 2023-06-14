<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class RegistroForm extends Model
{
    public $nombre_funcionario;
    public $cedula_funcionario;
    public $cargo_funcionario;
    public $email;
    public $password,$password_copy;
	  public $id_rol;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Esta dirección de correo electrónico ya se ha asignado.'],
			      [['nombre_funcionario'], 'string', 'max' => 100,'min' => 3],
            [['cedula_funcionario'], 'string', 'max' => 20],
            [['cargo_funcionario'], 'string', 'max' => 100],
			      ['id_rol', 'trim'],
            ['id_rol', 'string', 'min' => 1, 'max' => 255],
            [['password','nombre_funcionario','email','id_rol'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_copy', 'compare', 'compareAttribute' => 'password', 'message' => 'Las contraseñas no coinciden'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'cedula_funcionario' => 'Cédula o Nit sin dígito de verificación',
            'password' => 'Contraseña',
            'id_rol' => 'Tipo de usuario',
            'password_copy' => 'Confirmación de contraseña',          
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function registro()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->nombre_funcionario = $this->nombre_funcionario;
        $user->cargo_funcionario = $this->cargo_funcionario;
        $user->cedula_funcionario = $this->cedula_funcionario;
		    $user->id_rol = $this->id_rol;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}

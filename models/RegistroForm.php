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
    public $id_privilegio;

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
            [['id_rol', 'id_privilegio'],'string'],
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
            'id_privilegio' => 'Privilegios',
            'password_copy' => 'Confirmación de contraseña'  
                
        ];
    }


    private $arrayPrivilegios;

     public function getArrayPrivilegios()
     {
          
          if($this->arrayPrivilegios == null) 
          {
                $this->arrayPrivilegios = $this->id_privilegio;
          }
          return $this->arrayPrivilegios;
     }

      
      public function setArrayPrivilegios($value)
      {
           $this->arrayPrivilegios = $value;
      }
}

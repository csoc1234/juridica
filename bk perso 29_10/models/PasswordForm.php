<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\User;
//use kartik\password\StrengthValidator;

class PasswordForm extends Model
{
    public $password_anterior;
	public $password;
	public $password_copy;

	public function rules(){
	return
		[
			[['password','password_copy','password_anterior'], 'string', 'min' => 6],
            ['password_anterior','validar'],
            //[['password'], StrengthValidator::className(), 'preset'=>'normal', 'userAttribute'=>'password_anterior','hasUserError'=>'La nueva contraseña no puede contener o ser igual a la anterior'],
            //['password', 'compare', 'compareAttribute' => 'password_anterior', 'message' => 'La contraseña no puede ser igual a la anterior'],
            ['password_copy', 'compare', 'compareAttribute' => 'password', 'message' => 'Las contraseñas no coinciden'],

		];
	}


	    public function attributeLabels()
    {
        return [
            'password_anterior' => 'Contraseña actual',
            'password' => 'Contraseña nueva',
            'password_copy' => 'Confirmación de contraseña nueva',
        ];
    }

    public function validar($attribute, $params){

        if(isset(Yii::$app->user->identity->id)){

        $user =  User::findOne(Yii::$app->user->identity->id);

            if(!$user->validatePassword($this->$attribute)){
                $this->addError($attribute, 'Contraseña actual incorrecta');
            }

        }

    }

    public function contraseña(){
    	if (!$this->validate()) {
            return null;
        }
        if(isset(Yii::$app->user->identity->id)){

        $user =  User::findOne(Yii::$app->user->identity->id);

        }else{
            $user = new User();
        }
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save(false) ? $user : null;
    }

}

<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
//New
//use yii\filters\AccessControl;
//Fin new

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $id_rol
 * @property string $id_privilegio
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

	//Constantes para los usuarios del sitio
    const ROL_ADMINISTRADOR = 100;
    
    const PRIVILEGIO_REPARTIDOR = 1;
    const PRIVILEGIO_RADICADOR = 2;
    const PRIVILEGIO_TRAMITADOR = 3;
    const PRIVILEGIO_CERTIFICADOR = 4;
    const PRIVILEGIO_MODIFICADOR = 5;

	//Funciones para limitar sitio para usuarios
	public static function roleInArray($array_rol){
        if(Yii::$app->user->identity->id_rol == 1){
            return true;
        }elseif(Yii::$app->user->identity->id_rol == 2){
            $Privilegios = explode(",",Yii::$app->user->identity->id_privilegio);
            foreach($Privilegios as $privilegio){
                if(in_array($privilegio, $array_rol)){
                    return true;
                }
            }
        }
        
    }

    public static function IsAdministrador(){

        return Yii::$app->user->identity->id_rol == 1;        
    }


    public static function IsRepartidor(){

        if(Yii::$app->user->identity->id_rol == 2){
            return in_array(self::PRIVILEGIO_REPARTIDOR,explode(",",Yii::$app->user->identity->id_privilegio));   
        }else return false;
        
    }

    public static function IsRadicador(){

        if(Yii::$app->user->identity->id_rol == 2){
            return in_array(self::PRIVILEGIO_RADICADOR,explode(",",Yii::$app->user->identity->id_privilegio));
        }else return false;
        
    }

    public static function IsTramitador(){

        if(Yii::$app->user->identity->id_rol == 2){
            return in_array(self::PRIVILEGIO_TRAMITADOR,explode(",",Yii::$app->user->identity->id_privilegio));
        }else return false;
        
    }
    
    public static function IsCertificador(){

        if(Yii::$app->user->identity->id_rol == 2){
            return in_array(self::PRIVILEGIO_CERTIFICADOR,explode(",",Yii::$app->user->identity->id_privilegio));
        }else return false;
   
    }

    public static function IsModificador(){

        if(Yii::$app->user->identity->id_rol == 2){
            return in_array(self::PRIVILEGIO_MODIFICADOR,explode(",",Yii::$app->user->identity->id_privilegio));
        }else return false;
   
    }

	public static function isActive(){
		return Yii::$app->user->identity->status == self::STATUS_ACTIVE;
  }

    /**
     * @inheritdoc
     */
  public static function tableName(){
    return '{{%user}}';
  }

    /**
     * @inheritdoc
     */
  public function behaviors()
  {
      return [
          TimestampBehavior::className(),
      ];
  }

    /**
     * @inheritdoc
     */
  public function rules()
  {
      return [
          ['status', 'default', 'value' => self::STATUS_ACTIVE],
          ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
          [['nombre_funcionario','cargo_funcionario'], 'string', 'max' => 100,'min' => 3],
          [['cedula_funcionario'], 'string', 'max' => 20],
          ['id_rol', 'trim'],
          [['id_rol', 'id_privilegio'], 'string'],
      ];
  }

  public function attributeLabels()
  {
      return [
            'status' => 'Estado',
            'id_rol' => 'Rol', 
            'id_privilegio' => 'Privilegios'       
      ];
  }
    /**
     * @inheritdoc
     */
  public static function findIdentity($id)
  {
      return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
  }

    /**
     * @inheritdoc
     */
  public static function findIdentityByAccessToken($token, $type = null)
  {
      throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
  public static function findByEmail($email)
  {
      return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
  }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
  public static function findByPasswordResetToken($token)
  {
      if (!static::isPasswordResetTokenValid($token)) {
          return null;
      }

      return static::findOne([
          'password_reset_token' => $token,
          'status' => self::STATUS_ACTIVE,
      ]);
  }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
  public static function isPasswordResetTokenValid($token)
  {
      if (empty($token)) {
          return false;
      }

      $timestamp = (int) substr($token, strrpos($token, '_') + 1);
      $expire = Yii::$app->params['user.passwordResetTokenExpire'];
      return $timestamp + $expire >= time();
  }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function getrol(){
      $roles = Roles::findOne($this->id_rol);
      return $roles['rol'];
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = "";
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

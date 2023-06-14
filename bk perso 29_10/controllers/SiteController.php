<?php

namespace app\controllers;
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
//New add
use yii\web\Response;
use app\models\User;
use app\models\Roles;
use app\models\Uhistorial;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
//use mPDF;
//End add
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\RegistroForm;
use app\models\ContactForm;

date_default_timezone_set('America/Bogota');
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
		//Aqui se agregan los stios que tendran restricción de acceso
		$only = ['logout', 'registro', 'iniciativas', 'cruds', 'create', 'update','Uhistorial' ,'entidadesform'];
		return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => $only,
                'rules' => [
                    [
                        'actions' => ['registro'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
							$valid_roles = [User::ROL_SUPERUSER];
							return User::roleInArray($valid_roles) && User::isActive();
                    	}
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					[
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) {
							$valid_roles = [User::ROL_USER,User::ROL_SUPERUSER];
							return User::roleInArray($valid_roles) && User::isActive();
                    	}
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
					'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin()
    {
		if(!Yii::$app->user->isGuest){

            $tipoUsuario = Yii::$app->user->identity->id_rol;

            switch ($tipoUsuario) {
            case '1':
                return $this->render('superuser/index');
                break;
            case '2':
                return $this->render('usuario/index');
                break;
            case '3':
                return $this->render('usuario/index');
                break;
            case '4':
                return $this->render('usuario/index');
                break;

            }
		}
		else
			return $this->render('index');
    }


    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {

            $tipoUsuario = Yii::$app->user->identity->id_rol;

            switch ($tipoUsuario) {
            case '1':
                return $this->render('superuser/index');
                break;
            case '2':
                return $this->render('usuario/index');
                break;
            case '3':
                return $this->render('usuario/index');
                break;
            case '4':
                return $this->render('usuario/index');
                break;
            default:
                return $this->render('index');
                break;
        }
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->set('id_entidad',"");
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact(){

        $model = new ContactForm();
        try {
          $msg = null;
          if ($model->load(Yii::$app->request->post()) && $model->validate()) {
              if ($model->sendEmail(Yii::$app->params['adminEmail'])){
                 $msg = 'Gracias por contactarnos. Responderemos a la mayor brevedad posible.';
              } else {
                $msg = 'Se ha producido un error al enviar el mensaje.';
                }
              $model->name = '';
              $model->email ='';
              $model->subject ='';
              $model->body ='';
              $model->verifyCode ='';
              return $this->render('contact', ['model' => $model, 'msg' => $msg]);
          } else {
              return $this->render('contact', [
                  'model' => $model,
                  'msg' => $msg,
              ]);
          }
        } catch (\Exception $e) {
            return SiteController::actionAbout();
        }
    }

    public function actionError()
    {
        return $this->render('error');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRegistro(){

    $model = new RegistroForm();
    $msgreg = null;
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->registro()) {

              Yii::$app->mailer->compose('bienvenido.php',[
                'cedula' => $model->cedula_funcionario,
                'nombres' => $model->nombre_funcionario,
                'cargo' => $model->cargo_funcionario,
                'password' => $model->password,
              ])
                 //->attach($ruta)
                 //->attachContent('Contenido adjunto', ['fileName' => 'attach.txt', 'contentType' => 'text/plain']);
                 ->setTo($model->email) // para
                 ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name]) // de
                 //->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name ]) // de
                 ->setSubject("Portal Personerias - Bienvenido/a") // asunto
                 //->setTextBody($mensaje) // cuerpo del mensaje
                 ->send();

              $msgreg = 'Usuario registrado correctamente';

              $model->nombre_funcionario = '';
              $model->cedula_funcionario = '';
              $model->cargo_funcionario ='' ;
              $model->id_rol = "";
              $model->email ='';
              $model->password = '';
              return $this->render('registro', ['model' => $model, 'msgreg' => $msgreg]);
              //return $this->refresh(); ---
            }
          }
      return $this->render('registro', [
        'model' => $model,
        'msgreg' => $msgreg,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
     public function actionRequestPasswordReset(){
         $model = new PasswordResetRequestForm();
         $msg = null;
         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             $resultado = $model->sendEmail();
             if ($resultado !== false) {
               $msg = 'Se ha enviado un correo electrónico a '.$resultado." con el paso a seguir para continuar con el proceso.";

               //  return $this->goHome();
             } else {
                 Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos restablecer la contraseña para la dirección de correo electrónico proporcionada.');
             }
         }

         return $this->render('requestPasswordResetToken', [
             'model' => $model,
             'msg' => $msg,
         ]);
     }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

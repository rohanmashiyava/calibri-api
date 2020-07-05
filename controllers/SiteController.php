<?php

namespace app\controllers;

use app\models\UserDevicesInfo;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    public function actionUploadPrevious(){
        $json = file_get_contents(Yii::getAlias(Yii::getAlias('@app').DIRECTORY_SEPARATOR.'data-json'.DIRECTORY_SEPARATOR.'firestore-export.json'));
        $arr = Json::decode($json);
        $flg= true;
        $transaction = Yii::$app->db->beginTransaction();

        foreach($arr as $key => $data_arr){
           foreach($data_arr  as $data){
               $devices = new UserDevicesInfo();
               $devices->unique_device_id = $data['UUID'];
               $devices->version_code =  (string)$data['versionCode'];
               $devices->last_version = isset($data['lastVersion'])?$data['lastVersion']:null;
               $devices->version_name = (string)$data['versionName'];
               $devices->paid_app = isset($data['paidApp'])?$data['paidApp']:0;
               $devices->created_date = time();
               if($devices->validate()){
                   $devices->save();
                   $flg= true;
               }else{
                   foreach($devices->errors as $error){
                       foreach($error as $e){
                           echo $e;
                       }
                   }
                   $flg= false;

               }
           }
           if($flg == true){
               echo "badha insert thai gya";
               $transaction->commit();
           }else{
               echo "locho..";
               $transaction->rollBack();
           }
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

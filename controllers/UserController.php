<?php

namespace app\controllers;

use Yii;
use app\nexmo\NexmoMessage;
use app\models\LoginForm;
use app\models\ContactForm;
use app\forms\TransferForm;
use app\forms\SignupForm;
use app\forms\ViewForm;
use app\forms\CreateaccountForm;
use app\components\Mandrill;
use app\models\Account;
use app\models\Transaction;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class UserController extends \yii\web\Controller
{
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
    	if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

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

    public function actionSignup()
    {
        $db = Yii::$app->db->beginTransaction();
        $model = new SignupForm;

        if($model->load(Yii::$app->request->post())){
            try
            {
                $model->signUp();
                    
                Yii::$app->getSession()->setFlash('success', 'Register Successfully.');

                Yii::$app->mailer->compose('email')
                    ->setFrom('assignment1200@gmail.com')
                    ->setTo($model->email)
                    ->setSubject('WELCOME')
                    ->send();

                $db->commit();
                return $this->redirect('signup');

            }catch(\Exception $e){
                $db->rollback();
                echo $e->getMessage();    
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    public function actionCreateaccount()
    {
        $model = new CreateaccountForm;

        if($model->load(Yii::$app->request->post())){
            $model->createAccount();
            Yii::$app->getSession()->setFlash('success', 'Create account successfully.');

            return $this->redirect('createaccount');
        }
        return $this->render('createaccount', ['model' => $model]);
    }

    public function actionTransfer()
    {
        $client = new Client();
        $db = Yii::$app->db->beginTransaction();
        $user = User::findOne(Yii::$app->user->identity->id);
        $model = new TransferForm();
        $account = Account::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        $listData = ArrayHelper::map($account, 'account_number', 'account_number');
        // $model->getAccountnumber();

        if($model->load(Yii::$app->request->post())){
            try{
                $model->transferAccount();
                Yii::$app->getSession()->setFlash('success', 'Amount transfered successfully.');

                // SMS Function
                // $nexmo_sms = new NexmoMessage('dd8a33e0', 'M7Y7VNY4XfMH8K8f');

                // $info = $nexmo_sms->sendText( '+60176049207', '60176049207', 'This transaction has been made.' );
                // echo $nexmo_sms->displayOverview($info);


                $db->commit();

                //SMS Function
                $response = $client->createRequest()
                            ->setMethod('GET')
                            ->setUrl('https://platform.clickatell.com/messages/http/send')
                            ->setData(['apiKey' => '1g4tmCw5SBejQ3JSf0b_7w==', 'to' => '60176049207', 'content' => 'A transaction has been made. Thank you for using our service.'])
                            ->send();

                //Email Function
                Yii::$app->mailer->compose('transactionemail')
                    ->setFrom('assignment1200@gmail.com')
                    ->setTo($user->email)
                    ->setSubject('TRANSACTION')
                    ->send();
                return $this->redirect('transfer');

            }catch(\Exception $e){
                $db->rollback();
                return $e;
            }
        }
        return $this->render('transfer', ['model' => $model, 'listData' => $listData]);
    }

    public function actionViewaccount()
    {
        $id = Yii::$app->user->identity->id;
        $dataProvider = Account::findOne($id);

        return $this->render('viewaccount', ['dataProvider' => $dataProvider]);
    }

    public function findModel($id)
    {
        if(($model = Account::findOne($id)) !== null){
            return $model;
        } 
        throw new HttpRequestException('The request page does not exist.');
    }

    public function actionTransactionhistory()
    {
        $id = Yii::$app->user->identity->id;
        $dataProvider = Transaction::findOne($id);

        return $this->render('transactionhistory', ['dataProvider' => $dataProvider]);
    }

    public function actionTestmail()
    {
        $this->layout = '@app/mail/layouts/html';

        $recipient = [
            [
                'email' => 'onizaki920@gmail.com',
                'name' => 'Name',
                'type' => 'to'
            ]
        ];

        $subject = 'TESTING EMAIL';
        $message = 'Testing';

        $content = $this->render('@app/mail/email');

        $mandrill = Yii::$app->mandrill->instance;
        $message = Yii::$app->mandrill->message;
        $message['to'] = $recipient;
        $message['html'] =  $content;
        $message['subject'] = $subject;

        $status = $mandrill->message->send($message, false, "Test", null);
        echo var_export($status, true) . "\n";
    }

    public function actionUploadimage()
    {
        $image = UploadedFile::getInstanceByName('image');

        $aws = Yii::$app->aws->client();
        $s3 = $aws->createS3();
        $result = $s3->putObject([
            'ACL' => 'public-read',
            'Bucket' => 'FOLDER_NAME_OBTAIN_FROM_S3',
            'ContentType' => $image->type,
            'SourceFile' => $image->tempName,
            'Key' => Yii::getAlias("your_folder_name/{$filename}.{$image->extension}"),
        ]);
        $result->get("ObjectURL");
    }

    public function actionUserprofile()
    {
        $model = User::findOne(Yii::$app->user->identity->id);

        return $this->render('userprofile', ['model' => $model]);
    }

    public function actionUpdateprofile($id)
    {
        $db = Yii::$app->db->beginTransaction();
        $model = User::findOne($id);

        if($model->load(Yii::$app->request->post())){
            try
            {
                if(!$model->save()){
                    throw new Exception(current($model->getFirstErrors()));
                }  
                Yii::$app->getSession()->setFlash('success', 'Profile updated Successfully.');

                $db->commit();
                return $this->redirect('userprofile');

            }catch(\Exception $e){
                $db->rollback();
                echo $e->getMessage();    
            }

        }
        return $this->render('updateprofile', ['model' => $model]);
    }
}

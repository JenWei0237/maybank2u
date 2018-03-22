<?php

namespace app\controllers;

use Yii;
use app\nexmo\NexmoMessage;
use app\models\LoginForm;
use app\models\ContactForm;
use app\forms\TransferForm;
use app\forms\SignupForm;
use app\forms\ViewForm;
use app\forms\AccountForm;
use app\forms\AccountSearchForm;
use app\forms\TransactionSearchForm;
use app\forms\CreateaccountForm;
use app\components\Mandrill;
use app\models\Account;
use app\models\Transaction;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\data\ActiveDataProvider;

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
                    'logout' => ['get'],
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
        $user = User::find();
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

    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionSignup()
    {
        $db = Yii::$app->db->beginTransaction();
        $model = new SignupForm;

        if($model->load(Yii::$app->request->post())){
            try
            {
                $model->signUp();
                    
                Yii::$app->getSession()->setFlash('success', 'Register Successfully.');

                // Yii::$app->mailer->compose('email')
                //     ->setFrom('assignment1200@gmail.com')
                //     ->setTo($model->email)
                //     ->setSubject('WELCOME')
                //     ->send();

                $db->commit();
                return $this->redirect('signup');

            }catch(\Exception $e){
                $db->rollback();
                Yii::$app->getSession()->setFlash('danger', $e->getMessage());
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    // public function actionCreateaccount()
    // {
    //     $model = new CreateaccountForm;

    //     if($model->load(Yii::$app->request->post())){
    //         $model->createAccount();
    //         Yii::$app->getSession()->setFlash('success', 'Create account successfully.');

    //         return $this->redirect('createaccount');
    //     }
    //     return $this->render('createaccount', ['model' => $model]);
    // }

    public function actionTransfer()
    {
        if(!Yii::$app->user->isGuest){
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

                    $db->commit();

                    //SMS Function
                    $response = $client->createRequest()
                                ->setMethod('GET')
                                ->setUrl('https://platform.clickatell.com/messages/http/send')
                                ->setData(['apiKey' => '1g4tmCw5SBejQ3JSf0b_7w==', 'to' => '60176049207', 'content' => 'A transaction has been made. Thank you for using our service.'])
                                ->send();

                    //Email Function
                    // Yii::$app->mailer->compose('transactionemail')
                    //     ->setFrom('assignment1200@gmail.com')
                    //     ->setTo($user->email)
                    //     ->setSubject('TRANSACTION')
                    //     ->send();
                    return $this->redirect('transfer');

                }catch(\Exception $e){
                    $db->rollback();
                    
                    Yii::$app->getSession()->setFlash('danger', $e->getMessage());
                }
            }
            return $this->render('transfer', ['model' => $model, 'listData' => $listData]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionViewaccount()
    {
        if(!Yii::$app->user->isGuest){
            $id = Yii::$app->user->identity->id;
            $dataProvider = Account::findOne($id);

            return $this->render('viewaccount', ['dataProvider' => $dataProvider]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
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
        if(!Yii::$app->user->isGuest){
            $id = Yii::$app->user->identity->id;
            $dataProvider = Transaction::findOne($id);

            return $this->render('transactionhistory', ['dataProvider' => $dataProvider]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
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
        if(!Yii::$app->user->isGuest){
            $model = User::findOne(Yii::$app->user->identity->id);

            return $this->render('userprofile', ['model' => $model]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionUpdateprofile($id)
    {
        if(!Yii::$app->user->isGuest){
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
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionForgetpassword()
    {
        $model = new SignupForm;
        $db = Yii::$app->db->beginTransaction();

        if($model->load(Yii::$app->request->post())){
            try{
                $code = $model->requestCode($model->email);

                Yii::$app->mailer->compose('securitycodeemail', ['code' => $code])
                        ->setFrom('assignment1200@gmail.com')
                        ->setTo($model->email)
                        ->setSubject('Verfication Code')
                        ->send();

                Yii::$app->getSession()->setFlash('success', 'Verification code has been sent to your email, please check your email.');

                $db->commit();
                
                return $this->redirect('nextforgetpassword');
            }catch(\Exception $e){
                $db->rollback();
                Yii::$app->getSession()->setFlash('danger', $e->getMessage());
            }
        }

        return $this->render('forgetpassword', ['model' => $model]);
    }

    public function actionNextforgetpassword()
    {
        $model = new SignupForm();
        $db = Yii::$app->db->beginTransaction();

        if($model->load(Yii::$app->request->post())){
            try{
                $model->newPassword($model->security_code);

                Yii::$app->getSession()->setFlash('success', 'Your password has been resetted.');

                $db->commit();

                return $this->redirect('login');

            }catch(\Exception $e){
                $db->rollback();
                Yii::$app->getSession()->setFlash('danger', $e->getMessage());
            }
        }
        return $this->render('nextforgetpassword', ['model' => $model]);
    }

    public function actionActivateaccount()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->position === 'admin'){

                $model = new AccountForm;
                $user = User::find()->where(['activation' => 'Deactivate'])->all();
                $listData = ArrayHelper::map($user, 'id', 'name');
                $db = Yii::$app->db->beginTransaction();

                $model->getBalance();

                if($model->load(Yii::$app->request->post())){
                    try{
                        $model->activateAccount();
                        $db->commit();

                        Yii::$app->getSession()->setFlash('success', 'This user account has been activated.');

                        return $this->redirect('activateaccount');
                    }catch(\Exception $e){
                        $db->rollback();
                        Yii::$app->getSession()->setFlash('danger', $e->getMessage());
                    }            
                }

                return $this->render('activateaccount', [
                    'model' =>$model,
                    'listData' => $listData
                ]);
            }else {
                Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');

                return $this->goHome();
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionChangepassword()
    {
        if(!Yii::$app->user->isGuest){
            $model = new SignupForm();
            $db = Yii::$app->db->beginTransaction();

            if($model->load(Yii::$app->request->post())){
                try{
                    $model->changePassword();
                    $db->commit();

                    Yii::$app->getSession()->setFlash('success', 'New password has been changed.');
                    return $this->redirect('changepassword');
                }catch(\Exception $e){
                    $db->rollback();
                    Yii::$app->getSession()->setFlash('danger', $e->getMessage());
                }
            }

            return $this->render('changepassword', ['model' => $model]);

        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionViewaccountlist()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->position === 'admin'){
                $searchModel = new AccountSearchForm();
                $dataProvider = $searchModel->accountSearch(Yii::$app->request->queryParams);

                return $this->render('viewaccountlist', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel
                ]);
            }else {
                Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');

                return $this->goHome();
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionUserlist()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->position === 'admin'){
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()
                ]);

                return $this->render('userlist', ['dataProvider' => $dataProvider]);
            }else {
                Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');

                return $this->goHome();
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }

    public function actionViewtransactionlist()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->position === 'admin'){
                $searchModel = new TransactionSearchForm();
                $dataProvider = $searchModel->transactionSearch(Yii::$app->request->queryParams);

                return $this->render('viewtransactionlist', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel
                ]);
            }else {
                Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');

                return $this->goHome();
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'You do not require the permission to access this page.');
        }
    }
}

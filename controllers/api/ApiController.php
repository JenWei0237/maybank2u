<?php

namespace app\controllers\api;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\filters\auth\QueryParamAuth;

class ApiController extends Controller
{
	public $additional_data = [];

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(),[
			'corsFilter' => [
				'class' => Cors::className(),
			],
			'authenticator'=>[
				'class' => QueryParamAuth::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'*' => ['post','get'],
				],
			],
			'contentNegotiator'=>[
				'class' => ContentNegotiator::className(),
				'formats' => [
					'text/xml' => Response::FORMAT_JSON,
					'application/json' => Response::FORMAT_JSON,
					'text/html' => Response::FORMAT_JSON,
				],
			],
		]);
	}

	public function init()
	{
		Yii::$app->user->enableSession = false;
		Yii::$app->user->loginUrl = null;
		Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
			$response = $event->sender;
			if ($response->data !== null) {
				if ($response->isServerError || $response->isClientError) {
					$data = $response->data;
					$code = $response->statusCode;
					if ($response->statusCode != 401) {
						$response->statusCode = 200;
						$code = $data['code'];
					}
					$response->data = [
						'code' => Yii::$app->errorHandler->exception->getCode(),
						'error' => true,
						'message' => Yii::$app->errorHandler->exception->getMessage(),
						'data' => null
					];
				} else {
					$response->data = array_merge([
						'code' => 200,
						'error' => false,
						'message' => 'OK',
						'data' => $response->data,
					], $this->additional_data);
				}
			}
		});
		Yii::$app->response->format = Response::FORMAT_JSON;
		return parent::init();
	}

	public function throwNewException(\Exception $error, $code=0)
	{
		$error_code = empty($error->statusCode) ? 0 : $error->statusCode;
		switch ($error_code) {
			case 401:
				throw new \yii\web\UnauthorizedHttpException(Yii::t('app', $error->getMessage()));
			default:
				throw new \Exception(Yii::t('app', $error->getMessage()), $code);
		}
	}
}

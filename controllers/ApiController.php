<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;

class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add authenticator filter but exclude 'login' action
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\QueryParamAuth::class,
            'tokenParam' => 'access_token',
            'except' => ['login'], // Skip authentication for login
        ];

        // Optionally add access control if needed
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['logout'],
            'rules' => [
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],  // Only authenticated users can logout
                ],
            ],
        ];

        return $behaviors;
    }
    public function beforeAction($action)
    {
        if ($action->id === 'login') {
            \Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
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


    /**
     * Login action.
     * @return Response|string
     */
    public function actionLogin()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            $user->generateAccessToken(); // Generate a new access token
            return [
                'status' => 'success',
                'access_token' => $user->accessToken
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Invalid username or password'
        ];
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
}

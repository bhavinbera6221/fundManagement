<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use app\models\Users;

class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Only apply token authentication to actions except 'login' and 'forgot-password'
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
            'except' => ['login', 'forgot-password'], // exclude login and forgot password from token validation
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        // Disable CSRF validation for API requests
        if (in_array($action->id, ['login', 'forgot-password','change-password'])) {
            $this->enableCsrfValidation = false;
        }
        // Exclude token check for login and forgot password
        if (in_array($action->id, ['login', 'forgot-password', 'get-users', 'change-password'])) {
            return parent::beforeAction($action);
        }

        // Token validation
        $user = User::findOne(['accessToken' => Yii::$app->request->get('access_token')]);
        if (!$user || $user->access_token_expires_at < time()) {
            throw new \yii\web\UnauthorizedHttpException('Your access token is invalid or expired.');
        }

        Yii::$app->response->statusCode = 200;
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

        // Ensure the request content type is JSON
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $username = $request['username'];
            $password = $request['password'];

            $user = User::findByUsername($username);
            if ($user && $user->validatePassword($password)) {
                $user->generateAccessToken(); // Generate a new access token
                return [
                    'access_token' => $user->accessToken,
                    'expires_at' => $user->access_token_expires_at,
                ];
            }

            Yii::$app->response->statusCode = 401; // Unauthorized
            return [
                'status' => 'FAILED',
                'message' => 'Invalid username or password',
            ];
        }

        // If request is not POST or JSON
        Yii::$app->response->statusCode = 400; // Bad Request
        return [
            'status' => 'FAILED',
            'message' => 'Bad request. Expected JSON POST.',
        ];
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->user->logout();

        return [
            'status' => 'SUCCESS',
            'message' => 'Logged out successfully.',
        ];
    }

    public function actionGetUsers()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $query = Users::find()->where([]);

        // Check if specific parameters are passed and add conditions dynamically
        if (!empty($params['username'])) {
            $query->andWhere(['user_name' => $params['username']]);
        }
        if (!empty($params['status'])) {
            $query->andWhere(['status' => $params['status']]);
        }
        if (!empty($params['email'])) {
            $query->andWhere(['email' => $params['email']]);
        }
        if (!empty($params['mobile'])) {
            $query->andWhere(['mobile' => $params['mobile']]);
        }

        $users = $query->all(); // Execute the query
        if (empty($users)) {
            Yii::$app->response->statusCode = 204; // No Content
            return ['status' => 'FAILED', 'message' => 'No users found.'];
        }

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'mobile' => $user->mobile,
                'email' => $user->email,
                'status' => $user->status,
                'account_number' => $user->account_number,
                'bank_name' => $user->bank_name,
                'branch_name' => $user->branch_name,
                'ifsc_code' => $user->ifsc_code,
            ];
        }

        Yii::$app->response->statusCode = 200; // OK
        return ['status' => 'SUCCESS', 'users' => $data];
    }

    private function generateRandomPassword($length = 8)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    public function actionForgotPassword()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set response format
        $request = Yii::$app->request;

        if ($request->isPost) {
            $email = $request->post('email'); // Get email from POST request
            $user = User::findOne(['email' => $email]); // Replace User with your model

            if ($user) {
                $newPassword = $this->generateRandomPassword(); // Generate a new random password
                $user->password = Yii::$app->security->generatePasswordHash($newPassword); // Hash the password

                if ($user->save()) {
                    // Send the new password to the user's email
                    Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setSubject('Your New Password')
                        ->setTextBody("Your new password is: $newPassword")
                        ->send();

                    Yii::$app->response->statusCode = 200; // OK
                    return ['status' => 'SUCCESS', 'message' => 'A new password has been sent to your email.'];
                }
            }

            Yii::$app->response->statusCode = 400; // Bad Request
            return ['status' => 'FAILED', 'message' => 'Email not found.'];
        }

        Yii::$app->response->statusCode = 400; // Bad Request
        return ['status' => 'FAILED', 'message' => 'Bad request. Expected JSON POST.'];
    }

    public function actionChangePassword()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set response format
        $request = Yii::$app->request;

        if ($request->isPost) {
            $currentPassword = $request->post('currentPassword');
            $newPassword = $request->post('newPassword');

            // Check if passwords are provided
            if (empty($currentPassword) || empty($newPassword)) {
                Yii::$app->response->statusCode = 400; // Bad Request
                return [
                    'status' => 'FAILED',
                    'message' => 'Current password and new password cannot be empty.',
                ];
            }

            // Get the access token from the Authorization header
            $authHeader = Yii::$app->request->headers->get('Authorization');
            if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                Yii::$app->response->statusCode = 401; // Unauthorized
                return [
                    'status' => 'FAILED',
                    'message' => 'Unauthorized. Please provide a valid access token.',
                ];
            }
            $accessToken = $matches[1];

            // Find the user by access token
            $user = User::findOne(['accessToken' => $accessToken]);

            if (!$user) {
                Yii::$app->response->statusCode = 401; // Unauthorized
                return [
                    'status' => 'FAILED',
                    'message' => 'Unauthorized. Please provide a valid access token.',
                ];
            }

            // Validate current password
            if (!$user->validatePassword($currentPassword)) {
                Yii::$app->response->statusCode = 400; // Bad Request
                return [
                    'status' => 'FAILED',
                    'message' => 'Current password is incorrect.',
                ];
            }

            // Validate new password
            if (strlen($newPassword) < 6) {
                Yii::$app->response->statusCode = 400; // Bad Request
                return [
                    'status' => 'FAILED',
                    'message' => 'New password must be at least 6 characters long.',
                ];
            }

            // Update the password
            $user->password = Yii::$app->security->generatePasswordHash($newPassword);
            if ($user->save()) {
                Yii::$app->response->statusCode = 200; // OK
                return [
                    'status' => 'SUCCESS',
                    'message' => 'Password changed successfully.',
                ];
            }

            Yii::$app->response->statusCode = 500; // Internal Server Error
            return [
                'status' => 'FAILED',
                'message' => 'Unable to change password. Please try again later.',
            ];
        }

        Yii::$app->response->statusCode = 400; // Bad Request
        return [
            'status' => 'FAILED',
            'message' => 'Bad request. Expected JSON POST.',
        ];
    }
}

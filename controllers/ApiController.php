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

        // Apply token authentication to all actions except 'login' and 'forgot-password'
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
            'except' => ['login', 'forgot-password'], // exclude login and forgot password from token validation
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        // Disable CSRF validation for API requests (for these actions)
        if (in_array($action->id, ['login', 'forgot-password', 'change-password', 'get-users', 'logout'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Login action.
     * @return Response|string
     */
    public function actionLogin()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $username = $request['username'] ?? null;
            $password = $request['password'] ?? null;

            if (!$username || !$password) {
                Yii::$app->response->statusCode = 400; // Bad Request
                return ['status' => 'FAILED', 'message' => 'Username and password are required.'];
            }

            $user = User::findByUsername($username);
            if ($user && $user->validatePassword($password)) {
                $user->generateAccessToken(); // Generate a new access token
                return [
                    'status' => 'SUCCESS',
                    'data' => [
                        'id' => $user->id,
                        'user_name' => $user->user_name,
                        'password' => $user->password,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'mobile' => $user->mobile,
                        'email' => $user->email,
                        'status' => $user->status,
                        'account_number' => $user->account_number,
                        'bank_name' => $user->bank_name,
                        'branch_name' => $user->branch_name,
                        'ifsc_code' => $user->ifsc_code,
                        'access_token' => $user->accessToken
                    ],
                ];
            }

            Yii::$app->response->statusCode = 401; // Unauthorized
            return ['status' => 'FAILED', 'message' => 'Invalid username or password'];
        }

        Yii::$app->response->statusCode = 400; // Bad Request
        return ['status' => 'FAILED', 'message' => 'Bad request. Expected JSON POST.'];
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Get the access token from the Authorization header
    $authHeader = Yii::$app->request->headers->get('Authorization');
    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $accessToken = $matches[1];

        // Find the user by access token and remove it
        $user = User::findOne(['accessToken' => $accessToken]);
        if ($user) {
            $user->accessToken = null; // Clear the access token
            $user->access_token_expires_at = null; // Clear the expiration date if you have it
            $user->save();
        }
    }

    Yii::$app->user->logout(); // Logout the user
    return ['status' => 'SUCCESS', 'message' => 'Logged out successfully.'];
    }

    /**
     * Get users action.
     * @return array
     */
    public function actionGetUsers()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $params = Yii::$app->request->queryParams;

        $query = Users::find()->where([]);

        if (empty($params['id'])) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'FAILED', 'message' =>"User Id not found!"];
        }
        $query->andWhere(['id' => $params['id']]);
        $users = $query->one();
        if (empty($users)) {
            Yii::$app->response->statusCode = 204; // No Content
            return ['status' => 'FAILED', 'message' => 'No users found.'];
        }
            $data = [
                'id' => $users->id,
                'user_name' => $users->user_name,
                'password' => $users->password,
                'first_name' => $users->first_name,
                'last_name' => $users->last_name,
                'mobile' => $users->mobile,
                'email' => $users->email,
                'status' => $users->status,
                'account_number' => $users->account_number,
                'bank_name' => $users->bank_name,
                'branch_name' => $users->branch_name,
                'ifsc_code' => $users->ifsc_code,
            ];

        return ['status' => 'SUCCESS', 'users' => $data];
    }

    /**
     * Forgot Password action.
     *
     * @return Response
     */
    public function actionForgotPassword()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $email = Yii::$app->request->post('email');
            $user = User::findOne(['email' => $email]);

            if ($user) {
                $newPassword = $this->generateRandomPassword();
                $user->password = Yii::$app->security->generatePasswordHash($newPassword);

                if ($user->save()) {
                    Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setSubject('Your New Password')
                        ->setTextBody("Your new password is: $newPassword")
                        ->send();

                    return ['status' => 'SUCCESS', 'message' => 'A new password has been sent to your email.'];
                }
            }

            Yii::$app->response->statusCode = 400; // Bad Request
            return ['status' => 'FAILED', 'message' => 'Email not found.'];
        }

        Yii::$app->response->statusCode = 400; // Bad Request
        return ['status' => 'FAILED', 'message' => 'Bad request. Expected JSON POST.'];
    }

    /**
     * Change Password action.
     * @return array
     */
    public function actionChangePassword()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $currentPassword = Yii::$app->request->post('currentPassword');
            $newPassword = Yii::$app->request->post('newPassword');

            if (empty($currentPassword) || empty($newPassword)) {
                Yii::$app->response->statusCode = 400; // Bad Request
                return ['status' => 'FAILED', 'message' => 'Current and new passwords are required.'];
            }

            $params = Yii::$app->request->queryParams;

            if (empty($params['id'])) {
                Yii::$app->response->statusCode = 400;
                return ['status' => 'FAILED', 'message' =>"User Id not found!"];
            }
            $user = User::findOne(['id' => $params['id']]);

            if (!$user || !$user->validatePassword($currentPassword)) {
                Yii::$app->response->statusCode = 400; // Bad Request
                return ['status' => 'FAILED', 'message' => 'Current password is incorrect.'];
            }

            if (strlen($newPassword) < 6) {
                return ['status' => 'FAILED', 'message' => 'New password must be at least 6 characters long.'];
            }

            $user->password = Yii::$app->security->generatePasswordHash($newPassword);

            if ($user->save()) {
                return ['status' => 'SUCCESS', 'message' => 'Password changed successfully.'];
            }

            Yii::$app->response->statusCode = 500; // Internal Server Error
            return ['status' => 'FAILED', 'message' => 'Unable to change password. Please try again later.'];
        }

        Yii::$app->response->statusCode = 400; // Bad Request
        return ['status' => 'FAILED', 'message' => 'Bad request. Expected JSON POST.'];
    }

    /**
     * Generates a random password
     * @param int $length
     * @return string
     */
    private function generateRandomPassword($length = 8)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }
}

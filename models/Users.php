<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $user_name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int $mobile
 * @property string $email
 * @property string $password
 * @property string $role
 * @property int $account_number
 * @property string $bank_name
 * @property string $branch_name
 * @property string $ifsc_code
 * @property string $created_at
 * @property string $updated_at
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_name', 'mobile', 'email', 'password', 'role', 'account_number', 'bank_name', 'branch_name', 'ifsc_code'], 'required'],
            [['mobile', 'account_number'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_name', 'first_name', 'last_name', 'email', 'password', 'role', 'bank_name', 'branch_name', 'ifsc_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'User ID',
            'user_name' => 'User Name',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Role',
            'account_number' => 'Account Number',
            'bank_name' => 'Bank Name',
            'branch_name' => 'Branch Name',
            'ifsc_code' => 'Ifsc Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>

<div class="wrapper">
    <?php $form = ActiveForm::begin(); ?>
    <h2>Login</h2>
    <div class="input-field">
        <input type="text" name="user_name" required>
        <label>Enter your email</label>
    </div>
    <div class="input-field">
        <input type="password" name="password" required>
        <label>Enter your password</label>
    </div>
    <!-- <button type="submit">Log In</button> -->
    <?= Html::submitButton('Log In', ['style' => "margin-top:30px"]) ?>
    <?php ActiveForm::end(); ?>
</div>
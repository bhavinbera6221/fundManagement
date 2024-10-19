<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-form">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">User Information</h4>
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form-sample']]); ?>

            <div class="row compact-row">
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'user_name', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'User Name']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'first_name', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'First Name']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'last_name', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Last Name']) ?>
                    </div>
                </div>
            </div>

            <div class="row compact-row">
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'mobile', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Mobile']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'email', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Email']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'password', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->passwordInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Password']) ?>
                    </div>
                </div>
            </div>

            <div class="row compact-row">
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'role', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Role']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'account_number', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Account Number']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'bank_name', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Bank Name']) ?>
                    </div>
                </div>
            </div>

            <div class="row compact-row">
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'branch_name', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'Branch Name']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group compact-form-group">
                        <?= $form->field($model, 'ifsc_code', [
                            'labelOptions' => ['class' => 'compact-form-label'],
                            'template' => '{label}{input}{error}',
                        ])->textInput(['class' => 'form-control compact-form-control', 'placeholder' => 'IFSC Code']) ?>
                    </div>
                </div>
            </div>

            <div class="form-group  compact-form-group button-position"> <!-- Use custom class -->
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success me-2']) ?>
                <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
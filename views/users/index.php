<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;


$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$this->params['active_menu'] = "users";
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Users'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php 
    
    
    // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Bordered table</h4>
                <p class="card-description">Add class <code>.table-bordered</code></p>
                <div class="table-responsive pt-3">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-bordered'], // Apply table-bordered class here
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            'user_name',
                            'first_name',
                            'last_name',
                            'mobile',
                            // Uncomment columns as needed
                            // 'email:email',
                            // 'password',
                            // 'role',
                            // 'account_number',
                            // 'bank_name',
                            // 'branch_name',
                            // 'ifsc_code',
                            // 'created_at',
                            // 'updated_at',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    return Url::to([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>


    <?php Pjax::end(); ?>

</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$this->params['active_menu'] = "users";
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

// Custom CSS to make headers bold and larger
$this->registerCss("
    .table th {
        font-weight: bold;
        font-size: 160px;
    }
");
?>

<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Search Form Rendered Here -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <?= $this->render('_search', ['model' => $searchModel]) ?> <!-- Render the search form -->
        </div>
    </div>

    <?php Pjax::begin(); ?>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <!-- Align the title and button in one row -->
                <div class="d-flex justify-content-end align-items-center mb-0">
    <?= Html::a(Yii::t('app', 'Create Users'), ['create'], ['class' => 'btn btn-success']) ?>
</div>



                <div class="table-responsive pt-3">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-hover table-bordered'], // Table classes
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['style' => 'border-bottom: 2px solid #ddd;'],
                                'contentOptions' => ['style' => 'border-bottom: 1px solid #ddd;'],
                            ],
                            [
                                'attribute' => 'user_name',
                                'headerOptions' => ['style' => 'border-bottom: 2px solid #ddd;'],
                            ],
                            [
                                'attribute' => 'first_name',
                                'headerOptions' => ['style' => 'border-bottom: 2px solid #ddd;'],
                            ],
                            [
                                'attribute' => 'last_name',
                                'headerOptions' => ['style' => 'border-bottom: 2px solid #ddd;'],
                            ],
                            [
                                'attribute' => 'mobile',
                                'headerOptions' => ['style' => 'border-bottom: 2px solid #ddd;'],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'headerOptions' => ['style' => 'border-bottom: 2px solid #ddd; width: 100px;'], // Set fixed width for the column
                                'contentOptions' => ['style' => 'border-bottom: 1px solid #ddd; padding-left: 5px; white-space: nowrap;'], // Add left padding
                                'template' => '{update}{delete}', // No space between buttons
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                            'title' => Yii::t('app', 'Update'),
                                            'class' => 'btn btn-sm btn-warning',
                                            'style' => 'margin-right: 3px;', // Small margin between buttons
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                                            'title' => Yii::t('app', 'Delete'),
                                            'class' => 'btn btn-sm btn-danger',
                                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'data-method' => 'post',
                                        ]);
                                    },
                                ],
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    return Url::to([$action, 'id' => $model->id]);
                                }
                            ]


                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>

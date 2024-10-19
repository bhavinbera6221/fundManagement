<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <div class="container-scroller">
        <?php $this->beginBody() ?>

        <!-- Start Header  -->
        <?= $this->render('header') ?>
        <!-- End -->
        <!-- Start Sidebar  -->
        <?= $this->render('sidebar') ?>
        <!-- End -->

        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="container">
                    <?php if (!empty($this->params['breadcrumbs'])): ?>
                        <?= \yii\bootstrap5\Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                    <?php endif; ?>
                    <?= $content ?>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
    </div>

    <!-- Start Footer  -->
    <?= $this->render('footer') ?>
    <!-- End -->

    <?php $this->endBody() ?>
    </div>
</body>

</html>
<?php $this->endPage() ?>
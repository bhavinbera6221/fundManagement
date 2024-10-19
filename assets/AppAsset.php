<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css', // default CSS
        'css/feather.css',
        'css/themify-icons.css',
        'css/vendor.bundle.base.css',
        'css/font-awesome.min.css',
        'css/materialdesignicons.min.css',
        'css/select.dataTables.min.css',
        'css/style.css',
        // 'css/login.css',
    ];
    public $js = [
        'js/vendor.bundle.base.js',
        'js/dataTables.select.min.js',
        'js/off-canvas.js',
        'js/template.js',
        'js/settings.js',
        'js/jquery.cookie.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}

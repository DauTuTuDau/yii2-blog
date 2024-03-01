<?php
namespace diazoxide\blog\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@vendor/DauTuTuDau/yii2-blog/assets/default';

    public $baseUrl = '@web';

    public $css = [
        'css/style.css',
        'css/bootstrap_custom.css',
        'css/common.css',
    ];

    public $js = [
    ];

    public $depends = [
        BootstrapAsset::class,
        StickySidebarAsset::class,
        FontAwesomeAsset::class,
        FeatherlightAsset::class
    ];
}

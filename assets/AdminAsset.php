<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@vendor/DauTuTuDau/yii2-blog/assets/default';

    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap_custom.css',
        'css/common.css',
    ];

    public $js = [
    ];
    public $depends = [
        BootstrapAsset::class,
        FontAwesomeAsset::class,
        JqueryAsset::class
    ];
}

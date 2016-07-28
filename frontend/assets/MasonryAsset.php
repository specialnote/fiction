<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * 瀑布流插件
 */
class MasonryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        '/js/masonry.pkgd.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = [
        'position' => View::POS_BEGIN,
    ];
}

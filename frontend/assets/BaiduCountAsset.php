<?php
/**
 * 百度统计代码
 */
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class BaiduCountAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/baidu_count.js'
    ];
    public $depends = [
    ];
    public $jsOptions = [
        'position' => View::POS_BEGIN,
    ];
}

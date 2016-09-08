<?php

/* @var $this \frontend\models\view\FrontendView */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
\frontend\assets\JqueryValidateAsset::register($this);

if (YII_ENV === 'prod') {
    \frontend\assets\BaiduCountAsset::register($this);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="sogou_site_verification" content="ATC69gwHz1"/>
    <meta name="description" content="<?= $this->description?>">
    <meta name="keywords" content="<?= $this->keywords?>">
    <link type="image/x-icon" href="<?= \yii\helpers\Url::to("/fiction_note.ico")?>" rel="shortcut icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    $categoryList = $this->categoryList;
    foreach ($categoryList as $category) {
        $menuItems[] = ['label' => Html::encode($category->name), 'url' => "/category/index?id=" . $category->id];
    }

    if (isset($menuItems) && $menuItems) {
        NavBar::begin([
            'brandLabel' => $this->headline,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="row float-right">
            <form action="/category/search" method="get" role="form" class="form-inline" id="search_form">
                <div class="form-group col-xs-12 col-md-12">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="小说名|作者" name="search_key" value="<?= trim(Yii::$app->request->get('search_key'))?>"/>
                        <div class="input-group-addon"><button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button></div>
                    </div>
                </div>
            </form>
        </div>
        <?= $content ?>
    </div>
</div>
<?php if (YII_ENV !== 'prod') {
    ?>
<footer class="footer">
    <div class="container">

    </div>
</footer>
<?php 
} ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

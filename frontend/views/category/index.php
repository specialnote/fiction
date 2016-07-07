<?php
    $this->title = $category['category_name'];
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
?>
<?php
NavBar::begin([
    'brandLabel' =>  Yii::$app->params['company_name'].' - '.$category['category_name'],
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
foreach($categoryList as $category) {
    $menuItems[] = ['label' => Html::encode($category['category_name']), 'url' => "/category/index?dk=$dk&ck=".Html::encode($category['category_key'])];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
?>
<div class="row" id="category_list">
    <?php foreach($fictionList as $v) {?>
        <div class="col-xs-6 col-md-6">
            <a href="/category/detail?dk=<?= $dk?>&ck=<?= $ck?>&url=<?= $v['url']?>"><?= $v['text']?></a>
        </div>
    <?php }?>
</div>
<div class="row col-xs-12 col-md-12">
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
        'maxButtonCount' => 8
    ])?>
</div>

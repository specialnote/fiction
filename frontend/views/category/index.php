<?php
    $this->title = $category['category_name'];
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
?>

<div class="container">
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
</div>
<div class="container">
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
</div>-->

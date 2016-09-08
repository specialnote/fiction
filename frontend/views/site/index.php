<?php
/* @var $this \frontend\models\view\FrontendView */
$company = Yii::$app->params['company_name'];
$categories = \common\models\Category::find()->all();
$categoryNames = \yii\helpers\ArrayHelper::getColumn($categories, 'name');
$categoryNameString = implode('、', $categoryNames);
$this->title = $company . '-' . $company . '首页-' . $company . '官网-小说-' . $categoryNameString . '！';
$this->headline = $company;
$this->description = $this->title;
$this->keywords = $company . ',' . implode(',', $categoryNames);
\frontend\assets\MasonryAsset::register($this);
?>
<style>
    .jumbotron{padding: 0px;margin: 0px;cursor: pointer;}
    .masonry-item h3{font-weight: bolder;}
</style>
<div class="row">
    <div class="">
        <button class="btn btn-primary" type="button">
            热门推荐 <span class="badge"><?= count($fictions)?></span>
        </button>
    </div>
</div>
<div class="row masonry">
    <?php if ($fictions) {
    ?>
        <?php foreach ($fictions as $fiction) {
    ?>
            <div class="col-xs-6 col-md-3 masonry-item" onclick="location.href = '/fic/index?id=<?= $fiction->id ?>'">
                <div class="jumbotron">
                    <h3><?= $fiction->name?></h3>
                    <small>作者 ： <?= $fiction->author?></small>
                    <p style="font-size: 14px;"><?= $fiction->description?></p>
                </div>
            </div>
        <?php 
} ?>
    <?php 
}?>
</div>
<script type="text/javascript">
    $('.masonry').masonry({
        itemSelector: '.masonry-item',
        columnWidth: 0
    });
</script>



<?php
$this->title = Yii::$app->params['company_name'];
$this->headline = Yii::$app->params['company_name'];
\frontend\assets\MasonryAsset::register($this);
?>
<style>
    .jumbotron{padding: 0px;margin: 0px;cursor: pointer;}
    .masonry-item h3{font-weight: bolder;}
</style>
<div class="row masonry">
    <?php if ($fictions) { ?>
        <?php foreach ($fictions as $fiction) { ?>
            <div class="col-xs-6 col-md-3 masonry-item" onclick="location.href = '/fic/index?id=<?= $fiction->id ?>'">
                <div class="jumbotron">
                    <h3><?= $fiction->name?></h3>
                    <small>作者 ： <?= $fiction->author?></small>
                    <p style="font-size: 14px;"><?= $fiction->description?></p>
                </div>
            </div>
        <?php }?>
    <?php }?>
</div>
<script type="text/javascript">
    $('.masonry').masonry({
        itemSelector: '.masonry-item',
        columnWidth: 0
    });
</script>



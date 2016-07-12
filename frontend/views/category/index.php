<?php
    $this->title = $category['category_name'];
    $this->headline = $category['category_name'];
?>

<div class="container">
    <div class="row" id="category_list">
        <?php foreach($fictionList as $v) {?>
            <div class="col-xs-6 col-md-6">
                <a href="/category/detail?dk=<?= $dk?>&url=<?= base64_encode($v['url'])?>"><?= $v['text']?></a>
            </div>
        <?php }?>
    </div>
    <div class="row col-xs-12 col-md-12">
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
            'maxButtonCount' => 6
        ])?>
    </div>
</div>
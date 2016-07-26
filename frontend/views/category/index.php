<?php
    $this->title = $category->name;
    $this->headline = $category->name;
?>

<div class="container">
    <div class="row" id="category_list">
        <?php foreach ($fictionList as $fiction) { ?>
            <div class="col-xs-6 col-md-6 text-center">
                <a href="/fic/index?id=<?= $fiction->id?>"><?= $fiction->name?></a>
            </div>
        <?php }?>
    </div>
    <div class="row" style="margin-bottom: 40px;position: inherit;bottom: 40px;width: 100%;;">
        <div class="col-xs-12 col-md-12 text-center">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
                'maxButtonCount' => 3,
            ])?>
        </div>
    </div>
</div>
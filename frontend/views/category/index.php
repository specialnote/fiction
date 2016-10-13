<?php
$company = Yii::$app->params['company_name'];
$categories = \common\models\Category::find()->all();
$categoryNames = \yii\helpers\ArrayHelper::getColumn($categories, 'name');
$categoryNameString = implode('、', $categoryNames);
$this->title = $category->name . '-' . Yii::$app->params['title'];
$this->headline = $category->name;
$this->description = $this->title;
$this->keywords = $company . ',' . implode(',', $categoryNames);
$this->categoryKey = $category->categoryKey;
$this->categoryName = $category->name;
?>

<div class="container">
    <div class="row">
        <div class="page-header">
            <h3> <?= $category->name?></h3>
        </div>
    </div>
    <?php if ($fictionList) {
    ?>
    <div class="row" id="category_list">
        <ul class="list-group">
        <?php foreach ($fictionList as $fiction) {
    ?>
                <li class="list-group-item col-xs-6 col-md-3">
                    <a href="/fic/index?id=<?= $fiction->id?>">
                        <p><?= $fiction->name?><span class="category_list_author"><?= $fiction->author?></span></p>
                    </a>
                </li>
        <?php 
} ?>
        </ul>
    </div>
    <div class="row" style="margin: 0px;position: inherit;bottom: 40px;width: 100%;;">
        <div class="col-xs-12 col-md-12 text-center">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
                'maxButtonCount' => 3,
                'nextPageLabel' => '下',
                'prevPageLabel' => '上'
            ])?>
        </div>
    </div>
    <?php 
} else {
    ?>
    <div class="row">暂无数据</div>
    <?php 
}?>
</div>
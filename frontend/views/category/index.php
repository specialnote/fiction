<?php
$company = Yii::$app->params['company_name'];
$categories = \common\models\Category::find()->all();
$categoryNames = \yii\helpers\ArrayHelper::getColumn($categories, 'name');
$categoryNameString = implode('、', $categoryNames);
$this->title = $category->name . '-' . $company;
$this->headline = $category->name;
$this->description = $this->title;
$this->keywords = $company . ',' . implode(',', $categoryNames);
?>

<div class="container">
    <div class="row" id="category_list">
        <?php foreach ($fictionList as $fiction) { ?>
            <div class="col-xs-6 col-md-3">
                <a href="/fic/index?id=<?= $fiction->id?>">
                    <p><?= $fiction->name?><span class="category_list_author"><?= $fiction->author?></span></p>
                </a>
            </div>
        <?php }?>
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
</div>
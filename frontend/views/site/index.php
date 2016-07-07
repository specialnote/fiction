<?php
$this->title = Yii::$app->params['company_name'];
use yii\helpers\Html;
?>
<?php if($categoryList) { ?>
    <div class="row">
        <?php foreach($categoryList as $category) { ?>
        <div class="col-xs-3 col-md-3">
            <a href="/category/index?dk=<?= $dk?>&ck=<?= Html::encode($category['category_key'])?>"><?= Html::encode($category['category_name'])?></a>
        </div>
        <?php }?>
    </div>
<?php }?>


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
?>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <button class="btn btn-primary" type="button">
            热门推荐 <span class="badge"><?= count($fictions)?></span>
        </button>
        <button class="btn btn-default float-right" id="site_change"><span class="glyphicon glyphicon-refresh"></span></button>
    </div>
</div>
<div class="row masonry" id="site_body">
    <?= $this->renderFile('@frontend/views/site/_recommend.php', [
        'fictions' => $fictions,
    ])?>
</div>
<script type="text/javascript">
    $('#site_change').click(function () {
        $.post('/site/change',{'_csrf':'<?= Yii::$app->request->csrfToken?>'}, function(html){
            if (html){
                $('#site_body').html(html);
            }
        });
    });
</script>



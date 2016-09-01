<?php
/* @var $this \frontend\models\view\FrontendView */
    use yii\helpers\Html;

    $list = $chapterList;

    if ($list) {
        $sort_list = $list ?: [];
        rsort($sort_list);
        $latest = $sort_list[0];
    } else {
        $sort_list = [];
        $latest = [];
    }
$company = Yii::$app->params['company_name'];
$frontend_host = Yii::$app->params['frontend_host'];
$fk = $fiction->fictionKey;
$title = Html::encode($fiction->name);
$author = Html::encode($fiction->author);
$this->title = $title . '-' . $author . '-最新章节';
$this->headline = $title;
$this->description = $company . '-' . $this->title;
$this->keywords = $company . ',' . $fiction->name . ',' . $fiction->author;
?>
<div class="row" id="list_header">
    <div class="col-xs-12 col-md-12">
        <h3><?= $title?><small style="margin-left: 10px;">作者 ： <?= $author?></small></h3>
    </div>
    <div class="col-xs-12 col-md-12">
        <p class="p_content">
            <?= Html::encode($fiction->description)?>
        </p>
    </div>
    <?php if ($latest) { ?>
    <div class="col-xs-12 col-md-12">
        <p><span class="label label-info">最新章节</span> <a href="/fic/detail?fid=<?= $fiction->id ?>&num=<?= count($list) ?>"><?= $list[count($list) - 1]['text']?></a></p>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default" role="button" id="sold_asc">正序</a>
            <a class="btn btn-default" role="button" id="sold_desc">反序</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <ul class="list-group" id="list_body_asc">
            <?php if($list) { ?>
                <?php foreach ($list as $k => $v) { ?>
                    <li id="f_c_list_<?= $k?>" class="list-group-item" >
                        <a href="/fic/detail?fid=<?= $fiction->id ?>&num=<?= $k + 1 ?>"><?= $v['text']?></a>
                    </li>
                <?php }?>
            <?php } ?>
        </ul>
        <ul class="list-group" id="list_body_desc" style="display: none">
            <?php if($sort_list) { ?>
                <?php foreach ($sort_list as $k => $v) { ?>
                    <li id="f_c_list_<?= (count($sort_list) - $k) ?>" class="list-group-item" >
                        <a href="/fic/detail?fid=<?= $fiction->id ?>&num=<?= (count($sort_list) - $k) ?>"><?= $v['text']?></a>
                    </li>
                <?php }?>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12" id="list_footer">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a href="#list_header" class="btn btn-default" role="button">返回顶部</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var asc= $("#list_body_asc");
        var desc = $("#list_body_desc");
        $('#sold_asc').click(function () {
            asc.show();
            desc.hide()
        });
        $('#sold_desc').click(function () {
            asc.hide();
            desc.show()
        });

        $.get('/fic/update?id=<?= $fiction->id ?>');
    });
</script>
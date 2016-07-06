<?php
    $this->title = $fiction['fiction_name'] . '-最新目录';
    use yii\helpers\Html;

    $sort_list = $list;
    rsort($sort_list);
    $frontend_host = Yii::$app->params['frontend_host'];
?>
<div class="row" id="list_header">
    <div class="col-xs-12 col-md-12">
        <h3><?= Html::encode($fiction['fiction_name'])?><small>作者 ： <?= Html::encode($fiction['fiction_author'])?></small></h3>
    </div>
    <div class="col-xs-12 col-md-12">
        <p class="p_content">
            <?= Html::encode($fiction['fiction_introduction'])?>
        </p>
    </div>
    <div class="col-xs-12 col-md-12">
        <p><span class="label label-info">最新章节:</span> <a href="/fic/detail?dk=<?= $dk?>&fk=<?= $fk?>&url=<?= $list[count($list) - 1]['url'] ?>&text=<?= $list[count($list) - 1]['text']?>"><?= $list[count($list) - 1]['text']?></a></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default" role="button" id="sold_asc">正序</a>
            <a class="btn btn-default" role="button" style="width: 5%">全部章节目录</a>
            <a class="btn btn-default" role="button" id="sold_desc">反序</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <ul class="list-group" id="list_body"></ul>
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

    var list = <?= json_encode($list) ?>;
    var reset_list = <?= json_encode($sort_list)?>;

    function getHtml(list) {
        var html = '';
        if (list.length > 0) {
            for (var i = 0; i < list.length; i++) {
                var target = 'f_c_list_' + i;
                html = html + '<li class="list-group-item" id="' + target + '"><a href="/fic/detail?dk=<?= $dk?>&fk=<?= $fk?>&url=' + list[i]['url'] + '&text=' + list[i]['text'] + '">' + list[i]['text'] + '</a></li>';
            }
        } else {
            html = html + '<li class="list-group-item">暂无数据</li>';
        }
        $('#list_body').html(html);
    }

    $(function () {
        getHtml(list);

        $('#sold_asc').click(function () {
            getHtml(list);
        });
        $('#sold_desc').click(function () {
            getHtml(reset_list);
        });
    });
</script>


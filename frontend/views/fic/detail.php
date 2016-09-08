<?php
$company = Yii::$app->params['company_name'];
$this->title = $fiction->name . '-' . $detail['text'];
$this->headline = $fiction->name;
$this->description = $company . '-' . $this->title;
$this->keywords = $company . ',' . $fiction->name . ',' . $fiction->author . ',' . $detail['text'];
?>
<div class="row">
    <h2><?= $fiction->name?><span class="small"><?= $fiction->author?></span></h2>

    <?php $rate = min(100, ceil($num / $fiction->getChapterCount() * 100)); ?>
    <div class="progress">
        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $rate ?>%;">
            <?= $rate ?>%
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-md-6">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default font_small" role="button">-</a>
            <a class="btn btn-default font_big" role="button">+</a>
            <a class="btn btn-default" id="close_light" role="button">关灯</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12 text-center">
        <?= $detail['text']?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default detail_prev_url" role="button">上一章</a>
            <a class="btn btn-default" role="button" style="width: 2%" href="/fic/index?id=<?= $fiction->id ?>#<?= 'f_c_list_'.$num?>">返回目录</a>
            <a class="btn btn-default detail_next_url" role="button">下一章</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12" id="fiction_detail">
        <?= $detail['detail']?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default detail_prev_url" role="button">上一章</a>
            <a class="btn btn-default" role="button" style="width: 2%" href="/fic/index?id=<?= $fiction->id ?>#<?= 'f_c_list_'.$num?>">返回目录</a>
            <a class="btn btn-default detail_next_url" role="button">下一章</a>
        </div>
    </div>
</div>
<script>
    var content = $("#fiction_detail");

    $('.font_small').bind('click', function () {
        var size = parseInt(content.css('font-size'));
        if (size > 12) {
            content.css('font-size', size - 1);
        }
    });
    $('.font_big').bind('click', function () {
        var size = parseInt(content.css('font-size'));
        if (size < 30) {
            content.css('font-size', size + 1);
        }
    });
    $('#close_light').click(function () {
        $('body').toggleClass('close_light_style');
    });

    //获取上一章、下一章链接
    $.get('/fic/pn?fid=<?= $fiction->id ?>&num='+<?= $num ?>, function (data) {
        var prev = $('.detail_prev_url');
        var next = $('.detail_next_url');
        if (data.prev) {
            prev.attr('href', '/fic/detail?fid=<?= $fiction->id?>&num=' + data.prev);
        } else {
            prev.css('background', '#ccc');
        }
        if (data.next) {
            next.attr('href', '/fic/detail?fid=<?= $fiction->id?>&num=' + data.next);
        } else {
            next.css({'background': '#ccc', 'border': '1px solid #ccc'});
        }
    });

    //缓存当前详情
    $.get('/fic/cache?fid=<?= $fiction->id ?>&num='+<?= $num ?>);
    //缓存下一章
    $.get('/fic/cache?fid=<?= $fiction->id ?>&num='+<?= $num + 1 ?>);
</script>


<?php
    $this->title = $fiction['fiction_name'] . '-' . $text;

    use yii\helpers\Html;
?>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default" role="button" href="/fic/detail?dk=<?= $dk?>&hk=<?= $hk?>&url=<?= $prev?>&">上一章</a>
            <a class="btn btn-default font_small" role="button">-</a>
            <a class="btn btn-default" role="button" style="width: 2%" href="/fic/list?dk=<?= $dk?>&fk=<?= $fk?>">章节目录</a>
            <a class="btn btn-default font_big" role="button">+</a>
            <a class="btn btn-default" role="button">下一章</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12" id="fiction_detail">
        <?= $content?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default" role="button">上一章</a>
            <a class="btn btn-default font_small" role="button">-</a>
            <a class="btn btn-default" role="button" style="width: 2%" href="/fic/list?dk=<?= $dk?>&fk=<?= $fk?>">章节目录</a>
            <a class="btn btn-default font_big" role="button">+</a>
            <a class="btn btn-default" role="button">下一章</a>
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
        if (size < 20) {
            content.css('font-size', size + 1);
        }
    });
</script>


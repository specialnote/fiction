<?php
    $this->title = $fiction['fiction_name'];
    use yii\helpers\Html;
    use yii\grid\GridView;
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
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
            <a class="btn btn-default" role="button" id="sold_asc">正序</a>
            <a class="btn btn-default" role="button">最新章节目录</a>
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
            <a href="#" class="btn btn-default" role="button">反序</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    var list = <?= json_encode($list) ?>;
    var reset_list = <?= json_encode($list)?>;

    function getHtml(list){
        var html = '';
        if (list.length > 0) {
            for (var i =0; i < list.length; i++){
                html = html + '<li class="list-group-item"><a href="' + list[i]['href'] + '">' + list[i]['text'] + '</a></li>';
            }
        } else {
            html = html + '<li class="list-group-item">暂无数据</li>';
        }
        $('#list_body').html(html);
    }

    $(function(){
        getHtml(list);

        $('#sold_asc').click(function(){
            getHtml(list);
        });
        $('#sold_desc').click(function(){
            getHtml(reset_list);
        });
    });
</script>


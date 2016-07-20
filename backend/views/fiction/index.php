<?php
    $this->title = '列表';

    use yii\helpers\Html;
    use common\models\Fiction;

    ?>
<div class="row">
    <table class="table table-striped">
        <tr>
            <th>渠道</th>
            <th>名称</th>
            <th>作者</th>
            <th>列表状态</th>
            <th>详情状态</th>
        </tr>
        <?php foreach ($ditches as $ditch) {
    ?>
            <?php foreach ($ditch['fiction_detail'] as $list) {
    ?>
                <tr>
                    <td><?= Html::encode($ditch['ditch_name'])?></td>
                    <td><?= Html::encode($list['fiction_name'])?></td>
                    <td><?= Html::encode($list['fiction_author'])?></td>
                    <?php $running =  Fiction::isFictionRunning($ditch['ditch_key'], $list['fiction_key']); ?>
                    <td><?= $running !== 0 ? '<span style="color: green;" class="glyphicon glyphicon-ok"></span>' : '<span style="color:red;" class="glyphicon glyphicon-remove"></span>'?></td>
                    <td><?= $running === 20 ? '<span style="color: green;" class="glyphicon glyphicon-ok"></span>' : '<span style="color:red;" class="glyphicon glyphicon-remove"></span>'?></td>
                </tr>
            <?php 
} ?>
        <?php 
}?>
    </table>
</div>


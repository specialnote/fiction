<?php
    use yii\helpers\Url;

?>
<?php foreach ($fictions as $fiction) {
    ?>
    <div class="col-xs-12 col-md-6 masonry-item" onclick="location.href = '/fic/index?id=<?= $fiction->id ?>'">
        <div class="jumbotron">
           <div class="float-left">
               <img width="120px" height="150px" src="<?= $fiction->imgUrl ? $fiction->imgUrl : Url::to('/images/default.svg', true)?>">
           </div>
            <div class="float-right text-left site_fic_list_right">
                <div>
                    <h3><?= $fiction->name?></h3>
                    <small>作者 ： <b><?= $fiction->author?></b></small>
                    <p><?= $fiction->description?></p>
                </div>
            </div>
        </div>
    </div>
<?php 
} ?>

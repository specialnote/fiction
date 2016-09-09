<?php foreach ($fictions as $fiction) {
    ?>
    <div class="col-xs-6 col-md-3 masonry-item" onclick="location.href = '/fic/index?id=<?= $fiction->id ?>'">
        <div class="jumbotron">
            <h3><?= $fiction->name?></h3>
            <small>作者 ： <b><?= $fiction->author?></b></small>
            <p><?= $fiction->description?></p>
        </div>
    </div>
<?php 
} ?>

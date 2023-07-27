<?php
$data = new Parse($_GET['url']);
$images = $data->parseUrl();
?>
    <div class="image">
        <?php foreach ($images as $img) { ?>
            <img class="image-block" src="<?php echo $img; ?>">
            <?php
        } ?>
    </div>
    <div class="text">
        <h3>Количество фотогрфай: <?php echo count($images) ?>, общим размером на <?php echo $data->allSize ?>Мб</h3>
    </div>
    </div><?php

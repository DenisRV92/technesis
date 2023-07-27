<?php
require_once './Parse/Parse.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>test</title>
</head>
<body>
<div class="container">

    <?php

    require_once './view/form_view.php';

    if (isset($_GET['url'])) {
    require './View/imges_view.php';
    ?>

</div>
<?php }
?>

</body>
</html>

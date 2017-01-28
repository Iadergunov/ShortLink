<?php
$loader = require_once __DIR__.'/vendor/autoload.php';
use MyClasses\Link;
$received_link = $_POST['link'];
$link = new Link($received_link);
$link->store();
?>

<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <title>ShortLink</title>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <?php
        $test = $_GET['link'];
        echo "<p>Ваша ссылка: <a href='http://wlp.icescroll.ru/$link->short_link'>wlp.icescroll.ru/$link->short_link</a></p>"
        ?>
    </div>
</div>
</body>
</html>
<?php
$loader = require_once __DIR__.'/vendor/autoload.php';
use MyClasses\Link;
$received_link = $_POST['link'];
$link = new Link($received_link);
$link->store();
echo "<br>";
echo $link->short_link;
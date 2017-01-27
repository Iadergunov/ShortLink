<?php
$loader = require_once __DIR__.'/vendor/autoload.php';
use MyClasses\Link;
$received_link = $_POST['link'];
$received_link = $_GET['link'];
$link = new Link($received_link);
echo $link->link_url;
echo "<br>";
echo $link->hash_link;
$link->store();
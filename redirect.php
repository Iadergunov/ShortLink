<?php
$loader = require_once __DIR__.'/vendor/autoload.php';
use MyClasses\Link;

if (isset($_GET['link'])) {
    $received_short_link = trim($_GET['link']);
    $link_url = Link::getShortLink($received_short_link);
    Link::redirect($link_url);
}
else{
    //Перенаправляем на главную, если пользователь просто попал на эту страницу
    Header('Location: http://wlp.icescroll.ru/');
}
<?php

namespace MyClasses;

class Link
{
    public $link_url;
    public $hash_link;
    public $short_link;
    public $id;

    public function __construct($link_url)
    {
        $this->link_url = $link_url;
        $this->hash_link =  md5($link_url);
    }

    public function store(){
        $mysqli = db_connection::connect_default();
        //Осуществляем проверку на наличие записи, в целях избежания дублирования информации
        if (!$this->linkExist($mysqli)){
            $this->storeNewLink($mysqli);
            $this->getLastId($mysqli);
            $this->short_link = $this->createShortLink($this->id);
            $this->storeShortLink($mysqli);
        }
    }

    /**
     * На основе id ссылки формируем короткую ссылку
     * @param $id
     * @return string
     */
    public function createShortLink($id){
        $availableValues = '';
        for ($i=0; $i<10; $i++){
            $availableValues .= $i;
        }
        for ($i=0; $i<26; $i++) {
            //$availableValues .= chr($i+65);
            $availableValues .=chr($i+97);
        }
        $shortLink = '';
        do{
            $modulo = $id%36;
            $shortLink .= $availableValues[$modulo];
            $id = floor($id/36);
        } while($id!=0);
        return $shortLink;
    }

    public function setLinkId(){

    }

    /**
     * Получаем последний id в базе и присваиваем его текущему объекту
     * @param $mysqli
     */
    public function getLastId($mysqli){
        $result = $mysqli->query('SELECT MAX(id) from short_link');
        $row = $result->fetch_row();
        $this->id = $row[0];
    }

    /**
     * Проверяем была ли получена из базы ссылка, если получена перенаправляем по ссылке
     * @param $link_url
     */
    public static function redirect($link_url){
        if (isset($link_url)){
            Header('Status: 301 Moved Permanently');
            Header('Location: http://'.$link_url);
            die();
        }
        else{
            die("<p>К сожалению, такой ссылки не существует</p>");
        }
    }

    /**
     * Сохраняем "короткую" ссылку в базе
     * @param $mysqli
     */
    public function storeShortLink($mysqli){
        if (!($stmt = $mysqli->prepare("UPDATE short_link SET short_link = ? WHERE id = ?"))) {
            echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("si", $this->short_link, $this->id)) {
            echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    /**
     * Сохраняем новую полученную ссылку в базе
     * @param $mysqli
     */
    public function storeNewLink($mysqli){
        if (!($stmt = $mysqli->prepare("INSERT INTO short_link(link_url, link_hash) VALUES (?, ?)"))) {
            echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ss", $this->link_url, $this->hash_link)) {
            echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    /**
     * Получаем изначальную ссылку по короткой ссылке
     * @param $received_short_link
     * @return mixed
     */
    public static function getShortLink($received_short_link){
        $mysqli = db_connection::connect_default();
        if (!($stmt = $mysqli->prepare("SELECT link_url FROM short_link WHERE short_link = ?"))) {
            echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $received_short_link)) {
            echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row['link_url'];
    }

    /**
     * Проверяем по хешу есть ли уже ссылка в базе данных, если есть, то присваиваем короткую ссылку текущему объекту.
     * @param $mysqli
     * @return mixed
     */
    public function linkExist($mysqli){
        if (!($stmt = $mysqli->prepare("SELECT short_link FROM short_link WHERE link_hash = ?"))) {
            echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $this->hash_link)) {
            echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if (isset($row['short_link'])){
            $this->short_link = $row['short_link'];
            return true;
        }
        else {
            return false;
        }
    }
}
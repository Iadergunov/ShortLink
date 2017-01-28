<?php

namespace MyClasses;
//use MyClasses\db_connection;

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
        $this->storeNewLink($mysqli);
        $this->getLastId($mysqli);
        $this->short_link = $this->createShortLink($this->id);
        $this->storeShortLink($mysqli);

        //Закрываем соединение с базой
        //die(json_encode($send_data));
        //die();
    }

    /**
     * На основе id ссылки формируем короткую ссылку
     * @param $id
     * @return string
     */
    public function createShortLink($id){
        $availableValues = '';
        //$domain = 'http://wlp.icescroll.ru';
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
        //$fullShortLink = $domain . '/' . $shortLink;
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

    public static function checkShortLink($short_link){

        die();
    }
}
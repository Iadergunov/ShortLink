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
        if (!($stmt = $mysqli->prepare("INSERT INTO short_link(link_url, link_hash) VALUES (?, ?)"))) {
            echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ss", $this->link_url, $this->hash_link)) {
            echo "Не удалось привязать параметры: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
        }
        //Get id last inserted task
        //$result = $mysqli->query('SELECT MAX(id) from tasks');
        //$row = $result->fetch_row();
        //$send_data = array(
        //    "id" => $row[0],
        //);
        //close connection and send encoded id
        //die(json_encode($send_data));
        die();
    }

    public function getShortLink(){

    }
}
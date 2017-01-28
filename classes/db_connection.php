<?php
namespace MyClasses;
class db_connection
{
    private $host = 'localhost';
    private $db_name = 'wlp';
    private $db_user = 'wlp';
    private $db_password = 'Dxriprkz';

    public function __construct($host, $db_name, $db_user, $db_password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
    }

    public function connect(){
        $mysqli = new \mysqli($this->host, $this->db_user, $this->db_password, $this->db_name);
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        return $mysqli;
    }

    public static function connect_default(){
        $connection = new db_connection('localhost', 'wlp', 'wlp', 'Dxriprkz');
        $mysqli = $connection->connect();
        return $mysqli;
    }
}
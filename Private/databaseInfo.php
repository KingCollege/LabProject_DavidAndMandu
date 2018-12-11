<?php 
    define("DB_SERVER", "localhost");
    define("DB_USER", "webuser");
    define("DB_PASS", "password");
    define("DB_NAME", "GameSociety");
    $connection = NULL;
    function connectToDB(){
        global $connection;
        $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }
    }
?>
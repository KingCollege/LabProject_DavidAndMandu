<?php 
    define("DB_SERVER", "fdb24.awardspace.net");
    define("DB_USER", "2906760_db");
    define("DB_PASS", "davidmanduseglab64!");
    define("DB_NAME", "2906760_db");
    $connection = NULL;
    function connectToDB(){
        global $connection;
        $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }
    }
?>
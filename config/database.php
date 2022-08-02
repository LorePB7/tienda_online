<?php

class Database {

    private $hostname = "us-cdbr-east-06.cleardb.net";
    private $database = "heroku_e636b290235b28a";
    private $username = "bb328a19db9945";
    private $password = "9ce3221c";
    private $charset = "utf8";

    function conectar() {
        try{
        $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database ."; charset=" . $this->charset;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $pdo = new PDO($conexion, $this->username, $this->password, $options);

        return $pdo;
    } catch(PDOException $e){
        echo 'Error conexion' . $e->getMessage();
        exit;
    }
    }
}

?>
<?php
session_start();

// config.php
$host = 'localhost';
$dbname = 'solirestaurant';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getLastIdClient() {
    global $pdo;
        $sql = "SELECT MAX(idClient) AS maxId FROM client";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['maxId'])) {
            $MaxId = 0;
        } else {
            $MaxId = $result['maxId'];
        }
        return $MaxId;
    }
    
    function tel_existe($tel){
        global $pdo;
        $sql = "SELECT * FROM client where telCl=:tel";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tel', $tel);
        $stmt->execute();
        $rusult = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rusult;
    }


    function getPlatsByType($type){
        global $pdo;
        $sql = "SELECT * FROM plat WHERE TypeCuisine=:type";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        $rusult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rusult;
    }
    
    function getCuisineType(){
        global $pdo;
        $sql = "SELECT distinct TypeCuisine  FROM plat ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rusult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rusult;
    }
?>

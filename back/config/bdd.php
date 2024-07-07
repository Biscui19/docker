<?php 
try{
    $bdd = new PDO('mysql:host=db;dbname=FootKit', 'user', 'root');
} catch (PDOException $e) {
    die('Erreur PDO :' . $e->getMessage());
}
?>

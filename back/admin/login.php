<?php
session_start();

require "/var/www/html/config/bdd.php";

$erreur = '';

if (isset($_POST['connexion'])){
    $mail = htmlspecialchars($_POST['mail']);
    $mdp = $_POST['mdp'];

    $connect = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = :mail");
    $connect->bindValue('mail', $mail, PDO::PARAM_STR);
    $userexist = $connect->execute();
    $userexist = $connect->fetch();

    if (empty($mail) || empty($mdp)) $erreur = 'Vous devez remplir tous les champs';
    if (empty($userexist)) $erreur = 'Vous n\'êtes pas inscrit';

    if (isset($userexist['mot_de_passe'])) {
        $mdpcrypt = $userexist['mot_de_passe'];
        if (!password_verify($mdp, $mdpcrypt)) $erreur = 'Mot de passe incorrect';
    }

    if (empty($erreur)) {
        $_SESSION['pseudo'] = $userexist['pseudo'];
        $_SESSION['admin'] = $userexist['admin'];
        if ($_SESSION['admin'] == 1) {
            header("Location: http://localhost:8080/backoffice.php");
        } else {
            header("Location: http://localhost:8080/index.php");
        }
    } else {
      
        header("Location: http://localhost:8080/connexion.php?erreur=" . urlencode($erreur));
        exit();
    }
}


<?php
require "/var/www/html/config/bdd.php";

$erreurpseudo = '';
$erreurmdp = '';
$erreurmdpconf = '';
$erreurmail = '';

if (isset($_POST['inscription'])) {

    $pseudo = $_POST['pseudo'];    
    $email = $_POST['mail'];
    $mdp = $_POST['mdp'];
    $mdpconf = $_POST['mdpconf'];

    $mdplength = strlen($mdp);
    $pseudolength = strlen($pseudo);

    $mdphash = password_hash($mdp, PASSWORD_DEFAULT);

    if ($pseudolength >= 55 || $pseudolength <= 2) {
        $erreurpseudo = "Votre pseudo doit contenir entre 2 et 55 caractères";
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_])(?=.{8,})/', $mdp)) {
        $erreurmdp = "Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule et 1 caractère spécial.";
    }
    if ($mdp != $mdpconf) {
        $erreurmdpconf = "Vos mots de passe ne correspondent pas.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurmail = "Votre adresse mail n'est pas valide.";
    }

    $reqmail = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $reqmail->execute(array($email));
    $mailexistant = $reqmail->rowCount();

    if ($mailexistant > 0) {
        $erreurmail = "Adresse mail déjà utilisée !";
    }

    if (empty($erreurpseudo) && empty($erreurmdp) && empty($erreurmdpconf) && empty($erreurmail)) {
        $insertmbr = $bdd->prepare("INSERT INTO utilisateurs(pseudo, email, mot_de_passe) VALUES(?, ?, ?)");
        $insertmbr->execute(array($pseudo, $email, $mdphash));
        
        header("Location: http://localhost:8080/index.php?inscription=success");
        session_destroy();
        exit();
    } else {
        
        header("Location: http://localhost:8080/inscription.php?erreurpseudo=" . urlencode($erreurpseudo) . "&erreurmdp=" . urlencode($erreurmdp) . "&erreurmdpconf=" . urlencode($erreurmdpconf) . "&erreurmail=" . urlencode($erreurmail));
        exit();
    }
}


<?php

    require("../config/bdd.php");

    $nomMaillot = $cheminImage = '';
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nomMaillot = $_POST['nom_maillot'];

        if (isset($_FILES['image_maillot']) && $_FILES['image_maillot']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = './maillot/';
            $uploadFile = $uploadDir . basename($_FILES['image_maillot']['name']);
            
            if (move_uploaded_file($_FILES['image_maillot']['tmp_name'], $uploadFile)) {
                $cheminImage = $uploadFile;

                $sql = "INSERT INTO maillots (nom, image) VALUES (:nom, :cheminImage)";
                $stmt = $bdd->prepare($sql);
                $stmt->bindParam(':nom', $nomMaillot, PDO::PARAM_STR);
                $stmt->bindParam(':cheminImage', $cheminImage, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $message = "Le maillot a été ajouté avec succès.";
                } else {
                    $message = "Erreur lors de l'ajout du maillot : " . $stmt->errorInfo()[2];
                }
            } else {
                $message = "Erreur lors du téléversement de l'image.";
            }
        } else {
            $message = "Veuillez sélectionner une image.";
        }
        header("Location: http://localhost:8080/backoffice.php?message=" . urlencode($message));
        exit();
    }


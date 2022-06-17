<?php
    //inclure et exécuter le fichier config.php en argument comme include
    require 'config.php';

    //Démarre une nouvelle session ou reprend une session existante
    session_start();

    //!isset = Détermine si une variable est déclarée et est null
    if (!isset($_SESSION["user_session"])) {
        //redirection vers la page de connexion index.php
        header("Location:index.php");
    }

    // Get connected user data
    $user_id = $_SESSION["user_session"];
    $sql = "SELECT * FROM users WHERE user_id = $user_id";


    // Envoyer la requête au serveur
    $reponse = $con->query($sql);
    // On récupère le résultat
    $user = $reponse->fetch(PDO::FETCH_ASSOC);
    // $uname = $_SESSION["user_session_login"];
?>
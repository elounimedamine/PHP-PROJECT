<?php
  try
  {
    // Connexion à la base de données
    $con = new PDO('mysql:host=localhost;dbname=base_de_données_projet;charset=utf8' , 'root' , '');
  } catch (Exception $e)
  {
    die('Erreur de connexion au serveur.' . $e->getMessage());
  }
?>
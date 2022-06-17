 <?php
 //Démarre une nouvelle session ou reprend une session existante
 session_start();
  //Si l'utilisateur n'est pas connecté on le redirige vers la page de connexion
  //Il suffit de tester si la variable session $_SESSION['user_session'] existe ou non
  if (isset($_SESSION["user_session"])) {
	  // On déconnecte l'utilisateur => Destruction de la session
    session_destroy();

    //Détruit la variable
    unset($_SESSION["user_session"]);
  }
  
	//Puis on le redirige vers la page de connexion
  header('Location:index.php');
?>
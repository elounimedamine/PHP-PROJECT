<?php
  //inclut la configuration de la base de données et les fonctions de teste des inputs
  include 'config.php';
  include 'functions.php';
  //Démarre une nouvelle session ou reprend une session existante
  session_start();
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sign In Form</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="icon-font/lineicons.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/CSS.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <?php
        //Détermine si une variable n'est vide
        if (!empty($_POST)) {
            // Récupérer le contenu du formulaire avec le test avec la fonction test_input
            $uname = test_input($_POST['txt_uname_email']);
            $umail = test_input($_POST['txt_uname_email']);
            $upass = test_input($_POST['txt_password']);

            //prepare => Prépare une requête à l'exécution et retourne un objet
            $req = $con->prepare("SELECT * FROM users
                        WHERE user_name = :uname
                        OR user_email = :umail LIMIT 1");
            
            //bindParam => Lie un paramètre à un nom de variable spécifique
            $req->bindParam(":uname", $uname);
            $req->bindParam(":umail", $umail);
            // Exécuter une requête préparée
            $req->execute();

            // On récupère le résultat
            $user = $req->fetch(PDO::FETCH_ASSOC);

            //On vérifie son mot de passe saisie avec celui enregistré dans la base de données
            // $2y$10$WC9HAn6HJpQWWS8862BFOObfOPbbRL7E7hgGbQl.A8c0mJScYX6ee === test12
                if (password_verify($upass, $user['user_pass'])){ 
                    //$user = variable de résultat de requete et user_pass de la BD 
                    // Si le mot de passe existe
                    // alors on lui crée une session
                    
                    // $_SESSION => tab associatif contient les ids des utilisateurs
                    $_SESSION["user_session"] = $user['user_id'];
                    // $_SESSION["user_session_login"] = $user['user_name'];
                    // Redirection
                    header("Location:admin_page.php");
            } else  {
                // Sinon on affiche un message d'erreur
                $error = "Informations erronnées !"; 
            }
        }
    ?>
    <div class="content">
      <div class="text">Sign In</div>
            <form method="post" id="login-form">
                <div id="error">
                    <?php
                    //Si la variable $error existe on l'affiche
                    //Détermine si une variable est déclarée et est différente de null
                    if (isset($error)) {
                        ?>
                            <div class="alert alert-danger">
                                <i class="lni lni-warning"></i> <?php echo $error ?> 
                            </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="field">
                    <input type="text" class="form-control" name="txt_uname_email" placeholder="Login ou E-mail" required />
                    <span class="bx bxs-user"></span>
                </div>
                <div class="field">
                    <input type="password" class="form-control" name="txt_password" placeholder="mot de passe" required />
                    <span class="bx bxs-lock-alt"></span>
                </div>
                <div class="button">
                    <button type="submit" name="btn-login">
                        <i class="lni lni-enter"></i> Connexion
                    </button>
                </div>
                <br>
                <div class="foot">
                     <a>You don't have an account?</a>
                     <a class="in" href="sign-up.php">Sign Up</a>
                </div>
                
            </form>

        </div>
    </div>
    <div class="drak-light">
          <i class="bx bx-moon moon"></i>
          <i class="bx bx-sun sun"></i>
    </div>

<script>
//retourne le premier Element dans le document correspondant au sélecteur - ou groupe de sélecteurs - spécifié(s), ou null si aucune correspondance n'est trouvée.
const body = document.querySelector("body"),
modeToggle = document.querySelector(".drak-light");

//Attache une fonction à appeler chaque fois que l'évènement spécifié est envoyé à la cible
modeToggle.addEventListener("click" ,() =>{
    modeToggle.classList.toggle("active");
    body.classList.toggle("dark");
    if(!body.classList.contains("dark"))
    {
        localStorage.setItem("mode", "light-mode");
    }
    else
    {
        localStorage.setItem("mode", "dark-mode");
    }  
})
</script>  
</body>
</html>
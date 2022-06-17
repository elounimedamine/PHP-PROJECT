<?php
  //inclut la configuration de la base de données et les fonctions de teste des inputs
  include 'config.php';
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up Form</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="icon-font/lineicons.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/CSS.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php
    // Récupérer le contenu du formulaire d'inscription
    if (!empty($_POST)) {
        //récupération des informations du formulaire
        // la fonction  trim() permet de supprimer les espaces avant et après un texte
        $uname = trim($_POST['txt_uname']);
        $umail = trim($_POST['txt_umail']);
        $upass = trim($_POST['txt_upass']);

        //Remplissage des messages d'erreurs dans un tableau
        $errors = [];
        $valid = true;

        if ($uname == "") {    // Vérifier username
            array_push($errors, "Vous devez saisir un nom d'utilisateur!");
            $valid = false;
        }

        if ($umail == "") {   // Vérifier email
            array_push($errors, "Vous devez saisir un email");
            $valid = false;
            //Filtre une variable avec un filtre spécifique 
            //FILTER_VALIDATE_EMAIL pour vérifier l'email
        } else if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Vous devez saisir un email valide");
            $valid = false;
        }

        if ($upass == "") {    // Vérifier mot de passe
            array_push($errors, "Vous devez saisir un mot de passe");
            $valid = false;
            //strlen => longueur
        } else if (strlen($upass) < 6) {
            array_push($errors, "Le mot de passe doit avoir au moins 6 caractères");
            $valid = false;
        }

        // Il n'y a pas d'erreurs
        // ON recherche si l'utilisateur existe déjà dans la base
        // La recherche se fait par username ou email
        // Si valid == true
        if ($valid) {
            // Requête SQL
            $sql = "SELECT * FROM users
                    WHERE user_name = '$uname'
                    OR user_email = '$umail'";

            // Envoyer la requête au serveur et récupérer le résultat
            //query => Prépare et Exécute une requête SQL sans marque substitutive
            $reponse = $con->query($sql);
            
            //On récupère le résultat
            //fetch => Récupère la ligne suivante d'un jeu de résultats PDO
            $user = $reponse->fetch(PDO::FETCH_ASSOC);

            //Si l'utilisateur existe on prépare les messages d'erreurs
            if ($user['user_name'] == $uname) {
                array_push($errors, "Désolé, le nom d'utilisateur existe déjà !");
            } else if ($user['user_email'] == $umail) {
                array_push($errors, "Désolé, l'email existe déjà !");
            } else {
                //si l'utilisateur n'existe pas alors on l'enregistre dans la BD

                    // hasher le password
                    //Utilisation de l'algorithme bcrypt => PASSWORD_DEFAULT
                    $new_password = password_hash($upass, PASSWORD_DEFAULT);
                    // On prépare la requête
                    $sql = "INSERT INTO users (user_name, user_email, user_pass)
                            VALUES ('$uname', '$umail', '$new_password')";
                    // Envoi et exécution de la requête
                    // exec => Exécute un programme externe
                    $res = $con->exec($sql);
                    // Si l'insertion est effectuée avec succès
                    // On redérige l'utilisateur vers la page de login (connexion)
                    if ($res) {
                        header('Location:index.php');
                    }
            }
        }
    }
    ?>
    <div class="content">
      <div class="text">Sign Up</div>
           <form method="post" id="login-form">
                <?php
                // S'il existe des messages d'erreurs, on les affiches
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">';
                    foreach ($errors as $error) {
                        echo '<p><i class="lni lni-warning"></i> ' . $error . '</p>';
                    }
                    echo '</div>';
                }
                ?>
                <div class="field">
                    <input type="text" class="form-control" name="txt_uname" placeholder="Votre nom d'utilisateur" required />
                    <span class="bx bxs-user"></span>
                </div>
                <div class="field">
                    <input type="text" class="form-control" name="txt_umail" placeholder="Votre E-Mail" required />
                    <span class="bx bxs-envelope"></span>
                </div>
                <div class="field">
                <span class="bx bxs-lock-alt"></span>
                    <input type="password" class="form-control" name="txt_upass" placeholder="Votre mot de passe" required />
                    
                </div>
                <div class="clearfix"></div>
                <div class="button">
                    <button type="submit" name="btn-login">
                        <i class="lni lni-enter"></i> S'inscrire
                    </button>
                </div>
                <br>
                <div class="foot">
                     <a>Already have an account?</a>
                     <a class="in" href="index.php">Sign In</a>
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
    //les icons comme user et email soit light soit dark qq soit le mode
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
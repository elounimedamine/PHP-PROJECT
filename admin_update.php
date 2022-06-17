<?php
//inclure et exécuter le fichier config.php en argument(elle est identique à include)
  require 'session.php';
?>

<?php
//inclure et exécuter le fichier config.php en argument
@include 'config.php';

//récupération de l'id de produit qu'on va les modifier à travers le bouton edit du tableau
$id = $_GET['edit'];

//vérifiez si'il y'a une variable est déclarée et est différente de null
//l'état du bouton nommée update_product
if(isset($_POST['update_product'])){ 
   //récupération des informations du formulaire
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   // $_FILES = Variable de téléchargement de fichier via HTTP
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   //on va uploder les images insérées dans le dossier nommée uploaded_img selon leur nom
   $product_image_folder = 'uploaded_img/'.$product_image;

   //Si les 3 champs son vide, on affiche un message d'erreur
   if(empty($product_name) || empty($product_price) || empty($product_image)){
      $message[] = 'Insérer les données nécessaires!';    
   }else{
       //sinon, on va mettre à jour les données qui sont insérées déja 
      $update_data = "UPDATE products SET name='$product_name', price=$product_price, image='$product_image' WHERE id = '$id'";
      // Envoyer la requête au serveur et récupérer le résultat
      $update = $con->exec($update_data);

      //si la requete est bien executée
      if($update){
          //Déplace le fichier téléchargé dans le dossier uploaded_img/ et afficher un message de succès
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         //redirection vers la page admin_page.php
         header('location:admin_page.php');
      }else{
          //sinon, on affiche un message d'erreur
         $message[] = 'Insérer les données nécessaires!'; 
      }

   }
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/style1.css">
</head>
<body background="i5.jpg">

<?php
//on va afficher le message correspondant dans chaque cas
   if(isset($message)){
      foreach($message as $message){
         echo '<span class="message">'.$message.'</span>';
      }
   }
?>

<div class="container">


<div class="admin-product-form-container centered">

   <?php

      $select = "SELECT * FROM products WHERE id = '$id'";

      // Envoyer la requête
      $reponse = $con->query($select);

      //On récupère le résultat
      $produits = $reponse->fetchAll(PDO::FETCH_ASSOC);

   ?>
   <!--on affiche les informations insérées dans un tableau-->
   <?php foreach ($produits as $produit) : ?>
   <form action="" method="post" enctype="multipart/form-data">
      <h3 class="title">update the product</h3>
      <input type="text" class="box" name="product_name" value="<?php echo $produit['name']; ?>" placeholder="Enter The Product Name">
      <input type="number" min="0" class="box" name="product_price" value="<?php echo $produit['price']; ?>" placeholder="Enter The Product Price">
      <input type="file" class="box" name="product_image"  accept="image/png, image/jpeg, image/jpg">
      <input type="submit" value="update product" name="update_product" class="btn">
      <a href="admin_page.php" class="btn">go back!</a>
   </form>

   <?php endforeach; ?>

</div>

</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
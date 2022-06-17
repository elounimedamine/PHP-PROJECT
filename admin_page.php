<?php
//inclure et exécuter le fichier config.php en argument(elle est identique à include)
  require 'session.php';
?>

<?php
//inclure et exécuter le fichier config.php en argument
@include 'config.php';

//vérifiez si'il y'a une variable est déclarée et est différente de null
if(isset($_POST['add_product'])){
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
       //sinon, on va insérer un nouveau produit 
      $insert = "INSERT INTO products(name, price, image) VALUES('$product_name', $product_price, '$product_image')";
      // Envoyer la requête
      $upload = $con->exec($insert);
      //si la requete est bien executée
      if($upload){
          //Déplace le fichier téléchargé dans le dossier uploaded_img/ et afficher un message de succès
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         $message[] = 'Produit ajoutée avec succès';
      }else{
          //sinon, on affiche un message d'erreur
         $message[] = "Produit n'est pas ajoutée";
      }
   }

};

//$_GET => Variables HTTP GET
if(isset($_GET['delete'])){
    //on va récupérer l'id du produit à supprimer à travers le bouton delete du tableau
   $id = $_GET['delete'];
   $delete = "DELETE FROM products WHERE id = $id";
   // Envoyer la requête
   $supprimer = $con->exec($delete);
   //redirection vers la page admin_page.php
   header('location:admin_page.php');
};

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Page d'Acceuil</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style1.css">

</head>
<body background="i5.jpg">
<div class="admin-product-form-container">
  <div class="topnav">
    <a class="active" href="logout.php"><h1 style="text-align: right">Déconnexion</h1></a>
  </div>
</div>


<?php
//on va afficher le message correspondant dans chaque cas
if(isset($message)){
   foreach($message as $message){
      echo '<span class="message">'.$message.'</span>';
   }
}

?>
   
<div class="container">

   <div class="admin-product-form-container">
       <!--$_SERVER — Variables de serveur et d'exécution-->
       <!--Le format des données transmises est le même que celui qu’utiliserait la méthode submit() du formulaire pour envoyer les données si l’encodage de ce dernier était défini sur multipart/form-data-->
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
         <h3>add a new product</h3>
         <input type="text" placeholder="Enter Product Name" name="product_name" class="box">
         <input type="number" placeholder="Enter Product Price" name="product_price" class="box">
         <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
         <input type="submit" class="btn" name="add_product" value="add product">
      </form>

   </div>

   <?php
   
   $select = "SELECT * FROM products";
   // Envoyer la requête
   $reponse = $con->query($select);
   //On récupère le résultat
   $produits = $reponse->fetchAll(PDO::FETCH_ASSOC);
   
   ?>
   <div class="product-display">
      <table class="product-display-table">
         <thead>
         <tr>
            <th>product image</th>
            <th>product name</th>
            <th>product price</th>
            <th>action</th>
         </tr>
         </thead>
         <!--on affiche les informations insérées dans un tableau-->
         <?php foreach ($produits as $produit) : ?>
         <tr>
             <!--on va afficher les images insérées appartie du dossier uploaded_img/-->
            <td><img src="uploaded_img/<?php echo $produit['image']; ?>" height="100" alt=""></td>
            <td><?php echo $produit['name']; ?></td>
            <td><?php echo $produit['price']; ?> DT</td>
            <td>
               <a href="admin_update.php?edit=<?php echo $produit['id']; ?>" class="btn"> <i class="fas fa-edit"></i> edit </a>
               <a href="admin_page.php?delete=<?php echo $produit['id']; ?>" class="btn"> <i class="fas fa-trash"></i> delete </a>
            </td>
         </tr>
         <?php endforeach; ?>
      </table>
   </div>

</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
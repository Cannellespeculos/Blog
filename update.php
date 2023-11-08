<?php
        session_start();

        if (!isset($_SESSION['login'])) {
            header('Location:login.php');
        }else{
            try{
                include("connection.php");
                $sql = "INSERT INTO Blog (titre, commentaire, image, Date, Id_auteur) VALUES (:titre,:commentaire,:image,:date, :Id_auteur)";
                $resultat = $base->prepare($sql);
                date_default_timezone_set('Europe/Paris');
                
                
               
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        #sub {
            width: 20%;
        }

        label:nth-child(2) {
            display: flex;
            flex-direction: column;
        }

        a {
            margin-top: 50px;
        }


        
    </style>

    <form action="update.php" enctype="multipart/form-data" method="POST">
        <label for="titre">Titre : <input type="text" name="titre" id="titre"></label>
        <label for="commentaire">Commentaire : <textarea name="commentaire" id="coo" cols="30" rows="10"></textarea></label>
        <input type="file" name="img" id="img">
        <input type="submit" value="Créer" id="sub">
    </form>

    <?php
         if (isset($_POST['titre']) && isset($_POST['commentaire']) && isset($_FILES['img']) ) {
            $img = $_FILES['img'];
            $titre = htmlspecialchars($_POST['titre']);
            $commentaire = htmlspecialchars($_POST['commentaire']);
            $date = date("Y-m-d H:i:s");
            $name = basename($img["name"]);
            $hashed_name = hash("md5", $name);
            $hasUploaded = move_uploaded_file($img["tmp_name"],"./photo/$hashed_name");
            if ($hasUploaded) {

                $resultat->execute(array("titre" =>  $titre, "commentaire" => $commentaire, "image" => $hashed_name, "date" => $date, "Id_auteur" => $_SESSION["id"]));
            echo "Publication créer.";
            
            $resultat->closeCursor();
            }elseif($_SESSION["Id_publication"]){
                $delete = "DELETE FROM blog WHERE Id_publication = :Id_publication";
                $resultA = $base->prepare($delete);
                $resultA->execute(array("Id_publication" => $_SESSION["Id_publication"]));
                $sq = "INSERT INTO Blog (titre, commentaire, image, Date, Id_auteur) VALUES (:titre,:commentaire,:image,:date, :Id_auteur)";
                $result = $base->prepare($sq);
                $result->execute(array("titre" =>  $titre, "commentaire" => $commentaire, "image" => $hashed_name, "date" => $date, "Id_auteur" => $_SESSION["id"]));
            }
            
            }else {
                echo "</br>Informations manquantes";
            }
        }
       
    Catch(Exception $e){
    // message en cas d’erreur
    die("Erreur : ".$e->getMessage());
    }
}

    ?>

    <a href="affich_update.php">Affichage des postes</a>

    
</body>
</html>
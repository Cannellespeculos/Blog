<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>Commentaires</h1>
    </header>

    <?php
        try
        {
            session_start();
        include("connection.php");
        $sql = "SELECT com_content, com_date FROM commentaire JOIN blog ON commentaire.Id_publ = blog.Id_publication WHERE Id_publication = :publId ";
        $resultat = $base->prepare($sql);
        $resultat->execute(array("publId" => $_SESSION["id_publication"]));
        while ($ligne = $resultat->fetch()) {
            echo $ligne["com_content"];
        }
        $resultat->closeCursor();
        }
        Catch(Exception $e)
        {
        // message en cas d’erreur
        die("Erreur : ".$e->getMessage());
        }
    ?>

</body>
</html>
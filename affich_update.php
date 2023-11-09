<?php
try {
    session_start();
    include("connection.php");
    if (isset($_POST["suppr"])) {
        $delete = "DELETE FROM blog WHERE Id_publication = :id AND Id_auteur = :authorId";
        $result = $base->prepare($delete);
        $result->execute(array("authorId" => $_SESSION["id"], "id" => $_POST["postId"]));
        $URL = "affich_update.php";
        header("Location:affich_update.php");
    } elseif (isset($_POST["modif"])) {
        $URL = "update.php";
        $_SESSION["id_publication"] = $ligne["Id_publication"];
        header("Location:update.php");
    }
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
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            header {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100px;
            }

            article {
                display: flex;
                flex-direction: column;
                width: 100%;
                /* height: 200px; */
            }

            img {
                width: 50%;
                height: fit-content;
            }

            hr {
                border-color: gray;
            }

            #red {
                background-color: #FE6847;
                color: white;
                margin: 15px;
                border: none;
                border-radius: 5px;
                padding: 10px 5px;
            }

            #yellow {
                background-color: #f7ef81;
                color: black;
                margin: 15px;
                border: none;
                border-radius: 5px;
                padding: 10px 5px;
            }
        </style>

        <header>
            <h1>BLOG</h1>
            <a href="update.php">Création de postes</a>
            <?php
            if (!isset($_SESSION['login'])) {
                echo "vous n'êtes pas connecté";
            } else {
                echo "Bienvenue " . $_SESSION["login"];
            }


            ?>
        </header>

        <main>
            <?php

            $sql = "SELECT p.Id_publication, p.titre, p.commentaire, p.image, p.Date, a.login FROM blog p JOIN login_password a ON p.Id_auteur = a.Id ORDER BY Date DESC";
            $resultat = $base->prepare($sql);
            $resultat->execute();
            while ($ligne = $resultat->fetch()) {
                echo "<article>";
                echo "<h2>" . $ligne['titre'] . "</h2>";
                echo "<p>" . date("Y-m-d H:i:s", strtotime($ligne['Date'])) . "</p>";
                echo "<p>" . $ligne['commentaire'] . "</p>";
                echo "<img src='./photo/" . $ligne['image'] . "'></img>";
                echo "<p>" . $ligne["login"] . "</p>";

                if (isset($_SESSION["login"])) {
                    if ($ligne["login"] === $_SESSION["login"]) {
                        $id = $ligne["Id_publication"];
                        echo "<form action='affich_update.php' method='POST'>";
                        echo "<input type='hidden' value='$id' name='postId'>";
                        echo "<button id='red' name='suppr'>SUPPRIMER</button>";
                        echo "<button id='yellow' name='modif'>MODIFIER</button>";
                        echo "</form>";


                    }
                }

                echo "</form>";
                echo "</article>";
                echo "<hr>";



            }
            $resultat->closeCursor();
} catch (Exception $e) {
    // message en cas d'erreur
    die('Erreur : ' . $e->getMessage());
}

?>
    </main>


</body>

</html>
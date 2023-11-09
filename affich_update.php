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
        $_SESSION["id_publication"] = $_POST["postId"];
        header("Location:update.php");
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src="https://kit.fontawesome.com/6a2b59470e.js" crossorigin="anonymous"></script>
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

            nav {
                width: 100%;
            }

            ul {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                gap: 20px;
                border: 1px solid grey;
            }

            li {
                list-style: none;
                padding: 15px 5px;
            }

            a {
                text-decoration: none;
                color: black;
            }

            /* i {
               
            } */

            article a {
                display: flex;
                align-self: flex-end;
                margin: 10px 20px;
                padding: 10px;
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
            
            <?php
            if (!isset($_SESSION['login'])) {
                echo "vous n'êtes pas connecté";
                echo "<nav>";
                echo "<ul>";
                echo "<li>";
                echo "<a href='login.php'>Se connecter</a>";
                echo "</li>";
                echo "<li>";
                echo "<a href='signup.php'>Créer un compte</a>";
                echo "</li>";
                echo "</ul>";
                echo " </nav>";
            } else {
                echo "Bienvenue " . $_SESSION["login"];
                echo "<nav>";
                echo "<ul>";
                echo "<li>";
                echo "<a href='update.php'>Création de poste</a>";
                echo "</li>";
                echo "<li>";
                echo "<a href='#'>Votre Compte</a>";
                echo "</li>";
                echo "</ul>";
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
                    
                    echo "<a href='comment.php'><i class='fa-solid fa-comments'></i></a>";

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
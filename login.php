<?php
    session_start();
    $error = null;

    try {        
        if (isset($_POST["login"]) && isset( $_POST["password"])) {
            include("connection.php");
            $sql = "SELECT Id , login, password FROM login_password WHERE login = :login AND password = :password";
            $resultat = $base->prepare($sql);
            $has = $resultat->execute(array("login" => $_POST["login"],"password"=> $_POST["password"]));
            if ($has) {
                $row = $resultat->fetch();
                if (isset($row)) {
                    $_SESSION["id"] = $row["Id"];
                    $_SESSION["login"] = $_POST["login"];
                    header("Location:affich_update.php");
                } else {
                    $error = "invalid";
                }
            }
        }
    } catch (Exception $e) {
        die('Erreur : '.$e->getMessage());
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
    <form action="login.php" method="POST">
    <label for="login">Login : <input type="text" name="login" id="login"></label><br>
    <label for="password">Password : <input type="password" name="password" id="password"></label><br>
    <input type="submit" value="Envoyer">
    </form>

    <a href="signup.php">Vous n'avez pas de compte ? </a>
   
    <?php
    if(isset($error)) {
        echo '<div>'.$error.'</div>';
    }
    ?>
    
</body>
</html>
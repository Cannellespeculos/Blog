<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="signup.php" method="POST">
    <label for="signup">Signup : <input type="text" name="signup" id="login"></label><br>
    <label for="password">Password : <input type="password" name="password" id="password"></label><br>
    <input type="submit" value="Envoyer">
    </form>

    <a href="login.php">Vous avez déjà créer un compte ? </a>

    <?php 
        try {
            session_start();
            include("connection.php");
            $sql = "INSERT INTO login_password (login, password) VALUE (:login, :password)";
            $resultat = $base->prepare($sql);
            

            if (isset($_POST['signup'])  && isset($_POST['password'])) {
                $signup = htmlspecialchars($_POST['signup']);
                $password = htmlspecialchars($_POST['password']);
                $has =  $resultat->execute(array("login" =>  $signup, "password" => $password ));
                $last = $base->lastInsertId();
                if($has && $resultat->rowCount() > 0) {
                    $_SESSION['id'] = $last;
                    $_SESSION["login"] = $signup;
                   header("Location:affich_update.php");
                }
            }

            

        } catch (Exception $e){
        die('Erreur : '.$e->getMessage());
        }
    ?>
</body>
</html>
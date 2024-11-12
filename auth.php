<?php

session_start();
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // // Informations de la base de données
    // $nom_serveur = "localhost";
    // $utilisateur = "user";
    // $mot_de_passe = "1234";
    // $nom_base_donnees = "projet2024";

    require('config/connexion.php');

    // Connexion à la base de données    
    $con = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['name']);
    // $con = mysqli_connect($nom_serveur, $utilisateur, $mot_de_passe, $nom_base_donnees);

    // Vérifier la connexion
    if (!$con) {
        die("Échec de la connexion à la base de données : " . mysqli_connect_error());
    }

    // Requête pour vérifier le login et le mot de passe
    $req = mysqli_query($con, "SELECT ua.*, ur.name as role_name, ur.description as role_description 
                               FROM useraccount ua
                               INNER JOIN userrole ur ON ua.userRoleId = ur.id
                               WHERE ua.login = '$login' AND ua.password = '$password'");
    
    if ($req && mysqli_num_rows($req) > 0) {
        // Récupérer les informations de l'utilisateur et du rôle
        $user_data = mysqli_fetch_assoc($req);
        
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['login'] = $user_data['login'];
        $_SESSION['firstname'] = $user_data['firstname'];
        $_SESSION['lastname'] = $user_data['lastname'];
        $_SESSION['role_name'] = $user_data['role_name'];
        $_SESSION['role_description'] = $user_data['role_description'];

        // Redirection vers la page de bienvenue
        header("Location: bienvenu.php");
        exit();
    } else {
        // Message d'erreur si les informations sont incorrectes
        $erreur = "Login ou mot de passe incorrect !";
    }

    mysqli_close($con);
    // $con->close();
    //TEST TK
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">

    <title>Authentification</title>
</head>
<body>
    <main>
        <section class="container">
            <?php
            if (isset($erreur)) {
                echo "<p style='color: red;'>$erreur</p>";
            }
            ?>
            <a href="login.php">Retour à la page de connexion</a>
        </section>
    </main>


</body>
</html>




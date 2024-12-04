<?php

session_start();  //démarrer une nouvelle session
if (isset($_POST['login']) && isset($_POST['password'])) {
    //Le 'isset' permet de vérifier si une variable est définie est n'est pas nulle. Ici il est utilisé pour vérifier si le formulaire a bien envoyé des données via POST et pour vérifier si $erreur contient un message d'erreur avant de l'afficher.
    //Donc ici on vérifie si les champs 'login' et 'password' on été soumis via le formulaire
    $login = $_POST['login'];
    $password = $_POST['password'];
    //On récupère les données soumises dans les variables $login et $password

    //connexion à la base de donnée
    require('db/connexion.php');

    // Vérifier la connexion
    if (!$con) {
        die("Échec de la connexion à la base de données : " . mysqli_connect_error());
        //On vérifie si la connexion a échoué
    }

    // Requête pour vérifier le login et le mot de passe
    $req = mysqli_query($con, "SELECT ua.*, ur.name as role_name, ur.description as role_description 
                               FROM useraccount ua
                               INNER JOIN userrole ur ON ua.userRoleId = ur.id
                               WHERE ua.login = '$login' AND ua.password = '$password'");
    
    if ($req && mysqli_num_rows($req) > 0) {
        // Récupérer les informations de l'utilisateur et du rôle

        $user_data = mysqli_fetch_assoc($req);
        // On récupère les données de l'utilisateur dans la session

        // On stocker les informations de l'utilisateur dans la session
        $_SESSION['login'] = $user_data['login'];
        $_SESSION['firstname'] = $user_data['firstname'];
        $_SESSION['lastname'] = $user_data['lastname'];
        $_SESSION['role_name'] = $user_data['role_name'];
        $_SESSION['role_description'] = $user_data['role_description'];

        // Redirection vers la page de bienvenue en cas de succès
        header("Location: bienvenue.php");
        exit(); //Le 'exit()' est appelé pour arrêter l'execution du script après la redirection vers la page 'bienvenue.php'
    } else {
        // Message d'erreur si les informations sont incorrectes et que la connexion échoue
        $erreur = "Login ou mot de passe incorrect !";
    }

    mysqli_close($con);
    //fermeture de la connexion à la base de données.
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <!-- On inclut une page de style externe -->

    <title>Authentification</title>
</head>
<body>
    <main>
        <section class="container">
            <?php
            if (isset($erreur)) {
                echo "<p style='color: red;'>$erreur</p>";
                //si on rencontre une erreur, le message d'erreur sera affiché en rouge
            }
            ?>
            <a href="login.php">Retour à la page de connexion</a>
            <!-- lien pour retoruner à la page de connexion -->
        </section>
    </main>
</body>
</html>



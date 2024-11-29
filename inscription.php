<?php
// Connexion à la base de données avec mysqli
require('db/connexion.php');

// Créer la connexion avec mysqli
$mysqli = new mysqli('localhost', 'user', '1234', 'projet2024');

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Traitement des données du formulaire et on vérifie si le formulaire a été soumis via une requette POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // htmlspecialchars() sert à convertir les caractères spéciaux en entités HTML  
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $userRoleId = intval($_POST['userRoleId']); // Convertir la valeur en un entier pour éviter les erreur de type

    

    // Validation des champs s'ils sont tous remplis
    if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($login) && !empty($password) && !empty($userRoleId)) {
        // Vérifier si l'email existe déjà
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM useraccount WHERE email = ?");
        $stmt->bind_param('s', $email); // 's' signifie que c'est un paramètre de type string
        $stmt->execute(); //execute la requête SQL  
        $stmt->bind_result($emailExists); //on associe le résultat à la variable $emailExists
        $stmt->fetch(); //on récupère la valeur du résultat 
        $stmt->close(); //on ferme la requête 

        // on vérifie si l'email est déjà utilisé 
        if ($emailExists > 0) {
            echo "Cet email est déjà utilisé.";  //si oui message d'erreur à l'utilisateur
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); //on utilise "BCRYPT" pour le hashage

            // Insérer les données dans la table useraccount
            $insertQuery = "INSERT INTO useraccount (userRoleId, login, firstname, lastname, email, password) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insertQuery); //préparer la requête 
            $stmt->bind_param('isssss', $userRoleId, $login, $firstname, $lastname, $email, $hashed_password);
            //on associe les valeurs aux paramètres 

            //on execute la reuquête d'insertion 
            if ($stmt->execute()) {
                echo "<h3>Inscription réussie !</h3>";  //message de succès à l'utilisateur 
            } else {
                echo "Erreur SQL : " . $stmt->error; //message d'erreur si l'erreur échou 
            }
            $stmt->close(); //on ferme la requête préparée  
        }
    } else {
        echo "Veuillez remplir tous les champs.";  //messages d'erreur si on ne remplie pas tout les champs 
    }
} else {
    echo "Accès non autorisé.";  //message d'erreur si la page est éccédée sans formulaire 
}

// Fermer la connexion mysqli
$mysqli->close();  
?>

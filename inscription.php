<?php
// Connexion à la base de données avec mysqli
require('db/connexion.php');

// Créer la connexion avec mysqli
$mysqli = new mysqli('localhost', 'user', '1234', 'projet2024');

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Traitement des données du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $userRoleId = intval($_POST['userRoleId']); // Convertir en entier

    // Affichage des données pour le débogage
    // echo "<pre>";
    // print_r([
    //     'userRoleId' => $userRoleId,
    //     'login' => $login,
    //     'firstname' => $firstname,
    //     'lastname' => $lastname,
    //     'email' => $email,
    //     'password' => $password
    // ]);
    // echo "</pre>";

    // Validation des champs
    if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($login) && !empty($password) && !empty($userRoleId)) {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM useraccount WHERE email = ?");
        $stmt->bind_param('s', $email); // 's' signifie que c'est un paramètre de type string
        $stmt->execute();
        $stmt->bind_result($emailExists);
        $stmt->fetch();
        $stmt->close();

        if ($emailExists > 0) {
            echo "Cet email est déjà utilisé.";
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insérer les données dans la table useraccount
            $insertQuery = "INSERT INTO useraccount (userRoleId, login, firstname, lastname, email, password) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insertQuery);
            $stmt->bind_param('isssss', $userRoleId, $login, $firstname, $lastname, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<h3>Inscription réussie !</h3>";
            } else {
                echo "Erreur SQL : " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
} else {
    echo "Accès non autorisé.";
}

// Fermer la connexion mysqli
$mysqli->close();
?>



<?php
// Connexion à la base de données
require('config/connexion.php');

// Traitement des données du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $userRoleId = intval($_POST['userRoleId']);

    // Validation des champs
    if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($login) && !empty($password) && !empty($userRoleId)) {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM useraccount WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            echo "Cet email est déjà utilisé.";
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insérer les données dans la table useraccount
            $insertQuery = "INSERT INTO useraccount (userRoleId, login, firstname, lastname, email, password) 
                            VALUES (:userRoleId, :login, :firstname, :lastname, :email, :password)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindParam(':userRoleId', $userRoleId);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                echo "<h3>Inscription réussie !</h3>";
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
} else {
    echo "Accès non autorisé.";
}


?>


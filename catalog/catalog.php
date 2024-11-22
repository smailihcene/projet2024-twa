<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');


// $query = "SELECT * FROM catalog";
$rep = mysqli_query($con, "SELECT * FROM catalog");

if (!$rep) {
    die("Erreur lors de la récupération des catalogues : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Catalogue</title>
</head>
<body>
    <header>
        <h1>Bienvenue, <?php echo ($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> !</h1>
    </header>
    <main>
        <section class="container">
            <p>Votre rôle : <?php echo ($_SESSION['role_name']); ?></p>

            <h2>Catalogue</h2>
            <table border="2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($catalog = mysqli_fetch_assoc($rep)) { ?>
                        <tr>
                            <td><?php echo ($catalog['id']); ?></td>
                            <td><?php echo ($catalog['name']); ?></td>
                            <td><?php echo ($catalog['description']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </section>
    </main>
</body>
</html>
<?php

mysqli_close($con);
?>

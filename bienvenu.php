<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Bienvenue, 
            <?php echo ($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> !</h1>
    </header>
    <main>
        <section class="container">
            <p>Vous êtes connecté en tant que 
                <?php echo ($_SESSION['role_name'] . " et sa description " . $_SESSION['role_description']); ?>.</p>
            <!-- <a href="logout.php" class="btn">Déconnexion</a> -->
            <a href="catalog/catalog.php" class="btn">Affichage des catalogue</a>
        </section>
    </main>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>
<?php $varchemin = "./catalog/catalog.php" ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- inclure la navbar -->
    <?php include 'navbar.php'; ?>
    <header>
        <h1>Bienvenue,
            <?php echo ($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> !</h1>
    </header>
    <main>
        <section class="container">
            <p>Vous êtes connecté en tant que
                <?php echo ($_SESSION['role_name'] . " et sa description " . $_SESSION['role_description']); ?>.</p>
            <!-- <a href="logout.php" class="btn">Déconnexion</a> -->
            <div class="row">
                <!--<div class="col mb-3">
                    <a href="logout.php" class="btn btn-primary w-100">Déconnexion</a>
                </div>-->
                <div class="col mb-3">
                    <a href="catalog/catalog.php" class="btn btn-primary w-100">Affichage des catalogues</a>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
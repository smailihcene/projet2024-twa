<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');

$rep = mysqli_query($con, "SELECT * FROM catalog");
if (!$rep) {
    die("Erreur lors de la récupération des catalogues : " . mysqli_error($con));
}
?>
<!-- inclure la navbar -->
     <?php include "../navbar.php"; ?>
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
            <h2>Catalogue</h2>
            <?php if ($_SESSION['role_name'] === 'editor') { ?>
            <a href="ajouter_catalog.php">Ajouter</a>
            <?php } ?>
            <table border="2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <?php if ($_SESSION['role_name'] === 'editor') { ?>
                        <th>Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($catalog = mysqli_fetch_assoc($rep)) { ?>
                        <tr>
                            <td><?php echo ($catalog['id']); ?></td>
                            <td><?php echo ($catalog['name']); ?></td>
                            <td><?php echo ($catalog['description']); ?></td>
                            <?php if ($_SESSION['role_name'] === 'editor') { ?>
                            <td>
                                <a href="editer_catalog.php?id=<?php echo $catalog['id']; ?>">Modifier</a>
                                <a href="supprimer_catalog.php?id=<?php echo $catalog['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce catalogue ?')">Supprimer</a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
<?php mysqli_close($con); ?>

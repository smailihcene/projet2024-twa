<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM catalog WHERE id = $id";
    $result = mysqli_query($con, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("Catalogue introuvable.");
    }

    $catalog = mysqli_fetch_assoc($result);
} else {
    header("Location: catalog.php");
    exit();
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    
    $updateQuery = "UPDATE catalog SET name = '$name', description = '$description' WHERE id = $id";
    if (mysqli_query($con, $updateQuery)) {
        header("Location: catalog.php");
        exit();
    } else {
        die("Erreur lors de la mise à jour : " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Modifier un Catalogue</title>
</head>
<body>
    <header>
        <h1>Modifier le Catalogue</h1>
    </header>
    <main>
        <form action="editer_catalog.php?id=<?php echo $catalog['id']; ?>" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($catalog['name']); ?>" required>
            <br>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($catalog['description']); ?></textarea>
            <br>
            <button type="submit">Mettre à jour</button>
        </form>
    </main>
</body>
</html>
<?php mysqli_close($con); ?>

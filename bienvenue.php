<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>

<!-- Inclure la navbar -->
<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Bienvenue</title>
    <style>
        body {
            background-color: #f4f6f9; /* Fond doux */
        }
        h1 span {
            color: #3498db; /* Couleur primaire pour le nom */
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9; /* Survol bouton */
            border-color: #2980b9;
        }
        .text-muted {
            font-size: 1.1rem;
        }
    </style>
</head>

<body>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="mb-4">Bienvenue, <span><?php echo ($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?></span> !</h1>
                <p class="lead">Vous êtes connecté en tant que <strong><?php echo $_SESSION['role_name']; ?></strong> avec le rôle suivant :</p>
                <p class="text-muted"><?php echo $_SESSION['role_description']; ?></p>
            </div>
        </div>

        <!-- Bouton action -->
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <a href="catalog/catalog.php" class="btn btn-primary w-100">
                    <i class="bi bi-folder2"></i> Afficher les catalogues
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-keTAXebTbo6G6Ie4UlwwU9q65sn3NtOva4paEDJVDs/6GZRm7dWXK56vylHcjl6k" crossorigin="anonymous"></script>
</body>

</html>

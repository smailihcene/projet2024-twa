<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('db/connexion.php');

// Récupération des catalogues
$query_catalogs = "
    SELECT c.id AS catalog_id, c.name AS catalog_name, c.description AS catalog_description,
           b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name
    FROM catalog AS c
    LEFT JOIN CatalogImage AS ci ON c.id = ci.catalogId
    LEFT JOIN image AS i ON ci.imageId = i.id
    LEFT JOIN bank AS b ON i.bankId = b.id
    ORDER BY c.id, i.id";
$result_catalogs = mysqli_query($con, $query_catalogs);

if (!$result_catalogs) {
    die("Erreur lors de la récupération des catalogues : " . mysqli_error($con));
}

// Organisation des catalogues
$catalogs = [];
while ($row = mysqli_fetch_assoc($result_catalogs)) {
    $catalog_id = $row['catalog_id'];
    if (!isset($catalogs[$catalog_id])) {
        $catalogs[$catalog_id] = [
            'name' => htmlspecialchars($row['catalog_name']),
            'description' => htmlspecialchars($row['catalog_description']),
            'images' => []
        ];
    }
    if ($row['image_name']) {
        $catalogs[$catalog_id]['images'][] = [
            'bank_dir' => htmlspecialchars($row['bank_dir']),
            'image_name' => htmlspecialchars($row['image_name']),
            'bank_name' => htmlspecialchars($row['bank_name'])
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .welcome-header {
            text-align: center;
            margin: 50px 0;
        }
        .carousel {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
</header>

<main>
    <!-- Section de bienvenue -->
    <section class="container welcome-header">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> !</h1>
        <p class="mt-3">Vous êtes connecté en tant que
            <strong><?= htmlspecialchars($_SESSION['role_name']); ?></strong>.
        </p>
        <p>Description de votre rôle :
            <em><?= htmlspecialchars($_SESSION['role_description']); ?></em>.
        </p>
    </section>

    <!-- Section Carousel des catalogues -->
    <section class="container">
        <h2 class="text-center">Carousel des Catalogues</h2>

        <?php if (empty($catalogs)): ?>
            <p class="text-center">Aucun catalogue trouvé.</p>
        <?php else: ?>
            <div id="catalogCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach (array_keys($catalogs) as $index => $catalog_id): ?>
                        <button type="button" data-bs-target="#catalogCarousel" data-bs-slide-to="<?= $index; ?>"
                                class="<?= $index === 0 ? 'active' : ''; ?>"
                                aria-current="<?= $index === 0 ? 'true' : 'false'; ?>"
                                aria-label="Slide <?= $index + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-inner">
                    <?php
                    $is_first = true;
                    foreach ($catalogs as $catalog): ?>
                        <div class="carousel-item <?= $is_first ? 'active' : ''; ?>">
                            <div class="card">
                                <div class="card-header text-center bg-primary text-white">
                                    <h2 class="mb-0"><?= $catalog['name']; ?></h2>
                                </div>
                                <div class="card-body">
                                    <p><strong>Description :</strong> <?= $catalog['description']; ?></p>

                                    <?php if (!empty($catalog['images'])): ?>
                                        <h3 class="mt-3">Images associées :</h3>
                                        <div class="row">
                                            <?php foreach ($catalog['images'] as $image): ?>
                                                <div class="col-md-4">
                                                    <div class="card mb-4">
                                                        <img src="<?= "images/" . $image['bank_dir'] . "/" . $image['image_name']; ?>"
                                                             class="card-img-top"
                                                             alt="<?= $image['image_name']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text"><strong>Banque :</strong> <?= $image['bank_name']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>Aucune image trouvée pour ce catalogue.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php $is_first = false; ?>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#catalogCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#catalogCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-danger" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        <?php endif; ?>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

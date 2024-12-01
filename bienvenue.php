<?php
session_start(); //début de la session 

//on vérifie si l'utilisateur est authentifié 
if (!isset($_SESSION['login'])) {
    //$_SESSION stocke des information spécifiques à une session utilisateur
    header("Location: login.php"); //si le login n'exixte pas on est redirigé vers la page de connexion
    exit();//Arreter le script après la redirection 
}

require('db/connexion.php');  //connexion à la base de donnée 

// Requête pour récupérer les images
$query_images = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name 
                 FROM image AS i 
                 INNER JOIN bank AS b ON i.bankId = b.id";
$result_images = mysqli_query($con, $query_images);  //mysqli_query() permet d'envoyer la requête à la base de donnée 

//si la requête échoue 
if (!$result_images) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con)); //message d'erreur 
}

//on stocke les images dans un tableau 
$images = [];
while ($row = mysqli_fetch_assoc($result_images)) {  //on traite les résultat
    $images[] = [
        'bank_dir' => $row['bank_dir'],   //répertoire de la banque 
        'image_name' => $row['image_name'],  //nom le l'image
        'bank_name' => $row['bank_name'] //nom de la banque
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie d'Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?> <!--on inclut la navbar --> 

    <div class="container mt-5">
        <!-- le titre principal -->
        <h1 class="text-center mb-4">Galerie d'images</h1>
        
        <?php if (empty($images)): ?> 
            <!-- si aucune images n'est dispo on renvoie ce message -->
            <p class="text-danger text-center">Aucune image n'est disponible.</p>
        <?php else: ?>
            <!-- Galerie avec navigation -->
            <div class="d-flex justify-content-center align-items-center">
                <button id="prevButton" class="btn btn-primary me-3">❮</button>
                <!-- le conteneur qui affiche les images-->
                <div class="image-container">
                    <img id="catalogImage" class="image-display" 
                         src="./images/<?= htmlspecialchars($images[0]['bank_dir'] . '/' . $images[0]['image_name']); ?>" 
                         alt="<?= htmlspecialchars($images[0]['image_name']); ?>">
                </div>
                <!-- bouton qui permet de naviguer vers l'autre image -->
                <button id="nextButton" class="btn btn-primary ms-3">❯</button>
            </div>
            <!-- information sur l'image actuelle -->
            <p id="imageInfo" class="mt-3 text-center">
                Banque : <strong><?= htmlspecialchars($images[0]['bank_name']); ?></strong>
            </p>
        <?php endif; ?>
    </div>

    <script> //partie JS 
        // Récupérer les données des images depuis le PHP
        const images = JSON.parse('<?= json_encode($images); ?>');
        let currentIndex = 0;

        // Sélectionner les éléments HTML
        const catalogImage = document.getElementById("catalogImage");  // l'image affichée
        const imageInfo = document.getElementById("imageInfo"); //les informations sur l'image affichée 
        const prevButton = document.getElementById("prevButton");  //bouton précédent 
        const nextButton = document.getElementById("nextButton");  //bouton suivant 

        // Fonction pour mettre à jour l'image et les info 
        function updateImage(index) {
            const image = images[index];  //on récupère l'image actuelle
            catalogImage.src = `./images/${image.bank_dir}/${image.image_name}`;   //modifier la source de l'image 
            imageInfo.innerHTML = `Banque : <strong>${image.bank_name}</strong>`;   //mettre a jour les infos 
        }

        // Gestion du bouton "Précédent"
        prevButton.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length; // Boucle vers la dernière image
            updateImage(currentIndex);
        });

        // Gestion du bouton "Suivant"
        nextButton.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % images.length; // Boucle vers la première image
            updateImage(currentIndex);
        });
    </script>
</body>

</html>

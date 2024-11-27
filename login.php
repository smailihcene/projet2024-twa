<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Rend la page responsive; elle s'adapte automatiquement aux différentes tailled'écran --> 
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css"> <!-- lien pour la page de style --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- lien pour faciliter l'interface avce bootstrap --> 
</head>
<body>
    <!-- Ici on a le haut de la page -->
    <header>
        <h1>Connexion</h1>
        <p>Veuillez entrer vos identifiants pour accéder à votre espace</p>
    </header>

    <!-- Main Content -->
    <main>
        <section class="container">
            <!-- on utilise la classe "container" de Bootstrap --> 
            <form action="auth.php" method="POST">  
                <!-- action ="auth.php" : les données saisies sont envoyées à la page 'auth.php' pour les traiter -->
                 <!-- method = "POST" : les données sont transmises de manière sécurisée -->  
                <div class="mb-3">  <!-- une div du Bootstrap --> 
                    <label for="login" class="form-label">Login :</label>
                    <!-- Etiquette pour le champ "login" --> 
                    <input type="text" name="login" id="login" class="form-control" required>
                    <!-- Champ de texte pour le nom d'utilisateur : 
                     - required : rend ce champ obligatoire 
                     - class = "from-control" est une classe Bootstrap pour le style
                    -->
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <!-- Etiquette pour le champ "password" --> 
                    <input type="password" name="password" id="password" class="form-control" required>
                    <!-- Champ de texte pour le mot de passe : 
                     - required : rend ce champ obligatoire 
                     - class = "from-control" est une classe Bootstrap pour le style
                    -->
                </div>
                <input type="submit" value="Se connecter" class="btn btn-custom w-100">
                <!-- bouton de soumission du formulaire 
                    - type="submit" permet d'envoyer les données du formulaire. 
                    - value= "se connecter" affiche le texte écrit sur le bouton. 
                    - class="btn btn-custom w-100" : Classe Bootstrap pour styliser le bouton et le rendre large (100% de la largeur). 
                -->
                <a href="formulaire.php" class="login-btn">S'inscrire</a>

            </form>
        </section>
    </main>

</body>
</html>

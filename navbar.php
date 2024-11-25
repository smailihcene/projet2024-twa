<!-- indication du type de document de manière explicite -->
<!doctype html>
<!-- ouverture de la balise HTML avec indication de la langue du contenu du site (important pour aider par exemple firefox à trouver la bonne langue pour la traduction) -->
<html lang="en">
<!-- ouverture de l'entête du document, pour y intégrer les métadonnées -->

<head>
	<!-- indication d'une métadonnée (meta) avec encodage UTF-8 (important pour prendre en compte les signes diacritiques comme le trema de Gaël) ou le cyrillique, alphabet arabe, hiragana, idéogrammes chinois, hangeul, etc. etc. etc. -->
	<meta charset="utf-8">
	<!-- indication de la métadonnée spécifiant le type d'affichage (ici adapté à l'appareil -- le device --) -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- mise en place du favicon, c'est-à-dire l'image qui va apparaître en petit aux côtés du titre de l'onglet du navigateur -->
	<link rel="icon" href="img/logo.png" />
	<!-- import de la feuille de style de bootstrap à distance. Celle-ci est "minifiée" et a donc tout le contenu sur une ligne pour gagner de la place et simplifier le téléchagement -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<!-- import de la feuille de style dédiée aux icônes de bootstrap -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<!-- fermeture de l'entête -->
</head>

<!-- ouverture de la balise body pour mettre le contenu de la page -->


<body>

	<!-- mise en place d'une barre de navigation la balise "nav" est une balise HTML 5 qui simplifie le travail des robots analyseurs de sites pour référencement automatique (comme Google, Bing, etc.) -->
	<!-- les classes utilisées proviennent de la feuille de style de bootstrap précédemment chargée dans l'entête -->
	<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
		<!-- ouverture d'un container-fluid, c'est à dire un contenant qui prendra toute la place horizontale disponible -->
		<div class="container-fluid">
			<!-- ouverture d'une liste non ordonnée mais ayant la classe bootstrap pour un affichage déjà défini -->
			<ul class="navbar-nav">
				<!-- mise en place des éléments de liste (list item - li -) avec une classe dédiée aux liens de navigation -->


				<!-- ouverture d'un deuxième élément de liste. Cette fois-ci il s'agit d'un élément classique. Si notre possédait plusieurs rubriques alors nous utiliserions plusieurs éléments comme celui-ci. La classe nv-item indique bien un élément de navigation 🤓 -->
				<li class="nav-item">
					<!-- mise en place dans cet élément d'un lien vers... ce qu'on veut. Ici vers la même page. -->
					<a class="nav-link active" href="./bienvenue.php"><i class="bi bi-house-fill"> Acceuil </i></a>
				</li>

				<li class="nav-item">
					<!-- mise en place dans cet élément d'un lien vers... ce qu'on veut. Ici vers la même page. -->
					<a class="nav-link active" href="<?php print($varchemin) ?>"> <i>Catalogue</i> </a>
				</li>

				<li class="nav-item">
					<!-- mise en place dans cet élément d'un lien vers... ce qu'on veut. Ici vers la même page. -->
					<a class="nav-link active" href="./logout.php"> <i> <u> Se déconnecter </u> </i> </a>
				</li>

			</ul>
		</div>
	</nav>
</body>
<style>
	body {
		padding-top: 56px;
	}

	.navbar-nav :hover {
		color: black;
		/* Rouge par exemple */
		font-weight: bold;
		/* Optionnel : mettre en gras */
	}
</style>

</html>
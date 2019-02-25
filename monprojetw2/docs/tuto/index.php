<?php
	$p = (!empty($_GET['p'])) ? $_GET['p'] : "presentation";
	$file = "pages/$p.php";
	if (!file_exists($file)){
		$file = "pages/404.php";
	}

	function active($item){
		global $p;
		return ($item == $p) ? 'class="active"' : "";
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>W :: Documentation</title>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,300,700">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/prism.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= $p ?>">
	<div class="container">
		<div class="row">
			<header class="col-xs-12"><h1>W :: Documentation</h1></header>
		</div>
		<div class="row">
			<aside id="sidebar" class="col-xs-12 col-lg-2">
				<h2>Chapitres</h2>
				<nav>
					<ul class="nav nav-pills nav-stacked">
						<li <?= active('presentation') ?>><a href="?p=presentation" title="Présentation">Présentation</a></li>
						<li <?= active('installation') ?>><a href="?p=installation" title="Installation">Installation</a></li>
						<li <?= active('creer_une_page') ?>><a href="?p=creer_une_page" title="Créer une page">Créer une page</a></li>
						<li <?= active('routes') ?>><a href="?p=routes" title="Les routes">Les routes</a></li>
						<li <?= active('controleurs') ?>><a href="?p=controleurs" title="Les contrôleurs">Les contrôleurs</a></li>
						<li <?= active('vues') ?>><a href="?p=vues" title="Les vues">Les vues</a></li>
						<li <?= active('modeles') ?>><a href="?p=modeles" title="Les modèles">Les modèles</a></li>
						<li <?= active('utilisateurs') ?>><a href="?p=utilisateurs" title="Les utilisateurs">Les utilisateurs</a></li>
					</ul>
				</nav>
				<h2>Références</h2>
				<nav>
					<ul class="nav nav-pills nav-stacked">
						<li <?= active('configuration') ?>><a href="?p=configuration" title="Référence de configuration">Configurations</a></li>
						<li <?= active('conventions') ?>><a href="?p=conventions" title="Conventions du framework W">Conventions</a></li>
						<!--li><a href="../api/namespaces/W.html" title="Documentation de l'API">W :: API</a></li-->
					</ul>
				</nav>
			</aside>
			<section id="content" class="language-php col-xs-12 col-lg-10">
				 <?php require($file); ?>
			</section>
		</div>
		<div class="row">
			<footer class="col-xs-12">
				<a href="http://wf3.fr" title="Site de la WebForce3" target="_blank">WebForce3</a>
			</footer>
		</div>
	</div>

	<script src="js/prism.js"></script>
</body>
</html>
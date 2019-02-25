<?php
//vendor installé ? 
if (!file_exists('../vendor/autoload.php')){
	echo '<p>Vous devez installer les dépendances du projet avec la commande <code>composer install</code>. En effet, ceux-ci ne sont pas versionnés.</p>';
	die();
}

//autochargement des classes
require '../vendor/autoload.php';

//config.php créé ?
if (!file_exists('../app/config.php')){
	echo '<p>Vous devez créer le fichier <code>app/config.php</code>, en créant une copie du fichier <code>app/config.dist.php</code></p>';
	die();
}

//configuration
require '../app/config.php';

//instancie notre appli en lui passant la config et les routes
$app = new W\App($w_routes, $w_config);

//exécute l'appli
$app->run();
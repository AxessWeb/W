<?php

namespace W\Controller;

use W\Security\AuthentificationModel;
use W\Security\AuthorizationModel;

/**
 * Le contrôleur de base à étendre
 */
class Controller 
{

	/**
	 * Constante du chemin du dossier des vues
	 */
	const PATH_VIEWS = '../app/Views';

	/**
	 * Génère l'URL correspondant à une route nommée
	 * @param  string $routeName Le nom de route
	 * @param  mixed  $params    Tableau de paramètres optionnel de cette route
	 * @param  boolean $absolute Retourne une url absolue si true (relative si false)
	 * @return L'URL correspondant à la route
	 */
	public static function generateUrl($routeName, $params = array(), $absolute = false)
	{
		$params = (empty($params)) ? array() : $params;

		$app = getApp();
    	$router = $app->getRouter();
    	$routeUrl = $router->generate($routeName, $params);
		$url = $routeUrl;
		if($absolute){
	    	$u = \League\Url\Url::createFromServer($_SERVER);
			$url = $u->getBaseUrl() . $routeUrl;
		}
		return $url;
	}

	/**
	 * Redirige vers une URI
	 * @param  string $uri URI vers laquelle rediriger
	 */
	public function redirect($uri)
	{
		header("Location: $uri");
		die();	
	}

	/**
	 * Redirige vers une route nommée
	 * @param  string $routeName Le nom de route vers laquelle rediriger
	 * @param  array  $params    Tableau de paramètres optionnel de cette route
	 */
	public function redirectToRoute($routeName, array $params = array())
	{
		$uri = $this->generateUrl($routeName, $params);
    	$this->redirect($uri);
	}


	/**
	 * Affiche un template
	 * @param string $file Chemin vers le template, relatif à app/Views/
	 * @param array  $data Données à rendre disponibles à la vue
	 */
	public function show($file, array $data = array())
	{
		//incluant le chemin vers nos vues
		$engine = new \League\Plates\Engine(self::PATH_VIEWS);

		//charge nos extensions (nos fonctions personnalisées)
		$engine->loadExtension(new \W\View\Plates\PlatesExtensions());

		$app = getApp();

		// Rend certaines données disponibles à tous les vues
		// accessible avec $w_user & $w_current_route dans les fichiers de vue
		$engine->addData(
			[
				'w_user' 		  => $this->getUser(),
				'w_current_route' => $app->getCurrentRoute(),
				'w_site_name'	  => $app->getConfig('site_name'),
			]
		);

		// Retire l'éventuelle extension .php
		$file = str_replace('.php', '', $file);

		// Affiche le template
		echo $engine->render($file, $data);
		die();
	}

	/**
	 * Affiche une page 403
	 */
	public function showForbidden()
	{
		header('HTTP/1.0 403 Forbidden');

		$file = self::PATH_VIEWS.'/w_errors/403.php';
		if(file_exists($file)){
			$this->show('w_errors/403');
		}
		else {
			die('403');
		}
	}

	/**
	 * Affiche une page 404
	 */
	public function showNotFound()
	{
		header('HTTP/1.0 404 Not Found');

		$file = self::PATH_VIEWS.'/w_errors/404.php';
		if(file_exists($file)){
			$this->show('w_errors/404');
		}
		else {
			die('404');
		}	
	}

	/**
	 * Récupère l'utilisateur actuellement connecté
	 */
	public function getUser()
	{
		$authenticationModel = new AuthentificationModel();
		$user = $authenticationModel->getLoggedUser();
		return $user;
	}

	/**
	 * Autorise l'accès à un ou plusieurs rôles
	 * @param mixed $roles Tableau de rôles, ou chaîne pour un seul
	 */
	public function allowTo($roles)
	{
		if (!is_array($roles)){
			$roles = [$roles];
		}
		$authorizationModel = new AuthorizationModel();
		foreach($roles as $role){
			if ($authorizationModel->isGranted($role)){
				return true;
			}
		}

		$this->showForbidden();
	}


	/**
	 * Retourne une réponse JSON au client
	 * @param mixed $data Les données à retourner
	 * @return les données au format json
	 */
	public function showJson($data)
	{
		header('Content-type: application/json');
		$json = json_encode($data, JSON_PRETTY_PRINT);
		if($json){
			die($json);
		}
		else {
			die('Error in json encoding');
		}
	}

}
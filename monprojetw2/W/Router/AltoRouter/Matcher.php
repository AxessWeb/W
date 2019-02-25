<?php

namespace W\Router\AltoRouter;

class Matcher 
{

	/**
	 * Cherche une correspondance entre l'URL et les routes, et appelle la méthode appropriée
	 */
	public function match()
	{
		$router = getApp()->getRouter();
		$match = $router->match();

		if ($match){

			$callableParts = explode('#', $match['target']);
			// Retire l'optionnel suffixe 'Controller', pour le remettre ci-dessous
			$controllerName = ucfirst(str_replace('Controller', '', $callableParts[0]));
			$methodName = $callableParts[1];
			$controllerFullName = 'Controller\\'.$controllerName.'Controller';
			
			$controller = new $controllerFullName();
			
			// Appelle la méthode, en lui passant les paramètres d'URL en arguments 
			call_user_func_array(array($controller, $methodName), $match['params']);
		}
		//404
		else {
			$controller = new \W\Controller\Controller();
			$controller->showNotFound();
		}

	}

}
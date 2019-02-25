<?php

namespace W\Security;

use \W\Session\SessionModel;
use \W\Security\AuthentificationModel;

/**
 * Gère l'accès aux pages en fonction des droits utilisateurs
 */
class AuthorizationModel
{

	/**
	 * Vérifie les droits d'accès de l'utilisateur en fonction de son rôle
	 * @param  string  	$role Le rôle pour lequel on souhaite vérifier les droits d'accès
	 * @return boolean 	true si droit d'accès, false sinon
	 */
	public function isGranted($role)
	{
		$app = getApp();
		$roleProperty = $app->getConfig('security_role_property');

		//récupère les données en session sur l'utilisateur
		$authentificationModel = new AuthentificationModel();
		$loggedUser = $authentificationModel->getLoggedUser();

		// Si utilisateur non connecté
		if (!$loggedUser){
			// Redirige vers le login
			$this->redirectToLogin();
		}

		if (!empty($loggedUser[$roleProperty]) && $loggedUser[$roleProperty] === $role){
			return true;
		}

		return false;
	}

	/**
	 * Redirige vers la page de connexion
	 */
	public function redirectToLogin()
	{
		$app = getApp();

		$controller = new \W\Controller\Controller();
		$controller->redirectToRoute($app->getConfig('security_login_route_name'));
	}

}
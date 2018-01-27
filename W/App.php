<?php

namespace W;

/**
 * Gère la configuration et exécute le routeur
 */
class App 
{
	/** @var array Contient le tableau de configuration complet */
	protected $config;
	/** @var \AltoRouter Le routeur */
	protected $router;
	/** @var string Le sous-dossier d'URL dans lequel on accède à l'appli */
	protected $basePath;

	/**
	 * Constructeur
	 * @param array $w_routes Tableau de routes
	 * @param array $w_config Tableau optionnel de configurations
	 */
	public function __construct(array $w_routes, array $w_config = array())
	{
		session_start();
		$this->setConfig($w_config);
		$this->routingSetup($w_routes);
	}

	/**
	 * Configure le routage
	 * @param  array  $w_routes Tableau de routes
	 */
	private function routingSetup(array $w_routes)
	{
		$this->router = new \AltoRouter();

		//voir public/.htaccess
		//permet d'éviter une configuration désagréable (sous-dossier menant à l'appli)
		$this->basePath = (empty($_SERVER['W_BASE'])) ? '' : $_SERVER['W_BASE'];

		$this->router->setBasePath($this->basePath);
		$this->router->addRoutes($w_routes);
	}

	/**
	 * Récupère les configurations fournies par l'appli
	 * @param array $w_config Tableau de configuration
	 */
	private function setConfig(array $w_config)
	{
		$defaultConfig = [
				//information de connexion à la bdd
			'db_host' => 'localhost',						//hôte (ip, domaine) de la bdd
			'db_user' => 'root',							//nom d'utilisateur pour la bdd
			'db_pass' => '',								//mot de passe de la bdd
			'db_name' => '',								//nom de la bdd
			'db_table_prefix' => '',						//préfixe ajouté aux noms de table

			//authentification, autorisation
			'security_user_table' => 'users',				//nom de la table contenant les infos des utilisateurs
			'security_id_property' => 'id',					//nom de la colonne pour la clef primaire
			'security_username_property' => 'username',		//nom de la colonne pour le "pseudo"
			'security_email_property' => 'email',			//nom de la colonne pour l'"email"
			'security_password_property' => 'password',		//nom de la colonne pour le "mot de passe"
			'security_role_property' => 'role',				//nom de la colonne pour le "role"

			'security_login_route_name' => 'login',			//nom de la route affichant le formulaire de connexion

			// configuration globale
			'site_name'	=> '', 								// contiendra le nom du site
		];

		//remplace les configurations par défaut par celle de l'appli
		$this->config = array_merge($defaultConfig, $w_config);
	}


	/**
	 * Récupère une donnée de configuration
	 * @param   $key Le clef de configuration
	 * @return mixed La valeur de configuration
	 */
	public function getConfig($key)
	{
		return (isset($this->config[$key])) ? $this->config[$key] : null;
	}

	/**
	 * Exécute le routeur
	 */
	public function run()
	{

		$matcher = new \W\Router\AltoRouter\Matcher($this->router);
		$matcher->match();
	}

	/**
	 * Retourne le routeur
	 * @return \AltoRouter Le routeur
	 */
	public function getRouter()
	{
		return $this->router;
	}

	/**
	 * Retourne la base path
	 * @return  string La base path
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}

	/**
	 * Retourne le nom de la route actuelle
	 * @return mixed Le nom de la route actuelle depuis \AltoRouter ou le false
	 */
	public function getCurrentRoute(){

		$route = $this->getRouter()->match();
		if($route){
			return $route['name'];
		}
		else {
			return false;
		}
	}
}
<?php

namespace W\Model;

use \PDO;
use \PDOException;

/**
 * Gère la connexion à la base de données (Singleton Pattern)
 */
class ConnectionModel
{

	private static $dbh;

	/**
	 * Crée une connexion ou la retourne si présente
	 */
	public static function getDbh()
	{
		if(!self::$dbh){
			self::setNewDbh();
		}
		return self::$dbh;
	}

	/**
	 * Crée une nouvelle connexion à la base
	 */
	public static function setNewDbh()
	{
		$app = getApp();
		
		try {
			//connexion à la base avec la classe PDO et le DSN
			self::$dbh = new PDO('mysql:host='.$app->getConfig('db_host').';dbname='.$app->getConfig('db_name'), $app->getConfig('db_user'), $app->getConfig('db_pass'), array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //on s'assure de communiquer en utf8
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //on récupère nos données en array associatif par défaut
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING         //on affiche les erreurs. À modifier en prod. 
			));
		} catch (PDOException $e) { //attrappe les éventuelles erreurs de connexion
			echo 'Erreur de connexion : ' . $e->getMessage();
		}
	}

}
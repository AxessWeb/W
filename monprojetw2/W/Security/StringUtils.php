<?php

namespace W\Security;

/**
 * Fonctions sécuritaires et utiles sur les chaînes
 */
class StringUtils 
{

	/**
	 * Retourne un chaîne aléatoire sécuritaire, url safe
	 * @param  integer $length Longeur de la chaîne à générer
	 * @return string $string La chaîne générée
	 */
	public static function randomString($length = 80)
	{
		$possibleChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';
		$factory = new \RandomLib\Factory;
		$generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));
		$string = $generator->generateString($length, $possibleChars);

		return $string;
	}

}
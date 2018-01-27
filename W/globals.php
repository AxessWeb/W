<?php

// Espace de nom global
namespace {

	/**
	 * print_r coké
	 * @param  mixed $var La variable a déboger
	 */
	function debug($var, $type = 'print')
	{
		echo '<pre class="debug" style="padding: 10px; font-family: Consolas, Monospace; background-color: #000; color: #FFF;">';
		print_r($var);
		echo '</pre>';
	}

	/**
	 * Retourne l'instance de l'application depuis l'espace global
	 * @return \W\App L'application
	 */
	function getApp()
	{
		if (!empty($GLOBALS['app'])){
			return $GLOBALS['app'];
		}

		return null;
	}

}
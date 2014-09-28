<?php
/**
*	Decena Php Framework.
*
*	@author		Edgard Decena - edecena@gmail.com
* 	@link		http://www.gnusistemas.com
* 	@version	1.0.0
* 	@package	DecPHP
*	@license 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/** CARGA DE CLASES DEL FRAMEWORK. **/

spl_autoload_register(function($clase)
{
	$archivoClase = __DIR__.DIRECTORY_SEPARATOR.'class'.$clase.'.php';

	if (is_readable($archivoClase))
	{
		require_once $archivoClase;
	}
});

/** CARGA DE CLASES DE LA APLICACIÓN. **/

spl_autoload_register(function($clase)
{
	$archivoClase = dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.$clase.'.php';

	if (is_readable($archivoClase))
	{
		require_once $archivoClase;
	}
});
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

final class App
{
	private static $_app_config = array();	// Variables de configuración de la aplicación.
	
	const DS = DIRECTORY_SEPARATOR;


	private function __construct(){}


	public static function url($url = null)
	{
		if ($url)
		{
			return trim('http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']), '/').$url;
		}
		else
		{
			return trim('http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']), '/').'/';
		}
	}


	public static function dir($path = null)
	{
		if ($path)
		{
			$path = dirname($_SERVER['SCRIPT_FILENAME']).App::DS.str_replace('.', App::DS, $path).App::DS;

			if (is_dir($path))
			{
				return $path;
			}
			else
			{
				App::error('No existe el directorio '.$path);	
			}
		}
		else
		{
			return dirname($_SERVER['SCRIPT_FILENAME']).App::DS;
		}
	}


	public static function setConfig(array $AppConfig)
	{
		App::$_app_config = $AppConfig;
	}


	public static function config($clave = null)
	{
		if ($clave)
		{
			if (array_key_exists($clave, App::$_app_config))
			{
				return App::$_app_config[$clave];
			}
			else
			{
				App::error('No existe el item de configuracion "'.$clave.'" en App::config().');
			}
		}
		else
		{
			return App::$_app_config;
		}
	}


	public static function error($textError)
	{
		if (App::config('debug_mode'))
		{
			try
			{
				throw new Exception($textError);
			}
			catch (Exception $e)
			{
				require_once 'error.phtml';
				exit;
			}
		}
		else
		{
			exit('ERROR: No ha sido establecido el parametro \'debug_mode\' en el config.php');
		}
	}


	public static function callController()
	{
		Bootstrap::run(new Request);
	}


	public static function run(callable $funcion)
	{
		$config = App::dir('Config').'config.php';

		if (is_readable($config))
		{
			App::setConfig(require_once $config);

			$folders_config = array('cache_folder',
									'classes_folder',
									'controllers_folder',
									'data_folder',
									'libs_folder',
									'models_folder',
									'templates_folder',
									'views_folder',
									'widgets_folder'
								);

			$claves_config = array(	'debug_mode',
									'template',
									'hash_key',
									'hash_algorithm',
									'set_locale',
									'time_zone',
									'session_time',
									'access_levels',
									'database_config'
								);

			//	VALIDACIÓN  DE  LOS  DIRECTORIOS  DE  LA  APLICACIÓN

			foreach ($folders_config as $folder)
			{
				if(!array_key_exists($folder, App::config())) App::error('No existe la clave "'.$folder.'" en App::AppConfig().');

				if (!is_dir(App::dir(App::config($folder)))) App::error('No existe el directorio '.App::dir(App::config($folder)));
			}

			//	VALIDACIÓN  DE  LOS  DIRECTORIOS  JS, CSS y IMG de las Vistas, Templates y Widgets

			foreach (['views_folder', 'templates_folder', 'widgets_folder'] as $folder)
			{
				foreach (['js', 'css', 'img'] as $subFolder)
				{
					if (!is_dir(App::dir(App::config($folder)).$subFolder)) App::error('No existe el directorio '.App::dir(App::config($folder)).$subFolder);
				}
			}

			//	VALIDACIÓN  DE  LAS  CLAVES  DE  LA  APLICACION

			foreach ($claves_config as $clave)
			{
				if(!array_key_exists($clave, App::config())) App::error('No existe la clave "'.$clave.'" en App::AppConfig().');
			}


			//	SETEO  LA  CONFIGURACIÓN  DE  ERROR  DE  PHP

			if(App::config('debug_mode'))
			{
				ini_set('error_reporting', E_ALL | E_NOTICE | E_STRICT);
				ini_set('track_errors', 'On');
				ini_set('display_errors', '1');
			}
			else
			{
				ini_set('display_errors', '0');
			}


			//	SETEO  DE  LA CONFIGURACIÓN  REGIONAL

			setlocale(LC_MONETARY, App::config('set_locale')); // para localeconv()

			setlocale(LC_NUMERIC, App::config('set_locale')); // para localeconv()

			setlocale(LC_TIME, App::config('set_locale')); // formato de fecha y hora con strftime()
			
			date_default_timezone_set(App::config('time_zone')); // Establece zona horaria.


			return call_user_func($funcion);
		}
		else
		{
			App::error('No existe o no es legible el archivo de configuración <b>'.$config);
		}
	}
}
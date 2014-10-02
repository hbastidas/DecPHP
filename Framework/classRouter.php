<?php
/**
*	Decena Php Framework.
*
*	@author		Edgard Decena - edecena@gmail.com
* 	@link		http://www.gnusistemas.com
* 	@version	1.0.0
* 	@package	DecPHP
*	@license 	http://opensource.org/licenses/gpl-license.php GNU Public License
*
* 	DEBE inluir el siguiente código en el archivo .htaccess
*	donde se encuentre el archivo index.php
*
* 	RewriteEngine On        
* 	RewriteCond %{REQUEST_FILENAME} !-d
* 	RewriteCond %{REQUEST_FILENAME} !-f
* 	RewriteCond %{REQUEST_FILENAME} !-l
* 	RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
*/

final class Router
{
	
	private static $_carVar      = ':';	// Caracter indicador de parámetros de closure.
	
	private static $_matchPatron = false;


	private function __construct() {}


	private static function _matchMetodoPatron($patron, $funcion)
	{
		$patron = explode('/', $patron);
			
		$url    = array();

		foreach ($patron as $i => $parte)
		{
			if (isset($parte[0]) and $parte[0] === Router::$_carVar)
				{
					$url[$i] = '(\w+)';
				}
				else $url[$i] = $patron[$i];
		}

		$patron = implode('/', $url);

		$patron = '/^'.str_replace('/', '\/', $patron).'$/';

		$url_get = isset($_GET['url']) ? rtrim('/'.$_GET['url'], '/') : '/';

		if (preg_match($patron, $url_get, $parametros))
		{
            array_shift($parametros);

            Router::$_matchPatron = true;

            return call_user_func_array($funcion, array_values($parametros));
        }
	}


	public static function get($patron = false, $funcion)
	{
		if (!Router::$_matchPatron)
		{
			if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] === 'GET')
			{
				if ($patron)
				{
					if (is_callable($funcion))
					{
						Router::_matchMetodoPatron($patron, $funcion);
					}
					else
					{
						App::error('Debe proveer una función al método Router::get($patron, $funcion).');
					}		
				}
				else
				{
					App::error('Debe proveer un parámetro $patron a Router::get($patron, $funcion).');	
				}
			}
		}
	}


	public static function post($patron = false, $funcion)
	{
		if (!Router::$_matchPatron)
		{
			if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] === 'POST')
			{
				if ($patron)
				{
					if (is_callable($funcion))
					{
						Router::_matchMetodoPatron($patron, $funcion);
					}
					else
					{
						App::error('Debe proveer una función al método Router::post($patron, $funcion).');
					}		
				}
				else
				{
					App::error('Debe proveer un parámetro $patron a Router::post($patron, $funcion).');
				}
			}
		}
	}


	public static function defaultRoute($funcion)
	{
		if (!Router::$_matchPatron)
		{
			if (is_callable($funcion))
			{
				return call_user_func($funcion);
			}
			else
			{
				App::error('Debe proveer una función al método Router::defaultRoute().');
			}
		}
	}


	public static function redirect($url = '/', $method = 'GET', $args = array())
	{
		$base_url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']);

		if ($method == 'GET')
		{
			header('Location: '.$base_url.$url);
		}
		elseif ($method == 'POST')
		{
			$ch = curl_init($base_url.$url);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $args);
			curl_exec ($ch);
			curl_close ($ch);
		}
		else
		{
			App::error('Debe proveer un parámetro $method GET o POST a Router::redirect($url, $method, $args).');
		}
	}

}
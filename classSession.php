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

final class Session
{

	private function __construct() {}


	public static function autenticate()
	{
		$_SESSION['autenticate']  = true;
		$_SESSION['session_time'] = time();
	}


	public static function autenticated()
	{
		if (App::config('session_time') == 0) return true;
		
		return isset($_SESSION['autenticate']) ? $_SESSION['autenticate'] : false;
	}


	public static function set($clave = false, $valor = null)
	{
		if ($clave)
		{
			if (!is_null($valor))
			{
				if (Session::autenticated())
				{
					$_SESSION[$clave] = $valor;
				}
				else
				{
					App::error('Debe estar autenticado el usuario antes de establecer una variable de sesión.');
				}
			}
			else
			{
				App::error('Debe proveer un parámetro $valor a Session::get($clave, $valor).');
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $clave a Session::get($clave, $valor).');
		}

	}


	public static function get($clave)
	{
		if (isset($_SESSION[$clave]))
		{
			return $_SESSION[$clave];
		}
		else
		{
			return null;
		}
	}


	public static function timeCompleted()
	{
		if (App::config('session_time') == 0) return false;

		if (Session::get('session_time'))
		{
			if (time() - Session::get('session_time') > App::config('session_time') * 60)
			{
				Session::end();
				return true;
			}
			else
			{
				Session::set('session_time', time());
			}
		}
		else
		{
			return true;	
		}
	}


	public static function begin()
	{
		session_regenerate_id();
		session_start();
	}


	public static function end($clave = false)
	{
		if ($clave)
		{
			if(is_array($clave))
			{
				foreach ($clave as $var)
				{
					if(isset($_SESSION[$var]))
					{
						unset($_SESSION[$var]);
					}
				}
			}
			else
			{
				if(isset($_SESSION[$clave]))
				{
					unset($_SESSION[$clave]);
				}
			}
		}
		else
		{
			session_destroy();
			unset($_SESSION);
		}
	}

}
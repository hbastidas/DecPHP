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

final class Cookie
{

	private function __construct(){}

 
	public static function exists($cookie)
	{
		return isset($_COOKIE[Security::hash($cookie)]);
	}


	public static function isEmpty($cookie)
	{
		return empty($_COOKIE[Security::hash($cookie)]);
	}


	public static function get($cookie)
	{
		return (isset($_COOKIE[Security::hash($cookie)]) ? Security::decrypt($_COOKIE[Security::hash($cookie)]) : null);
	}


	public static function set($cookie = false, $valor = false, $expira = 1, $ruta = '/', $dominio = false)
	{
		if ($cookie)
		{
			if ($valor)
			{
				$respuesta = false;

				if (!headers_sent())
			    {
			    	if ($dominio == false)
					{
						$dominio = $_SERVER['HTTP_HOST'];
					}

					if (is_numeric($expira))
					{
			        	$expira = $expira * 60 + time();
					}
					else
					{
			        	App::error('El parámetro $expira debe ser un número.');
					}

					$respuesta = @setcookie(Security::hash($cookie), Security::encrypt($valor), $expira, $ruta, $dominio);

					if ($respuesta)
					{
						$_COOKIE[Security::hash($cookie)] = Security::encrypt($valor);
					}
				}
			}
			else
			{
				App::error('Debe proveer un valor de $cookie a Cookie::set($cookie, $valor).');
			}
		}
		else
		{
			App::error('Debe proveer un nombre de $cookie a Cookie::set($cookie, $valor).');
		}
	}


	public static function delete($cookie = false, $ruta = '/', $dominio = false)
	{
		if ($cookie)
		{
			$respuesta = false;

			if (!headers_sent())
			{
				if ($dominio == false)
				{
					$dominio = $_SERVER['HTTP_HOST'];
				}

				$respuesta = setcookie(Security::hash($cookie), '', time() - 3600, $ruta, $dominio);

				if ($respuesta)
				{
					unset($_COOKIE[Security::hash($cookie)]);
				}
			}

			return $respuesta;
		}
		else
		{
			App::error('Debe proveer un nombre de $cookie a Cookie::delete($cookie).');
		}
	}

}
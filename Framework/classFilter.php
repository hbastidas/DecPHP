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

final class Filter
{
	private static $_sanear = array('email'   => FILTER_SANITIZE_EMAIL,
									'float'   => FILTER_SANITIZE_NUMBER_FLOAT,
									'entero'  => FILTER_SANITIZE_NUMBER_INT,
									'cadena'  => FILTER_SANITIZE_STRING,
									'url'     => FILTER_SANITIZE_URL,
									'escapar' => FILTER_SANITIZE_SPECIAL_CHARS
								);


	private static $_validar = array('booleano'	=> FILTER_VALIDATE_BOOLEAN,
									'email'		=> FILTER_VALIDATE_EMAIL,
									'float'		=> FILTER_VALIDATE_FLOAT,
									'entero'	=> FILTER_VALIDATE_INT,
									'ip'		=> FILTER_VALIDATE_IP,
									'url'		=> FILTER_VALIDATE_URL
								);


    private function __construct(){}


	public static function sanitize($dato = false, $filter = false)
	{
		if ($dato)
		{
			if ($filter)
			{
				if (array_key_exists($filter, Filter::$_sanear))
				{
					return filter_var(trim($dato), Filter::$_sanear[$filter]);
				}
				else
				{
					$validos = '';

					foreach (Filter::$_sanear as $key => $value)
					{
						$validos .= ' "'.$key.'",';
					}

					App::error('Debe proveer un filtro válido de saneamiento:'.trim($validos, ',').'.');
				}
			}
			else
			{
				App::error('Debe proveer un parámetro $filter al método Filter::sanitize($dato, $filter).');
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $dato al método Filter::sanatize($dato, $filter).');
		}
	}


	public static function validate($dato = false, $filter = false)
	{
		if ($dato)
		{
			if ($filter)
			{
				if (array_key_exists($filter, Filter::$_validar))
				{
					return filter_var($dato, Filter::$_validar[$filter]);
				}
				else
				{
					$validos = '';

					foreach (Filter::$_validar as $key => $value)
					{
						$validos .= ' "'.$key.'",';
					}
					App::error('Debe proveer un parámetro $filter válido de validación:'.trim($validos, ',').'.');
				}
			}
			else
			{
				App::error('Debe proveer un parámetro $filter al método Filter::validate($dato, $filter).');
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $dato al método Filter::validate($dato, $filter).');
		}
	}

}
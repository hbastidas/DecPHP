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

final class Access
{

	private function __construct() {}


	private static function _level($level = false)
	{
		if ($level)
		{
			if (array_key_exists($level, App::config('access_levels')))
			{
				return App::config('access_levels')[$level]; 
			}
			else
			{
				App::error('El nivel de acceso '.$level.' no existe entre los niveles de acceso permitidos.');
			}
		}
		else
		{
			App::error('Debe proveer un parametro $level a Access::_level().');
		}
	}


	public static function allow($levelAccess = false, $guestAccess = false)
	{
		if ($levelAccess)
		{
			if ($guestAccess)
			{
				if (Access::_level($levelAccess) < Access::_level($guestAccess))
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				App::error('Debe proveer un parametro $guestAccess a Access::allow().');
			}
		}
		else
		{
			App::error('Debe proveer un parametro $levelAccess a Access::allow().');
		}
	}


	public static function allowStrict($levelAccess = false, $guestAccess = false)
	{
		if ($levelAccess)
		{
			if ($guestAccess)
			{
				if (Access::_level($levelAccess) == Access::_level($guestAccess))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				App::error('Debe proveer un parametro $guestAccess a Access::access().');
			}
		}
		else
		{
			App::error('Debe proveer un parametro $levelAccess a Access::access().');
		}
	}
}
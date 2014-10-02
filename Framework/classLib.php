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

final class Lib
{

	private function __construct() {}


	public static function load($lib = false)
	{
		if ($lib)
		{
			$libreria = App::dir(App::config('libs_folder')).$lib.'.php';

			if (is_readable($libreria))
			{
				require_once $libreria;
			}
			else
			{
				App::error('No existe o no es legible la librería '.$libreria);
			}
		}
		else
		{
			App::error('Debe proveer un parametro $lib a Lib::load($lib).');
		}
		
	}

}
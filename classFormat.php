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

final class Format
{

	private function __construct() {}


	public static function dateMysql($fecha)
	{
		
	}


	public static function money($numero = 0)
	{
		return is_numeric($numero) ? money_format('%.2n', $numero) : false;
	}


	public static function number($numero = 0)
	{
		return is_numeric($numero) ? number_format($numero, 2, ',', '.') : false;
	}

}
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

final class Security
{
	private static $_cifrado       = MCRYPT_RIJNDAEL_256;
	
	private static $_modo          = MCRYPT_MODE_ECB;
	
	private static $_fuente        = MCRYPT_RAND;


	private function __construct() {}


	public static function hash($data = false)
	{
		if ($data)
		{
			if (in_array(App::config('hash_algorithm'), hash_algos()))
			{
				if (App::config('hash_key'))
				{
					$hash = hash_init(App::config('hash_algorithm'), HASH_HMAC, App::config('hash_key'));

					hash_update($hash, $data);

					return hash_final($hash);
				}
				else
				{
					App::error('Debe proveer una clave hash_key en Config/config.php.');
				}
			}
			else
			{
				App::error('El algoritmo dado '.App::config('hash_algorithm').' no es válido.');
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $data a Security::hash($date).');
		}
	}


	private static function _b64encode($data)
    {
        $data = base64_encode($data);
        $data = str_replace(array('+', '/' ,'='), array('-', '_', ''), $data);
        return $data;
    }


    private static function _b64decode($data)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $data);
        $mod4 = strlen($data) % 4;
        
        if ($mod4) $data .= substr('====', $mod4);

        return base64_decode($data);
    }


    public static function encrypt($data = false)
	{
		if ($data)
		{
			if (App::config('hash_key'))
			{
				$iv_size    = mcrypt_get_iv_size(Security::$_cifrado, Security::$_modo);
				
				$iv         = mcrypt_create_iv($iv_size, Security::$_fuente);
				
				$encriptado = mcrypt_encrypt(Security::$_cifrado, App::config('hash_key'), serialize($data), Security::$_modo, $iv);

	        	return Security::_b64encode($encriptado);
			}
			else
			{
				App::error('Debe proveer una clave hash_key en Config/config.php.');
			}
		}
		else
		{
			App::error('Debe proveer el parámetro $data a Security::encrypt($data).');
		}
	}


	public static function decrypt($data = false)
	{
		if ($data)
		{
			if (App::config('hash_key'))
			{
				$encriptado    = Security::_b64decode($data);
				
				$iv_size       = mcrypt_get_iv_size(Security::$_cifrado, Security::$_modo);
				
				$iv            = mcrypt_create_iv($iv_size, Security::$_fuente);
				
				$desencriptado = unserialize(mcrypt_decrypt(Security::$_cifrado, App::config('hash_key'), $encriptado, Security::$_modo, $iv));

				return $desencriptado;
			}
			else
			{
				App::error('Debe proveer una clave hash_key en Config/config.php.');
			}
		}
		else
		{
			App::error('Debe proveer el parámetro $data a Security::uncrypt($data).');
		}
	}

}
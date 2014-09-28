<?php
/**
*   Decena Php Framework.
*
*   @author     Edgard Decena - edecena@gmail.com
*   @link       http://www.gnusistemas.com
*   @version    1.0.0
*   @package    DecPHP
*   @license    http://opensource.org/licenses/gpl-license.php GNU Public License
*/

final class Cache
{

	private function __construct() {}


	private static function _delete($clave = false)
	{
		if ($clave)
		{
			$archivo = App::root().App::config('cache_folder').App::ds().Security::hash($clave);

			if (is_file($archivo))
			{
				unlink($archivo);
			}
		}
		else
		{
			App::error('Debe proveer un parametro $clave a Cache::_delete().');
		}
	}


	private static function _exists($clave = false, $tiempoMaximo = 1)
	{
		if ($clave)
        {
            $archivo = App::root().App::config('cache_folder').App::ds().Security::hash($clave);

            if (is_file($archivo))
            {
                if (filemtime($archivo) + $tiempoMaximo * 60 > time())
                {    
                    return true;
                }
                else
                {
                    Cache::_delete($clave);
                }
            }

            return false;
        }
        else
        {
            App::error('Debe proveer un parametro $clave a Cache::_exists().');
        }
	}


	public static function put($clave = false, $data = false)
	{
		if ($clave)
		{
			if ($data)
			{
				$archivo = App::root().App::config('cache_folder').App::ds().Security::hash($clave);

                $data = Security::encrypt($data);

                file_put_contents($archivo, $data);
			}
			else
			{
				App::error('Debe proveer un parametro $data a Cache::put($clave, $data).');
			}
		}
		else
		{
			App::error('Debe proveer un parametro $clave a Cache::put($clave, $data).');	
		}
	}


	public static function get($clave = false, $tiempoMaximo = 1)
    {
        if ($clave)
        {
            $archivo = App::root().App::config('cache_folder').App::ds().Security::hash($clave);

            if (is_file($archivo))
            {
                if (Cache::_exists($clave, $tiempoMaximo))
                {
                    $data = file_get_contents($archivo);

                    return Security::decrypt($data);
                }
                else
                {
                	return false;
                }
            }
            else
            {
            	return false;
            }
        }
        else
        {
        	App::error('Debe proveer un parametro $clave al metodo Cache::get($clave).');
        }
    }

}
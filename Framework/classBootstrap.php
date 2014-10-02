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

final class Bootstrap
{

	private function __construct(){}


	public static function run(Request $URL)
	{

		$controlador = $URL->getControlador();

		$metodo      = $URL->getMetodo();

		$parametros  = $URL->getParametros();

		$rutaControlador = App::dir(App::config('controllers_folder')).'controller'.$controlador.'.php';

		if (is_readable($rutaControlador))
		{
			require_once $rutaControlador;

			$controlador = 'controller'.$controlador;
			$controlador = new $controlador;

			if (!is_callable(array($controlador, $metodo)))
			{
				header('Location: '.App::url('/'.strtolower($URL->getControlador())));
				exit;
			}
			
			if(isset($parametros))
			{
				call_user_func_array(array($controlador, $metodo), $parametros);
	        }
	        else
	        {
	        	call_user_func(array($controlador, $metodo));
	        }
		}
		else
		{
			header('Location: '.App::url());
		}
	}

}
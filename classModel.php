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

abstract class Model extends Database
{

	public function __construct()
	{
		parent::__construct();
	}


	public static function get($model = null)
	{

		if ($model)
		{
			$modelo = App::dir(App::config('models_folder')).'model'.$model.'.php';

			if (is_readable($modelo))
			{
				require_once $modelo;

				$model = 'model'.$model;
				
				return new $model;
			}
			else
			{
				App::error('No existe o no es legible el modelo '.$modelo);
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $model a App::getModel($model).');
		}
	}

}
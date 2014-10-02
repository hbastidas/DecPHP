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

final class Widget
{

	public static $data       = null;
	private static $_widgets  = array();


	private function __construct() {}


	public static function set($etiqueta = false)
	{
		if ($etiqueta)
		{
			if (isset(Widget::$_widgets[$etiqueta]))
			{
				require array_values(widget::$_widgets[$etiqueta])[0];
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $etiqueta a Widget::setWidget($etiqueta).');
		}
	}


	public static function render($etiqueta = false, $widget = false)
	{
		if ($etiqueta)
		{
			if ($widget)
			{
				$archivoWidget = App::dir(App::config('widgets_folder')).'widget'.$widget.'.phtml';

				if(is_readable($archivoWidget))
				{
					Widget::$_widgets[$etiqueta][$widget] = $archivoWidget;
				}
				else
				{
					App::error('No existe o no es legible el widget '.$archivoWidget);
				}
			}
			else
			{
				App::error('Debe proveer un parámetro $widget a Widget::render($etiqueta, $widget).');
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $etiqueta a Widget::render($etiqueta, $widget).');
		}
	}


	public static function setJs()
	{
		$totalJsFiles = func_num_args();	
		$jsFiles      = func_get_args();
		$_jsFiles     = '';

		if ($totalJsFiles > 0)
		{
			for ($i = 0; $i < $totalJsFiles; $i++)
			{
				$archivoJs = App::dir(App::config('widgets_folder')).'js'.App::DS.$jsFiles[$i].'.js';

				if (is_readable($archivoJs))
				{
					$_jsFiles .= '<script type = "text/javascript" src = "'.App::url().App::config('widgets_folder').'/js/'.$jsFiles[$i].'.js"></script>'."\n";
				}
				else
				{
					App::error('No puede encontrarse el archivo '.$archivoJS);
				}
			}

			echo $_jsFiles;
		}
		else
		{
			App::error('No se ha encontrado ningún archivo .js para la widget.');
		}
	}


	public static function setCss()
	{
		$totalCssFiles = func_num_args();
		$cssFiles      = func_get_args();
		$_cssFiles     = '';

		if ($totalCssFiles > 0)
		{
			for ($i = 0; $i < $totalCssFiles; $i++)
			{	
				$archivoCss = App::dir(App::config('widgets_folder')).'css'.App::DS.$cssFiles[$i].'.css';

				if (is_readable($archivoCss))
				{
					$_cssFiles .= '<link rel = "stylesheet" type = "text/css" href = "'.App::url().App::config('widgets_folder').'/css/'.$cssFiles[$i].'.css"/>'."\n";
				}
				else
				{
					App::error('No puede encontrarse el archivo css '.$archivoCss);
				}
			}

			echo $_cssFiles;
		}
		else
		{
			App::error('No se ha pasado ningún archivo .css para la widget.');
		}
	}
	

}
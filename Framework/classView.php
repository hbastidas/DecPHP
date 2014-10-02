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

final class View
{
	
	public static $contenido  = '';	
	public static $data       = null;


	private function __construct() {}


	public static function render($vista = null, $sinTemplate = false, $tempFile = false)
	{
		if ($vista)
		{
			$vista                  = explode('.', $vista);
			$vista[count($vista)-1] = 'view'.$vista[count($vista)-1];

			$archivoVista = App::dir(App::config('views_folder')).implode(App::DS, $vista).'.phtml';

			if(is_readable($archivoVista))
			{
				if (!$sinTemplate)	// Con template
				{
					$template = $tempFile ? $tempFile : App::config('template');

					$template = App::dir(App::config('templates_folder')).'temp'.$template.'.phtml';

					if (is_readable($template))
					{
						ob_start();

						require_once $archivoVista;

						View::$contenido = ob_get_clean();

						require_once $template;
					}
					else
					{
						App::error('No existe o no es legible el template '.$template);
					}
				}
				else 				// Sin template
				{
					require_once $archivoVista;
				}
			}
			else
			{
				App::error('No existe o no es legible la vista '.$archivoVista);
			}
		}
		else
		{
			App::error('Debe proveer un parámetro $vista a View::render($vista).');
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
				$archivoJs = App::root().App::config('views_folder').App::ds().'js'.App::ds().$jsFiles[$i].'.js';

				if (is_readable($archivoJs))
				{
					$_jsFiles .= '<script type = "text/javascript" src = "'.App::base_url().App::config('views_folder').'/js/'.$jsFiles[$i].'.js"></script>'."\n";
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
			App::error('No se ha encontrado ningún archivo .js para la vista.');
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
				$archivoCss = App::root().App::config('views_folder').App::ds().'css'.App::ds().$cssFiles[$i].'.css';

				if (is_readable($archivoCss))
				{
					$_cssFiles .= '<link rel = "stylesheet" type = "text/css" href = "'.App::base_url().App::config('views_folder').'/css/'.$cssFiles[$i].'.css"/>'."\n";
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
			App::error('No se ha pasado ningún archivo .css para la vista.');
		}
	}


	public static function widget($etiqueta = false, $widget = false)
	{
		Widget::render($etiqueta, $widget);
	}


	public static function setWidget($etiqueta = false)
	{
		Widget::set($etiqueta);
	}
	

}
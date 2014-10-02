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

final class Html
{

	private function __construct(){}
	

	public static function a($url = null, $text = null, $title = '')
	{
		if ($url and $text)
		{
			echo '<a href="'.App::url($url).'" title="'.$title.'">'.utf8_decode($text).'</a>'."\n";
		}
		else
		{
			App::error('Debe proveer los parámetros $url y $text a Html::a($url, $text).');
		}
	}


	public static function h1($text = null)
	{
		if ($text) echo '<h1>'.utf8_decode($text).'</h1>'."\n"; else App::error('Debe proveer un parámetro string $text a Html::h1($text).');
	}


	public static function br()
	{
		echo '<br />'."\n";
	}


	public function img($src = false, $alt = false)
	{
		if ($src)
		{
			echo '<img src="'.$src.'" alt="'.$alt.'">';
		}
		else
		{
			App::error('Debe proveer un parámetro $src a Html::img($src, $alt).');
		}
	}

}
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


final class Upload
{
	private static $_inputName   = null;
	private static $_validTypes  = null;
	private static $_maxSizeFile = null;	// En bytes.
	private static $_files       = array();
	private static $_errorUpload = array();
	private static $_flag        = true;
	private static $_error       = array(

	1 => 'El tamaño del archivo ha excedido al máximo establecido en la directiva upload_max_filesize del archivo de configuración de Php.',
	2 => 'El tamaño del archivo ha excedido el valor MAX_FILE_SIZE especificado en el formulario de HTML.',
	3 => 'El archivo se subió incompleto o parcialmente al servidor.',
	4 => 'El archivo no fue subido al servidor.',
	6 => 'No se encuentra el directorio temporal en el servidor.',
	7 => 'Error de escritura del archivo en el servidor.',
	8 => 'PHP-stop downloading the file extension. PHP does not provide a way determine what extension stop file upload.'
	);


	private function __construct()
	{

	}


	/**
	 * Establce la variable FILE que viene por POST a ser usada.
	 * @param string $inputName variable POST por la que viene los archivos.
	 */
	public static function setInputName($inputName = false)
	{
		if (is_string($inputName))
		{
			Upload::$_inputName = $inputName;
		}
		else
		{
			App::error('Debe proveer un parámetro $inputName a Upload::setInputName().');
		}
	}


	/**
	 * Establece los tipos mimes de archivo a subir al server.
	 * @param string $validTypes tipos mimes válidos.
	 */
	public static function setValidTypes($validTypes)
	{
		$mimes = func_get_args();

		foreach ($mimes as $mime) Upload::$_validTypes[] = $mime;
	}


	/**
	 * Establece el tamaño máximo del archivo a subir en bytes.
	 * @param integer $maxSizeFile tamaño máximo en bytes del archivo a subir.
	 */
	public static function setMaxSizeFile($maxSizeFile = 0)
	{
		Upload::$_maxSizeFile = is_integer($maxSizeFile) ? abs($maxSizeFile) : 0;
	}


	/**
	 * Valida y filtra por typo y tamaño los archivos a ser subidos al server.
	 * @return array arreglo con el name y tmp_name de los archivos a ser subidos.
	 */
	public static function getFiles()
	{
		if (Upload::$_flag)
		{
			Upload::$_flag = false;	// Para garantizar que se llene los archivos tan solo una vez.

			if(empty($_FILES))
			{	
				return array();
			}
			else
			{
				if (Upload::$_inputName)
				{	
					foreach($_FILES[Upload::$_inputName]['name'] as $i => $name)
					{
						if ($_FILES[Upload::$_inputName]['error'][$i] == 0)
						{
							/* Valido tipo */

							if (Upload::$_validTypes)
							{	
								if (in_array($_FILES[Upload::$_inputName]['type'][$i], Upload::$_validTypes))
								{
									/* Valido tamaño del archivo */

									if (Upload::$_maxSizeFile)
									{
										if ($_FILES[Upload::$_inputName]['size'][$i] <= Upload::$_maxSizeFile)
										{
											$archivo = array('name' => $name,
															'tmp_name' => $_FILES[Upload::$_inputName]['tmp_name'][$i]);

											if (!in_array($archivo, Upload::$_files))
											{
												Upload::$_files[] = array(	'name' => $name,
																		'tmp_name' => $_FILES[Upload::$_inputName]['tmp_name'][$i]);
											}
										}
										else // ERROR el archivo supera el tamaño requerido.
										{
											Upload::$_errorUpload[] = array('name' => $name, 'error' => Upload::$_error[2]);
										}
									}
									else // No se ha establecido el maxSizeFile.
									{
										return array();
									}
								}
								else // ERROR no es el tipo de archivo correcto.
								{
									Upload::$_errorUpload[] = array('name' => $name, 'error' => 'Tipo de archivo incorrecto.');
								}
							}
							else // No se ha establecido los validTypes.
							{
								return array();
							}
						}
						else // ERROR El archivo tuvo en error al ser subido al server.
						{
							Upload::$_errorUpload[] = array('name' => $name, 'error' => Upload::$_error[$_FILES[Upload::$_inputName]['error'][$i]]);
						}
					}

					return Upload::$_files;
				}
				else
				{
					App::error('Debe establecer un $inputName con Upload::setInputName().');
				}
			}
		}
	}


	/**
	 * Retorna un arreglo con los errores del upload.
	 * @return array retorna un arreglo con los errores del upload.
	 */
	public static function error()
	{
		Upload::getFiles();

		return Upload::$_errorUpload;
	}


	/**
	 * Guarda los archivos válidos proveídos por POST en el disco del server.
	 * @param  string $path   ruta del server donde se guadarán los archivos.
	 * @param  strin $prefix prefijo usado para los nombres de los archivos a guardar.
	 * @return [type]         [description]
	 */
	public static function save($path = null, $prefix = null)
	{
		Upload::getFiles();

		if (Upload::$_files)
		{
			$path   = $path ? App::dir($path) : $dir = App::dir('Data');
			
			$prefix = $prefix ? $prefix : '';

			foreach (Upload::$_files as $i => $item)
			{
				if (!move_uploaded_file($item['tmp_name'], $path . $prefix . $item['name']))
				{
					Upload::$_errorUpload[] = array($item['name'], 'Hubo un error al guardar el archivo en el servidor.');
				}
			}
		}
		else
		{
			return false;
		}
	}


	/**
	 * Borra un archivo del server.
	 * @param  string $pathToFile Ruta + nombre del archivo.
	 * @return boolean True: si se borró con éxito, False: en caso contrario.
	 */
	public static function deleteFile($pathToFile = null)
	{
		if ($pathToFile)
		{
			return (unlink($pathToFile)) ? true : false;
		}
		else
		{
			App::error('Debe proveer un parámetro $pathToFile a Upload::deleteFile().');
		}
    }


    /**
     * Mueve un archivo a una ruta especificada.
     * @param  string  $pathToFile ruta del archivo más su nombre completo.
     * @param  string  $dirToMove  directorio al cual va a ser movido.
     * @param  boolean $del        establece si el archivo será eliminado al moverlo o no.
     * @return boolean             True: si se movió con éxito, False en caso contrario.
     */
    public static function moveFile($pathToFile = null, $dirToMove = null, $del = true)
    {
    	if ($pathToFile)
    	{
    		if ($dirToMove)
	    	{
				$pathinfo = pathinfo($pathToFile);

				$result = copy($pathToFile, $dirToMove.$pathinfo['basename']);

				if($del === true) Upload::deleteFile($pathToFile);

				return $result;
	    	}
	    	else
	    	{
	    		App::error('Debe proveer un parámetro $dirToMove a Upload::moveFile().');
	    	}
    	}
    	else
    	{
    		App::error('Debe proveer un parámetro $pathToFile a Upload::moveFile().');
    	}
	}

}
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

abstract class Database extends PDO
{

	private static $_modeDebug;

	private static $_error;


	public function __construct()
	{
		Database::$_error = false;

		extract(App::config('database_config'));

		Database::$_modeDebug = App::config('debug_mode');

		$dbConexion = !empty($engine) ? $engine : '';
		$dbConexion .= !empty($host) ? ': host='.$host : '';
		$dbConexion .= !empty($port) ? '; port='.$port : '';
		$dbConexion .= !empty($database) ? '; dbname='.$database : '';
		
		$user = !empty($user) ? $user : null;
		$pass = !empty($pass) ? $pass : null;

		try
		{
			parent::__construct($dbConexion, $user, $pass);

			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			Database::$_error = true;

			if (Database::$_modeDebug)
			{
				App::error('Conexión a la Base de Datos: '.$e->getMessage());
			}
        }
	}


	protected function execute($sql = null, Array $params = null)
	{
		try
		{
			if ($sql)
			{
				$db = $this->prepare($sql);

				$db->execute($params);

				if (strtoupper(substr($sql, 0, 6)) === strtoupper('SELECT'))
				{
					return $db->fetchAll(PDO::FETCH_ASSOC);
				}
				else
				{
					return null;
				}
			}
			else
			{
				Database::$_error = true;

				if (Database::$_modeDebug)
				{
					App::error('Debe pasar un string $sql de consulta a Database::sqlExecute($sql).');
				}
			}
		}
		catch(PDOException $e)
		{
			Database::$_error = true;

			if (Database::$_modeDebug)
			{
				App::error('Ejecución SQL en Base de Datos: '.$e->getMessage());
			}
        }
	}


	public function error()
	{
		return Database::$_error;
	}


	public function __destruct()
	{
		unset($this);
	}
}
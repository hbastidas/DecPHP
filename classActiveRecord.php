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

final class ActiveRecord extends Database
{
	private $_sql;
	private $_tabla;
	private $_campos;
	private $_data;
	private $_index;
	private $_total;
	private $_operacion;


	public function __construct($sql = null)
	{
		$sql = trim($sql);

		if (!empty($sql))
		{
			parent::__construct();

			if (strtoupper(substr($sql, 0, 6)) === strtoupper('SELECT'))
			{
				$this->_sql   = $sql;

				$sql_mod      = str_ireplace('from', 'FROM', $sql);
				
				$arraySql     = explode(' ', $sql_mod);
				
				$i_from       = array_search('FROM', $arraySql);
				
				if ($i_from)
				{	
					$this->_tabla = $arraySql[$i_from  + 1];
				}
				else
				{
					App::error('Error de sintaxis en la sentencia SQL enviada a ActiveRecord($sql).');
					exit;
				}
			}
			else
			{
				$this->_sql   = 'SELECT * FROM '.$sql.';';

				$this->_tabla = $sql;
			}

		
			$this->_data      = $this->execute($this->_sql);
			
			$this->_index     = count($this->_data) ? 1 : 0;
			
			$this->_total     = count($this->_data);
			
			$this->_operacion = null;
			
			$this->_campos    = array();
		}
		else
		{
			App::error('Debe proveer una consulta SELECT al objeto ActiveRecord($sql).');
		}
	}


	public function edit()
	{
		$this->_operacion = 'edit';
	}


	public function newe()
	{
		$this->_operacion = 'new';
	}


	public function update($where = null)
	{

		if ($this->_operacion == 'edit')
		{
			if ($where)
			{
				$sql = 'UPDATE '.$this->_tabla.' SET ';

				foreach ($this->_campos as $campo => $valor)
				{
					$sql .= trim($campo, ':').' = '.$campo.', ';
				}

				$sql = trim($sql, ', ');

				$sql .= ($where) ? ' WHERE '.trim($where, ';').';' : ';';
			}
			else
			{
				App::error('Para actualizar debe proveer un parámetro $where a ActiveRecord->update().');
			}
		}
		elseif ($this->_operacion == 'new')
		{
			$sql    = 'INSERT INTO '.$this->_tabla.' ';	
			$names  = '';
			$values = '';

			foreach ($this->_campos as $campo => $valor)
			{
				$names  .= trim($campo, ':').', ';
				$values .= $campo.', ';
			}

			$sql .= '('.trim($names, ', ').') VALUES ('.trim($values, ', ').');';
		}
		else
		{
			App::error('ActiveRecord->update() sin antes ActiveRecord->edit() o ActiveRecord->newe().');
		}

		$db->execute($sql, $this->_campos);

		$this->requery();	
	}


	public function next()
	{
		if ($this->_index <= $this->_total) $this->_index = $this->_index + 1;
	}


	public function requery()
	{	
		$this->_data = $this->execute($this->_sql);

		$this->_index = count($this->_data) ? 1 : 0;

		$this->_total = count($this->_data);

		$this->_operacion = null;

		$this->_campos = array();
	}


	public function delete($where = null)
	{
		if ($where)
		{
			$sql = 'DELETE * FROM '.$this->_tabla.' WHERE '.trim($where, ';').';';

			$this->execute($sql);

			$this->requery();
		}
		else
		{
			App::error('Para eliminar debe proveer un parámetro $where a ActiveRecord->delete().');		
		}
	}


	public function first()
	{
		if (count($this->_total) > 0) $this->_index = 1;	
	}


	public function last()
	{
		if (count($this->_total) > 0) $this->_index = $this->_total;
	}


	public function eof()
	{
		return $this->_index > $this->_total;
	}


	public function __set($campo, $valor)
	{
		if (array_key_exists($campo, $this->_data[$this->_index - 1]))
		{
			$this->_campos[':'.$campo] = $valor;
		}
		else
		{
			App::error('El campo "'.$campo.'" no pertenece al conjunto de datos.');
		}	
	}


	public function __get($campo)
	{
		if (array_key_exists($campo, $this->_data[$this->_index - 1]))
		{
			return $this->_data[$this->_index - 1][$campo];
		}
		else
		{
			App::error('El campo "'.$campo.'" no pertenece al conjunto de datos.');
		}
	}


	public function dataSet()
	{
		return $this->_data;
	}


	public function __destruct()
	{
		unset($this);
	}

}
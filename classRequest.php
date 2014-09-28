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

final class Request
{

    private $_controlador;

    private $_metodo;

    private $_parametros;


    public function __construct()
    {
        if(isset($_GET['url']))
        {
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

            $url = explode('/', $url);

            $url = array_filter($url);
            
			$this->_controlador = strtolower(array_shift($url));

			$this->_metodo      = strtolower(array_shift($url));

			$this->_parametros  = $url;
        }       
        
        if(!$this->_controlador)
        {
            $this->_controlador = App::config('default_controller');
        }
        
        if(!$this->_metodo)
        {
            $this->_metodo = 'index';
        }
        
        if(!isset($this->_parametros))
        {
            $this->_parametros = array();
        }
    }


    public function getControlador()
    {
        return ucfirst(strtolower($this->_controlador));
    }

    
    public function getMetodo()
    {
        return $this->_metodo;
    }

    
    public function getParametros()
    {
        return $this->_parametros;
    }

}
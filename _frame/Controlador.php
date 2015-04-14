<?php
/************************************
* Controlador de API REST
*************************************
*************************************
Rev:
*************************************
@ivanmiranda: 1.0
************************************/
abstract class Controlador{
	protected $_modelo;
    protected $_token;
	protected $_metodo;
	protected $_endpoint;
	protected $_parametros;
#El API se contruye con salida JSON y, a menos que al crear la clase se indique lo contrario
# se valida que el token calculado (en base al host que origina la peticion)
# coincida con el que se entrega como uno de los parametros de entrada
# para poder consumir cualquier operacion
	public function __construct($ambito){
        #Si existe un modelo con el mismo nombre del controlador, este carga por default
        $this->_modelo = $this->cargaModelo(str_replace("Controlador", "", get_class($this)));

		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-access-token");
        header("Content-Type: application/json; charset=utf8");
		$this->_metodo = $_SERVER['REQUEST_METHOD'];
		if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    	   $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
		}
        if($this->_metodo == "OPTIONS") {
            $this->respuesta("OPTIONS");
            exit;
        }
        #Cuando se recibe un token, se procesa y regresa el contenido
        #o un FALSE en caso de que ya no sea un token válido
        $this->_token = false;
        if(isset($_SERVER["HTTP_X_ACCESS_TOKEN"])) {
            $this->_token = Framework::validarToken(Framework::limpiarEntrada($_SERVER["HTTP_X_ACCESS_TOKEN"]));
        }

        ##Estando en el ambito privado, debe existir un token valido de autenticación
        if($ambito == SCOPE_PRIVATE && !$this->_token) {
            #echo "Debes enviar un token valido para realizar esta operacion";
            throw new SecurityException("Debes enviar un token valido para realizar esta operacion");
        }
        if($ambito == SCOPE_PRIVATE && !lib_seguridad::index($this->_token["datos"])) {
            #echo "No tienes permiso para realizar esta operacion";
            throw new SecurityException("No tienes permiso para realizar esta operacion");
        }

       $this->_parseaParametros();
	}

	#Cargar cualquier modelo necesario en nuestras operaciones
	protected function cargaModelo($modelo) {
		$rutaModelo = APP_PATH.'_modelos/'.$modelo.'.php';
		$modelo = $modelo.'Modelo';
		if(is_readable($rutaModelo)) {
			require_once $rutaModelo;
			$objModelo = new $modelo;
			return $objModelo;
		}else {
			return null;
		}
	}

	protected function validaToken() {
		if(!array_key_exists('_token', $this->_parametros)) {
        	$this->respuesta('', 401);
        }elseif($this->_parametros['_token'] != $this->_token_calculado) {
        	$this->respuesta('', 401);
        }
        exit();
	}

	protected function respuesta($data, $status = 200) {
		header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		echo json_encode(Framework::parseaUTF8($data));
	}

    private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            401 => 'No tienes permiso',
            404 => 'No encontrado',   
            405 => 'Metodo no permitido',
            500 => 'Error interno',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }

    private function _parseaParametros() {
        $_parametros = array();
 
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $_parametros);
        }
 
        //Lo que se ha recibido por POST, GET
        $_contenido = file_get_contents("php://input");
        $_contenido_tipo = false;
        if(isset($_SERVER['CONTENT_TYPE'])) {
            $_contenido_tipo = $_SERVER['CONTENT_TYPE'];
        }
        switch($_contenido_tipo) {
            case "application/json":
                $_parametros = json_decode($_contenido, TRUE);
                $this->_formato = "json";
                break;
            case "application/x-www-form-urlencoded":
                parse_str($_contenido, $postvars);
                foreach($postvars as $field => $value) {
                    $_parametros[$field] = Framework::limpiarEntrada($value);
                }
                $this->format = "html";
                break;
            default:
                parse_str($_contenido, $postvars);
                foreach($postvars as $field => $value) {
                    $_parametros[$field] = Framework::limpiarEntrada($value);
                }
                $this->format = "html";
                #$this->respuesta("Esta API funciona sobre una plataforma de contenidos JSON", 500);
                break;
        }
        $this->_parametros = $_parametros;
    }
}
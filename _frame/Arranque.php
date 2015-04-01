<?php
/************************************
* Control general de arranque del
* Framework
*************************************
*************************************
Rev:
*************************************
@ivanmiranda: 1.0
************************************/
class Arranque{
	public static function ejecuta(Peticion $peticion){
		$controlador = $peticion->getControlador()."Controlador";
		$rutaControlador = APP_PATH.'_controladores/'.$peticion->getControlador().'.php';
		$metodo = $peticion->getMetodo();
		$argumentos = $peticion->getArgumentos();
		if(file_exists($rutaControlador))
			require_once $rutaControlador;
		$objControlador = new $controlador($peticion->getAmbito());
		if(is_callable(array($objControlador, $metodo.$peticion->getMetodoHTTP()))) {
			if(!empty($argumentos)){
				call_user_func_array(array($objControlador, $metodo.$peticion->getMetodoHTTP()), $argumentos);
			} else {
				call_user_func(array($objControlador, $metodo.$peticion->getMetodoHTTP()));
			}
		} else {
			throw new FrameworkException("Debes enviar un token valido para realizar esta operacion");
		}
	}
}
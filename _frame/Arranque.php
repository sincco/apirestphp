<?php
class Arranque{
	public static function ejecuta(Peticion $peticion, $seguridad = null){
		$controlador = $peticion->getControlador()."Controlador";
		$rutaControlador = APP_PATH.'_controladores/'.$peticion->getControlador().'.php';
		$metodo = $peticion->getMetodo();
		$argumentos = $peticion->getArgumentos();
		require_once $rutaControlador;
		$objControlador = new $controlador;
		if(is_callable(array($objControlador, $metodo.$peticion->getMetodoHTTP()))) {
			if(!empty($argumentos)){
				call_user_func_array(array($objControlador, $metodo.$peticion->getMetodoHTTP()), $argumentos);
			} else {
				call_user_func(array($objControlador, $metodo.$peticion->getMetodoHTTP()));
			}
		} else {
			header("Content-Type: application/json; charset=utf8");
			header("HTTP/1.1 404 No existe la ruta ".$peticion->getControlador()."->".$metodo.$peticion->getMetodoHTTP());
		}
	}
}
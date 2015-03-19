<?php 
class loginControlador extends Controlador {

	public function indexPOST() {
		//print_r(apache_request_headers());
		//print_r($_SERVER);
		$this->_params["datos"]["password"] = "";
		$this->respuesta(array("token"=>Framework::crearToken($this->_params["datos"])));
	}

}
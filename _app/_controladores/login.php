<?php 
class loginControlador extends Controlador {

	public function indexPOST() {
		$this->respuesta(array("token"=>Framework::crearToken($this->_params["datos"])));
	}

	public function indexGET() {
		if($this->_token) {
			$this->respuesta(array("mensaje"=>"Acceso valido"));
		} else {
			$this->respuesta(array("mensaje"=>"Token no valido"), 400);
		}
	}

}
<?php
class indexControlador extends Controlador {
	public function indexGET() {
		$this->respuesta(array("Mensaje"=>APP_NAME." v.".APP_VERSION));
	}

	public function indexPOST() {
		$this->respuesta(array("token"=>Framework::crearToken($this->_parametros)));
	}

	public function indexPUT() {
		$this->respuesta(array("parametros"=>$this->_parametros));
	}
}
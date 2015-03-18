<?php 
class facturasControlador extends Controlador {

	public function leerGET($id) {
		$this->respuesta($this->_modelo->leer($id));
	}

	public function leerPOST() {
		$this->respuesta($this->_modelo->insertar($this->_params["datos"]));
	}
}
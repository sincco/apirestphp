<?php 
class productosControlador extends Controlador {
	public function catalogoGET() {
		$this->respuesta($this->_modelo->leer());
	}
}
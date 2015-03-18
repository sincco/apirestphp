<?php 
class indexControlador extends Controlador {
	public function indexGET() {
		$this->respuesta(array("Mensaje"=>APP_NAME." v.".APP_VERSION));
	}
}
<?php
#Clase para control de los elementos de seguridad del sistema
class lib_seguridad extends Seguridad {
	private $_db;
	#Manejador de BD
	public function __construct() { $this->_db = new BaseDatos(); }
#--------Metodo obligado por el Framework
	#Desde aqui se hacen todas las validaciones de un acceso al sistema
	public function Validar_Acceso ($controlador ="", $modelo = "") {
		if($controlador == "login")
			return true;
		return isset($_SESSION['__token']);
	}
#--------Metodo obligado por el Framework
	#Esto se lanza cuando hay una exepción de seguridad
	public function Error_Seguridad ($error) {
		switch($error->getCode()) {
			case 1:
				echo "Se ha intentado ejecutar un formulario desde otro sitio";
				break;
			case 2:
				echo "No tienes acceso a ".$error->getMessage();
				break;
		}
	}
#Retorna el identificador que permite establecer si existe un acceso valido al sistema
	public function existe() {
		return $__sesion->get('__token');
    }
#Inicia los valores de control de seguridad en la sesion
    public function iniciar() {
        session_regenerate_id();
    }
}
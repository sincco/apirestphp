<?php
#Ejemplo para manejar un control de seguridad en base al token recibido por la API
class lib_seguridad extends Seguridad {
	public function index($datos) {
		if($datos["correo"] == "pa.ivan.miranda@gmail.com")
			return true;
		else
			return false;
	}
}
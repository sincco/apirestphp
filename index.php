<?php
#------------------------------
#-- NO TOCAR
#------------------------------
#Desde aqui se invoca la carga de elementos del framework
#de las librerias y todo lo relacionado con la APP
#------------------------------

require_once './_frame/_boot.php'; #Carga de rutinas del framework

try {
	Arranque::ejecuta(new Peticion); #Se lanza la petición
} catch(FrameworkException $e) { #Se detectó un error en el framework
	Logs::procesa($e);
	header("HTTP/1.1 500 ".$e->getMessage());
} catch(SecurityException $e) {	#Se determinó que no se cumplen los requisitos de seguridad
	header("HTTP/1.1 401 ".$e->getMessage());
} catch(Exception $e) { #Cualquier otro error en el sistema
	Logs::procesa($e);
	header("HTTP/1.1 500 ".$e->getMessage());
}
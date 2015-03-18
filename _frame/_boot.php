<?php
###############################
#NO TOCAR
###############################
require_once './config.php'; #<-- NO TOCAR

#Para separar el manejo de errores
class DBException extends Exception {};
class FrameworkException extends Exception {};
class SecurityException extends Exception {};

#Controlador por default
define('DEFAULT_CONTROLLER', 'index');
#Directorios del sistema
define('FRAME_PATH','./_frame/');
define('LIBS_PATH','./_libs/');
#Directorios de la aplicacion
define('APP_PATH','./_app/');
#Archivo de log
define('DEV_LOGFILE', './_logs/'.date('YW').'.txt');

#Carga automatica de funciones
function __autoload($class_name) {
  #Para los archivos propios del framework
  $file = FRAME_PATH.$class_name.'.php';
  if (file_exists($file)) {
  	require_once $file;
  } else {
  #Para las extensiones del sistema
  	$file = LIBS_PATH.$class_name.'.php';
	  if (file_exists($file))
	  	require_once $file;
	  else
	  	throw new FrameworkException("No se pudo encontrar la clase {$class_name}");
  }
}
#La clase de seguridad para toda la aplicación
$__seguridad = Framework::Carga_Seguridad();
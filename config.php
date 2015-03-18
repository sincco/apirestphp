<?php
#/////////////////////////////////
# Configuración de la aplicación
#/////////////////////////////////

#-----------------------
#Constantes del sistema
#-----------------------
define('APP_NAME', 'REST PHP');
define('APP_VERSION', '0.1');
define('APP_COMPANY', 'sincco.com');
define('APP_KEY', '1628387158543db8f067f578.01851196'); #llave de encripcion de datos

#------------------------
#Conexion a DB de la APP
#------------------------
define('DB_HOST', '');
define('DB_USER', 'SYSDBA');
define('DB_PASS', 'masterkey');
define('DB_NAME', 'C:\\SAE60EMPRE01.FDB');
define('DB_CHAR', 'utf8');
define('DB_MANAGER', 'firebird');

#------------------------------
#Manejo de seguridad en la APP
#------------------------------
define('SECURITY_ACTIVE', FALSE); #Define si se activa el control de seguridad en la APP
define('SECURITY_CLASS', 'lib_seguridad'); 	#La clase que valida la seguridad de acceso, se extiende de Seguridad
											#...y debe estar alojada en _libs
											#Debe tener, por definicion un metodo llamado Validar_Acceso 
											#y otro Error_Seguridad

#/////////////////////////////////
# Configuración del framework
#/////////////////////////////////

#------------------------------
#Constantes del desarrollador
#------------------------------
define('DEV_SHOWERRORS', TRUE); #muestra pantalla de errores en el sistema
define('DEV_SHOWPHPERRORS', TRUE); #muestra errores de PHP
define('DEV_LOG', TRUE); #escribe un archivo en la carpeta _logs

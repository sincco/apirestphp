<?php
#/////////////////////////////////
# Configuración de la API
#/////////////////////////////////

#-----------------------
#Constantes del sistema
#-----------------------
define('APP_NAME', 'REST PHP');
define('APP_VERSION', '0.1');
define('APP_COMPANY', 'sincco.com');
define('APP_KEY', '1628387158543db8f067f578.01851196'); #llave de encripcion de datos

#------------------------
#Conexion a DB de la aPI
#------------------------
define('DB_HOST', '');
define('DB_USER', 'SYSDBA');
define('DB_PASS', 'masterkey');
define('DB_NAME', 'C:\\SAE60EMPRE01.FDB');
define('DB_CHAR', 'utf8');
define('DB_MANAGER', 'firebird');

#------------------------------
#Manejo de seguridad en la API
#------------------------------
define('SCOPE_PRIVATE', 'privado'); #Define la ruta para la sección privada
														#se debe usar una ruta distinta al valor aqui definido para estar en
														#la sección pública

#/////////////////////////////////
# Configuración del framework
#/////////////////////////////////

#------------------------------
#Constantes del desarrollador
#------------------------------
define('DEV_SHOWERRORS', TRUE); #muestra pantalla de errores en el sistema
define('DEV_SHOWPHPERRORS', TRUE); #muestra errores de PHP
define('DEV_LOG', TRUE); #escribe un archivo en la carpeta _logs

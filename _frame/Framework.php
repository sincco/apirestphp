<?php
/************************************
* Funciones comunes en el Framework
*************************************
*************************************
Rev:
*************************************
@ivanmiranda: 1.0
************************************/
class Framework {
#------------------------------------
#Cargar un modelo
#------------------------------------
	public static function cargaModelo($modelo) {
		$rutaModelo = APP_PATH.'_modelos/'.$modelo.'.php';
		$modelo = $modelo.'Modelo';
		if(is_readable($rutaModelo)) {
			require_once $rutaModelo;
			return new $modelo;
		}
		return false;
	}

	public static function parseaCampos($datos) {
		$res = array();
		foreach ($datos as $campo => $valor) {
			array_push($res, $campo." = '".$valor."'");
		}
		return implode(", ", $res);
	}

	public static function parseaUTF8($contenido){
		if(is_array($contenido))
			foreach ($contenido as $key => $value)
				$contenido[$key] = Framework::parseaUTF8($value);
		else
			$contenido = utf8_decode($contenido);
		return $contenido;
	}

	public static function limpiarEntrada($valor) {
	  $_busquedas = array(
	    '@<script[^>]*?>.*?</script>@si',   #Quitar javascript
	    '@<[\/\!]*?[^<>]*?>@si',            #Quitar html
	    '@<style[^>]*?>.*?</style>@siU',    #Quitar css
	    '@<![\s\S]*?--[ \t\n\r]*>@'         #Quitar comentarios multilinea
	  );
	  if (is_array($valor)) {
	  	foreach ($valor as $_key => $_value) {
	  		$valor[$_key] = Framework::limpiarEntrada($_value); #Recursivo para arreglos
	  	}	  	
	  }else {
	    $valor = preg_replace($_busquedas, '', $valor);
	    $valor = filter_var($valor,FILTER_SANITIZE_STRING);
	    if (get_magic_quotes_gpc()) {
				$valor = stripslashes($valor);
			}
		}
		return $valor;
	}
#------------------
#Encripcion AES256
#------------------
	public static function encrypt($data, $key = APP_KEY) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
		return $encrypted;
	}
	public static function decrypt($data, $key = APP_KEY) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);
		return $decrypted;
	}
#------------------------------------
#Traducir fecha al español
#------------------------------------
	public static function fechaEsp($fecha) {
		$anio = date("y", $fecha);
		$dia = date("j", $fecha);
		$mes=date("F", $fecha);
		if ($mes=="January") $mes="Enero";
		if ($mes=="February") $mes="Febrero";
		if ($mes=="March") $mes="Marzo";
		if ($mes=="April") $mes="Abril";
		if ($mes=="May") $mes="Mayo";
		if ($mes=="June") $mes="Junio";
		if ($mes=="July") $mes="Julio";
		if ($mes=="August") $mes="Agosto";
		if ($mes=="September") $mes="Setiembre";
		if ($mes=="October") $mes="Octubre";
		if ($mes=="November") $mes="Noviembre";
		if ($mes=="December") $mes="Diciembre";
		return "{$dia} {$mes} {$anio}";
	}

	public static function crearToken($datos, $horas = 3) {
		$datos['exp'] = time($horas * 60 * 60);
		return self::Encrypt(http_build_query($datos));
	}

	public static function validarToken($token) {
		$token = self::Decrypt($token);
		parse_str(Framework::limpiarEntrada($token), $_datos);
		if(isset($_datos["exp"])) {
			if($_datos["exp"] > time()) {
				return FALSE;
			} else {
				return $_datos;
			}
		} else {
			return FALSE;
		}
	}

}
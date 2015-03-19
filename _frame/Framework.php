<?php
/*******************************
Funciones comúnes del framework
*******************************/
class Framework {
#------------------------------------
#Cargar un modelo
#------------------------------------
	public static function CargaModelo($modelo) {
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

	public static function Carga_Seguridad() {
		#Si la seguridad esta activa en la configuración...
		if(SECURITY_ACTIVE) {
			$clase_seguridad = SECURITY_CLASS;
			require_once './_libs/'.$clase_seguridad.'.php';
			return @ new $clase_seguridad; #...se crea en base a la clase definida
		}
		else {
			return null;
		}
	}

	public static function limpiarEntrada($contenido){
		if(is_array($contenido))
			foreach ($contenido as $key => $value) {
				$value = trim(strip_tags($value));
				$contenido[$key] = filter_var($value,FILTER_SANITIZE_STRING);
			}
		else {
			$contenido = trim(strip_tags($contenido));
			$contenido = filter_var($contenido,FILTER_SANITIZE_STRING);
		}
		return $contenido;
	}

	public static function ParseaUTF8($contenido){
		if(is_array($contenido))
			foreach ($contenido as $key => $value)
				$contenido[$key] = utf8_decode($value);
		else
			$contenido = utf8_decode($contenido);
		return $contenido;
	}
#------------------
#Encripcion AES256
#------------------
	public static function Encrypt($data, $key = APP_KEY) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
		return $encrypted;
	}
	public static function Decrypt($data, $key = APP_KEY) {
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
	public static function FechaEsp($fecha) {
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
		if($_datos["exp"] > time()) {
			return FALSE;
		} else {
			return $_datos;
		}
	}

}
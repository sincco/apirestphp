<?php
/************************************
* Manejo de log
*************************************
*************************************
Rev:
*************************************
@ivanmiranda: 1.0
************************************/
class Logs {
	public static function procesa($e){
		if(DEV_SHOWERRORS){
			if(stripos($e->getMessage(), "SQLSTATE") > -1)
				Logs::pantalla_bd($e);
			else
				Logs::pantalla($e);
		} else {
			header("Location: ".ERROR_404);
		}
		if(DEV_LOG)
			Logs::escribe($e);
	}
	private static function escribe($e){
		if(!file_exists(DEV_LOGFILE))
			mkdir("./_logs");
		$log_file = fopen(DEV_LOGFILE, 'a+');
		fwrite($log_file, date("mdGis").'::'.$e->getMessage()."(".$e->getCode().")\r\n");
		fwrite($log_file, "--> ".$e->getFile()."::".$e->getLine()."\r\n");
		fwrite($log_file, "-->--> ".$e->getTraceAsString()."\r\n\r\n");
		fwrite($log_file,"URL: http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']."\r\n");
		fwrite($log_file, "PHP ".phpversion()." - ".PHP_OS."(".PHP_SYSCONFDIR." ".PHP_BINARY.")\r\n");
		fwrite($log_file,"--------------------------------------------\r\n");
		fclose($log_file);
	}
	private static function pantalla($e){
		echo "<html><head><style>h1{font-family:Arial, Helvetica, sans-serif; font-size:16px;} body{font-family:Courier; font-size:12px;}</style></head>";
		echo "<h1>".$e->getMessage()."(".$e->getCode().")</h1>";
		echo "<hr/>apirestPHP - ".APP_NAME." - ".APP_COMPANY;
		echo "</html>";
	}
	private static function pantallaBD($e){
		echo "<html><head><style>h1{font-family:Arial, Helvetica, sans-serif; font-size:16px;} body{font-family:Courier; font-size:12px;}</style></head>";
		echo "<h1>".$e->getMessage()."(".$e->getCode().")</h1>";
		echo $e->getFile()."::".$e->getLine()."<hr/>";
		echo "<hr/>apirestPHP - ".APP_NAME." - ".APP_COMPANY;
		echo "</html>";
	}
}
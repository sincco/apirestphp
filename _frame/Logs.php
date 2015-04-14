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
		if(DEV_LOG)
			Logs::escribe($e);
		Logs::pantalla($e);
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
		header("HTTP/1.1 500 ".$e->getMessage());
		echo json_encode(array("Mensaje"=>$e->getMessage(),"status"=>500));
		die;
	}
}
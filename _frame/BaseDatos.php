<?php
/************************************
* Control de BD en el Framework
*************************************
*************************************
Rev:
*************************************
@ivanmiranda: 1.0
@ivanmiranda: 1.5
    Soporte para BD firebird
************************************/
 class BaseDatos {  
    static private $PDOInstance; 
   #Conexion a la BD desde la creación de la clase
    public function __construct() {
        if(!self::$PDOInstance) {
	        try {
                $parametros = array();
                if(DB_MANAGER == "mysql")
                    $parametros = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '. DB_CHAR);
                else
                    $parametros = array();
                switch (DB_MANAGER) {
                    case 'sqlsrv':
                        self::$PDOInstance = new PDO(DB_MANAGER.":Server=".DB_HOST.";",
                            DB_USER, DB_PASS, $parametros);
                        break;
                    case 'mysql':
                        self::$PDOInstance = new PDO(DB_MANAGER.":host=".DB_HOST.";dbname=".DB_NAME,
                            DB_USER, DB_PASS, $parametros);
                        break;
                    case 'firebird':
                        $parametros = array(
                            PDO::FB_ATTR_TIMESTAMP_FORMAT,"%d-%m-%Y",
                            PDO::FB_ATTR_DATE_FORMAT ,"%d-%m-%Y"
                        );
                        self::$PDOInstance = new PDO(DB_MANAGER.":dbname=".DB_HOST.DB_NAME, DB_USER, DB_PASS, $parametros);
                        break;
                    default:
                        self::$PDOInstance = new PDO(DB_MANAGER.":host=".DB_HOST.";dbname=".DB_NAME,
                            DB_USER, DB_PASS);
                        break;
                }
			} catch (PDOException $e) { 
				Logs::procesa($e);
			}
    	}
      	return self::$PDOInstance;    	    	
    }
	 
	public function beginTransaction() {
		return self::$PDOInstance->beginTransaction();
	}

	public function commit() {
		return self::$PDOInstance->commit();
	}

    public function errorCode() {
    	return self::$PDOInstance->errorCode();
    }
    
    public function errorInfo() {
    	return self::$PDOInstance->errorInfo();
    }
   #Ejecucion de querys, con soporte para pase de parametros en un arreglo
    public function query($consulta, $valores = array()) {
        $resultado = false;
        if($statement = self::$PDOInstance->prepare($consulta)) {
            if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)) {
                $campo = array_pop($campo);
                foreach($campo as $parametro){
                    $statement->bindValue($parametro, $valores[substr($parametro,1)]);
                }
            }
            try {
                if (!$statement->execute())
                    throw new PDOException("[SQLSTATE] ".$statement->errorInfo()[2],$statement->errorInfo()[1]);
                $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement->closeCursor();
			}
			catch(PDOException $e) {
				Logs::procesa($e);
				return false;
	        }
	        return $resultado;
	    }
    }
    #Ejecucion de querys, para uso en el propio framework (construccion de modelos automaticos)
    public function raw_query($consulta, $valores = array()) {
        $resultado = false;
        if($statement = self::$PDOInstance->prepare($consulta)) {
            if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)) {
                $campo = array_pop($campo);
                foreach($campo as $parametro) {
                    $statement->bindValue($parametro, $valores[substr($parametro,1)]);
                }
            }
            try {
                if (!$statement->execute())
                    return array();
                $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
                if(count($resultado) == 1)
                    $resultado = $resultado[0];
                $statement->closeCursor();
            }
            catch(PDOException $e) {
                Logs::procesa($e);
                return false;
            }
            return $resultado;
        }
    }
    #Ejecucion de INSERT
    public function insert($consulta, $valores = array()) {
        $resultado = false;
        if($statement = self::$PDOInstance->prepare($consulta)) {
            if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)) {
                $campo = array_pop($campo);
                foreach($campo as $parametro){
                    $statement->bindValue($parametro, $valores[substr($parametro,1)]);
                }
            }
            try {
                if (!$statement->execute())
                    throw new PDOException("[SQLSTATE] ".$statement->errorInfo()[2],$statement->errorInfo()[1]);
                $resultado = self::$PDOInstance->lastInsertId();
            }
            catch(PDOException $e) {
                Logs::procesa($e);
                return false;
            }
            return $resultado;
        }
    }
}

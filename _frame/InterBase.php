<?php
/************************************
* Control de BD en el Framework     *
*************************************
*************************************
Rev:
*************************************
@ivanmiranda: 1.0
************************************/
class InterBase {  
    static private $PDOInstance; 
    #Conexion a la BD desde la creación de la clase
    public function __construct() {
        if(!self::$PDOInstance) {
            self::$PDOInstance = ibase_connect(DB_HOST,
                DB_USER, DB_PASS);
        }
        if(self::$PDOInstance)
            return self::$PDOInstance;
        else {
            throw new PDOException("[SQLSTATE] ".ibase_errmsg());
        }
    }

    #Ejecucion de querys, con soporte para pase de parametros en un arreglo
    public function query($consulta) {
    $resultado = false;
    if($statement = ibase_prepare(self::$PDOInstance,$consulta)) {
    $resultado = ibase_execute($statement);
    return ibase_fetch_assoc($resultado);
    }
    }
}
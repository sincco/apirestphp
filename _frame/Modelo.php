<?php
#Clase generica de Modelo, cualquier instancia derivada se conecta automaticamente a la BD
abstract class Modelo{
	protected $_db;
	protected $_campos = array();
	public function __construct(){
	#Siempre se instancia la base de datos
		$this->_db = new BaseDatos();
	#Si el modelo se llama como una tabla en la BD...
		if(DB_MANAGER == "mysql") {
			$_tabla = $this->_db->raw_query("SELECT * FROM ".str_replace("Modelo", "", get_class($this))." limit 0,1;");
			$this->_campos = array_keys($_tabla);
		#...se asignan los campos como variables del objeto
			foreach ($this->_campos as $key => $value)
				$this->$value = null;
		}
	}
}
<?php
class facturasModelo extends Modelo {
	public function leer($id = '') {
		if(strlen($id) > 0)
			return $this->_db->query("SELECT * FROM factf01 WHERE CVE_DOC = :id;", array("id"=>$id));
		else
			return $this->_db->query("SELECT * FROM factf01;");
	}

	public function insertar($datos) {
		//"INSERT INTO factf01 SET CVE_DOC = :CVE_DOC, CVE_CLPV = :CVE_CLPV, STATUS = :STATUS, DAT_MOSTR = :DAT_MOSTR;"
		$res= "INSERT INTO factf01 SET ".FrameWork::parseaCampos($datos).";";
		return $res;
	}

}
<?php
class productosModelo extends Modelo {
	public function leer($id = '') {
		if(strlen($id) > 0)
			return $this->_db->query("SELECT CVE_ART,DESCR,UNI_VENTA,PRECIO FROM factf01 WHERE CVE_DOC = '$id';");
		else
			return $this->_db->query("SELECT CVE_ART,DESCR,UNI_VENTA,PRECIO FROM factf01;");
	}
}
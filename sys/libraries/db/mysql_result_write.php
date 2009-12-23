<?php if(!defined('OK')) die('<h1>403</h1>');

class DB_mysql_Result_Write  extends DB_Result_Write {


	public function _rows_affected(){
		return @mysql_affected_rows($this->resource);
	}

}
?>

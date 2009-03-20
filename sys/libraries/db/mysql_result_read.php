<?php if(!defined('OK')) die('<h1>403</h1>');

class DB_mysql_Result_Read  extends DB_Result_Read {


	protected function _rows(){
		return @mysql_num_rows($this->resource);
	}
	
	protected function _fields(){}
	
	protected function _free(){
		mysql_free_result($this->resource);
		$this->resource = null;
	}
	
	// Moves the internal pointer to the desired offsset.
	// we call this internally to make sure the result set starts at zero.
	protected function _offset($n=0){
		return mysql_data_seek($this->resource, $n);
	}
	
	protected function _fetch_obj(){
		return mysql_fetch_object($this->resource);
	}
	
	protected function _fetch_arr(){
		return mysql_fetch_assoc($this->resource);
	}
	
}

?>
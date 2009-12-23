<?php if(!defined('OK')) die('<h1>403</h1>');

class DB_Result_Write {

	public $resource = null;

	public function __construct($RS=false){
		echo var_dump($RS);
		if (!is_resource($RS)) return false;
		$this->resource = $RS;
	}

	public function rows_affected(){
		if (is_resource($this->resource))	 return $this->_rows_affected();
		return true;
	}

}
?>

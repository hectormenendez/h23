<?php if(!defined('OK')) die('<h1>403</h1>');

class DB_Result_Read {

	public $resource 	= null;
	public $object		= array();
	public $array 		= array();
	public $rows		= 0;
	public $curr		= 0;

	public function __construct($RS=false){
		if (!is_resource($RS)) return false;
		$this->resource = $RS;
		// propagate the result to variables.
		$this->object = $this->_result_obj();
		$this->array  = $this->_result_arr();
		$this->rows   = $this->_rows();
	}

	// wrapper to return the result as an object or an array (default object)
	public function result($type=false){
		// if the resource isn't set, it means  we're dealing with the cache version
		// of this class, and the variables are already set.
		if (!is_resource($this->resource)) return (!$type)? $this->object : $this->array;
		return (!$type) ? $this->_result_obj() : $this->_result_arr();
	}

	// wrapper for row functions.
	public function row($n=false, $type=false){
		// if $n isn't a number, return the first element
		if (!is_int($n)) $n=0;
		// if type is set to true return an array instead of an object.
		return (!$type)? $this->_row_obj($n) : $this->_row_arr($n);
	}

	// this method will be replaced on the platform specific library
	public function rows(){
		return $this->rows;
	}

	public function row_first($type=false){
		if (count($res = $this->result($type)) == 0) return $res;
		return $res[0];
	}

	public function row_last($type=false){
		if (count($res = $this->result($type)) == 0) return $res;
		return $res[count($res)-1];
	}

	public function row_next($type=false){
		if (count($res = $this->result($type)) == 0) return $res;
		if (isset($res[$this->curr+1])) ++$this->curr;
		return $res[$this->curr];
	}

	public function row_prev($type=false){
		if (count($res = $this->result($type)) == 0) return $res;
		if (isset($res[$this->curr-1])) --$this->curr;
		return $res[$this->curr];
	}

	public function fields(){}

	public function free(){
		if (is_resource($this->resource)) $this->_free();
		return true;
	}

	/*************
	 *  SUPPORT  *
	 *************/

	// returns a single result row, object version.
	private function _row_obj($n=0){
		if (count($this->object)==0) return $this->object;
		if ($n!=$this->curr && isset($this->object[$n])) $this->curr = $n;
		return $this->object[$this->curr];
	}

	// returns a single result row, array version.
	private function _row_arr($n=0){
		if (count($this->array)==0) return $this->array;
		if ($n!=$this->curr && isset($this->array[$n])) $this->curr = $n;
		return $this->array[$this->curr];
	}

	// query result as object
	protected function _result_obj(){
		if (($prep = $this->_preres($this->object))!==true) return $prep;
		while ($row = $this->_fetch_obj()){ $this->object[] = $row; }
		return $this->object;
	}

	// query result as associative array
	protected function _result_arr(){
		if (($prep = $this->_preres($this->array))!==true) return $prep;
		while ($row = $this->_fetch_arr()){ $this->array[] = $row; }
		return $this->array;
	}

	// make some checks and preparation before returning the result.
	protected function _preres($return){
		// if there's already a result object, return it.
		if (count($return)>0) return $return;
		// if query caching is on,  there won't be resources available
		// so we return an empty array to avoid errors.
		if (!$this->resource || $this->rows() == 0) return array();
		// we make sure the internal pointer is at the start.
		$this->_offset(0);
		return true;
	}

}
?>

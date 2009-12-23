<?php if(!defined('OK')) die('<h1>403</h1>');

 class DB_mysql_driver extends DB_Driver {

	private static $_HCK = true;	// wheter to use the mysql "delete hack" which allows the number of
									// affected rows to be shown. uses preg_replace adding processing time.
	// database specific syntax;
	protected static $_count = 'SELECT COUNT(*) AS ';
	protected static $_rand  = ' RAND()';

	protected static $_types = array('SELECT', 'SHOW', 'DESCRIBE');

	/******************
	 *  INITIALIZERS  *
	 ******************/

	// Non-persistent database connection
	protected function _connect(){
		// if the connection port is different from default add it to the hostname
		if($this->port != DB::$PORT) $this->hostname .= ':'.$this->port;
		return @mysql_connect($this->hostname, $this->username, $this->password, true);
	}

	// Persistent database connection
	protected function _pconnect(){
		// if the connection port is different from default add it to the hostname
		if($this->port != DB::$PORT) $this->hostname .= ':'.$this->port;
		return @mysql_pconnect($this->hostname, $this->username, $this->password);
	}

	// Select the database
	protected function _set(){
		return @mysql_select_db($this->name, $this->DB);
	}

	//  Version number string
	protected function _version(){
		return "SELECT version() AS ver";
	}

	// Execute a query
	protected function _query($sql,$error=true){
		$sql = $this->_query_set($sql); echo '<h3>'.$sql.'::';
		$qry = @mysql_query($sql, $this->DB);  echo var_dump($qry).'</h3>';
		if (!$qry && $error) Core::error('DBIQRY', 'LIBTIT',array('__METHOD-2__',mysql_error()));
		else return $qry;
	}

	/**************
	 *  DATABASE  *
	**************/

	// create a database
	protected function _add($name){
		return "CREATE DATABASE ".$name;
	}

	// delete a database
	protected function _drop($name){
		return "DROP DATABASE ".$name;
	}

	protected function _list(){
		return  "SHOW DATABASES";
	}

	/************
	 *  TABLES  *
	 ************/
	protected function _table_list($prefix_limit = false){
		$sql = "SHOW TABLES FROM ".$this->char_escape.$this->name.$this->char_escape;
		if ($prefix_limit !== false && $this->prefix !='') $sql .= " LIKE '".$this->prefix."%'";
		return $sql;
	}

	protected function _table_add($table, $fields,$ifnotexists){
		$table =  $this->_identifiers_escape($table);
		// convert the fields array into an sql declaration.
		$fields = $this->_field_process($fields);
		return	'CREATE TABLE'.(($ifnotexists===true)? 'IF NOT EXISTS' : '')
				." $table ($fields ) DEFAULT CHARACTER SET {$this->charset} COLLATE {$this->collat};";
	}

	protected function _table_drop($table=false){
		return "DROP TABLE IF EXISTS ".$this->_identifiers_escape($table);
	}

	protected function _table_rename($table, $new_name){
		return "RENAME TABLE ".$this->_identifiers_protect($table)." TO ".$this->_identifiers_protect($new_name);
	}

	protected function _table_alter($type, $table, $subject, $after=false, $noprot=false){
		if (!in_array(strtoupper($type),array('ADD','DROP','CHANGE'))) return false;
		$sql = 'ALTER TABLE '.$this->_identifiers_protect($table);
		if (is_array($subject)){
			foreach ($subject as $s) $sql.=" $type ".$this->_identifiers_protect($s).',';
			return substr($sql, 0, -1); // remove last comma
		}
		// sometimes this method will receive several alters in a single subject, we won't protect them.
		$subject = $noprot? $subject : $this->_identifiers_protect($subject);
		if ($after!==false) $subject.=" AFTER ".$this->_identifiers_protect($after);
		return $sql.= " $type ".$subject;
	}

	/************
	 *  FIELDS  *
	 ************/

	protected function _field_list($table){
		return "SHOW COLUMNS FROM ".$table;
	}

	protected function _field_add($field, $table, $after){
		// convert all the fields to a single string, add the prefix ADD to each one of them
		// but remove the firstone, since the _table_alter method will add it.
		$after = $this->_identifiers_protect($after);
		$field = substr($this->_field_process($field,' ADD ', $after), 5);
		return $this->_table_alter('ADD', $table, $field, false, true);
	}

	protected function _field_rename($field, $nfild, $table){
		$nfild = $this->_identifiers_escape($nfild);
		// get the field type so we can do the table alter
		$qry = $this->query('DESCRIBE '.$table.' '.$field);
		$res = $qry->result(true);
		return  $this->_table_alter('CHANGE',$table, $field.' '.$nfild.' '.$res[0]['Type']);
	}

	protected function _field_data($table, $field=false){
		$field = $field? $this->_identifiers_protect($field) : '';
		return 'DESCRIBE '.$this->_identifiers_protect($table).' '.$field;
	}

	private function _field_process($fields, $prefix='',$after=false){
		$count = 0;
		$sql = '';
		$pre = $after!==false? $after : '';
		foreach($fields as $k=>$field){
			if (!is_numeric($k)) continue;
			// if val is string... this is a sql declaration, send it as is.
			if (is_string($field)){
				$sql.="\n\t$field";
				continue;
			}
			$sql.=$prefix;
			$cur = $this->_identifiers_protect($field['NAME']);
			// ok, an array... we'll work with it.
			// we're assuming NAME, TYPE and LENGTH are set, since we checked the array before.
			$sql.=" $cur ".$field['TYPE'];
			if (isset($field['LENGTH'])) $sql.='('.$field['LENGTH'].')';
			if (isset($field['UNSIGNED']) && $field['UNSIGNED']===true) $sql.=' UNSIGNED';
			if (isset($field['DEFAULT'])) $sql.= ' DEFAULT \''.$field['DEFAULT'].'\'';
			if (isset($field['NULL']) && $field['NULL']===true) $sql.=' NULL'; else $sql.=' NOT NULL';
			if (isset($field['AUTO_INCREMENT']) && $field['AUTO_INCREMENT']===true) $sql.=' AUTO_INCREMENT';
			if (isset($field['KEY']) && $field['KEY']===true) $sql.=' KEY';
			elseif (isset($field['PRIMARY_KEY']) && $field['PRIMARY_KEY']===true) $sql.=' PRIMARY KEY';
			// if after es enabled, add an after declaration before the comma
			if ($after!==false) $sql .= " AFTER $pre";
			// if this is the last field, don't add a comma.
			if (++$count < count($fields)) $sql.=',';
			// store the name of this element so it can be used next time.
			$pre =$cur;
		}
		return $sql;
	}

	/*************
	 *  SUPPORT  *
	 *************/

	// prep the query for execution
	// if needed, each database adapter can prepare the query string.
	private function _query_set($sql){
		// "DELETE FROM TABLE" returns 0 affected rows.
		// This hack modifies the query so it returns the real no. of affected rows.
		if(self::$_HCK === true)
			if(preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $sql))
				$sql = preg_replace("/^\s*DELETE\s+FROM\s+(\S+)\s*$/", "DELETE FROM \\1 WHERE 1=1", $sql);
			return $sql;
	}

	// Select client character set
	protected function _charset($charset, $collation){
		return @mysql_query("SET NAMES '".$this->_escape($charset)."' COLLATE '".$this->_escape($collation)."'", $this->DB);
	}

	// escape strings
	protected function _escape($str){
		// if array sent, use recursion to escape everything.
		if (is_array($str)) {
			foreach ($str as $key=>$val) $str[$key] = $this->escape($val);
			return $str;
		}
		if (function_exists('mysql_real_escape_string') && is_resource($this->DB))
			return mysql_real_escape_string($str, $this->DB);
		elseif (function_exists('mysql_escape_string')) return mysql_escape_string($str);
		else return addslashes($str);
	}
 }
?>

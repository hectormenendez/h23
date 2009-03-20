<?php if(!defined('OK')) die('<h1>403</h1>');

class DB_driver {
	
	// db connection vars
	public $username;
	public $password;
	public $name;
	public $hostname;
	public $prefix;
	public $driver;
	public $pconnect;
	public $debug;
	public $cache;
	public $cachedir;
	public $charset;
	public $collat;	
	public $port;
	// public vars
	public $char_bind	= '?';			// the character used when binding data on queries.
	public $char_escape = '`';			// Character used on queries.
	public $cache_clean = true;		// Wheter the cache library must autodelete entries.
	public $query_save  = true; 		// Wheter to save the queries for later debugging.
	public $query 		= array();		// Processed queries.
	public $queries 	= 0;			// The number of processes queries.
	public $field_template	= false;

	// internal vars
	protected $DB; 							// database connection object
	protected $RS;							 	// The query result object.
	protected $field			= array();	 	// Array that holds the fields to be created
	protected $data			= array();	 	// Internal temp data cache. 
	protected $ident_protect	= true;		 	// Protect Identifiers
	protected $ident_reserve	= array('*');	// Reserved Identifiers
	protected $field_allowed	= array('NAME','TYPE','LENGTH', 'UNSIGNED','BINARY','DEFAULT','NULL','AUTO_INCREMENT','COMMENT','KEY','PRIMARY_KEY', 'UNIQUE');
	protected $field_required	= array('NAME','TYPE','LENGTH');
	protected $field_type		= array('VARCHAR','TINYINT','TEXT','DATE','SMALLINT','MEDIUMINT','INT','BIGINT','FLOAT','DOUBLE','DECIMAL','DATETIME','TIMESTAMP','TIME','YEAR','CHAR','TINYBLOB','TINYTEXT','BLOB','MEDIUMBLOB','MEDIUMTEXT','LONGBLOB','LONGTEXT','ENUM','SET','BIT','BOOL','BINARY','VARBINARY');
	protected $query_rtype		= array('SELECT', 'SHOW', 'DESCRIBE');
	protected $query_wtype		= array('SET','INSERT','UPDATE','DELETE','REPLACE','RENAME', 'CREATE','DROP','TRUNCATE','LOAD','COPY','ALTER','GRANT','REVOKE','LOCK','UNLOCK');

	/******************
	 *  INITIALIZERS  *
	 ******************/

	public function __construct(){
		// load the field templates
		if (($this->field_template = Core::config('db_field_templates')) && count($this->field_template)>0){
			// we have to make sure all the templates have string keys defined since we'll use them as names
			foreach($this->field_template as $key => $field){
				if (!is_string($key)){
					if (!isset($field['NAME'])) Core::error('DBFDTM','LIBTIT',array(__CLASS__,$key));
					else {
						$this->field_template[$field['NAME']] =& $field;
						// remove old keys
						unset($this->field_template[$key],$this->field_template[$field['NAME']]['NAME']);
					}
				}
			};
		} else $this->field_template = false;
		// propagate database connection info to public vars
		if (is_array(DB::$INFO)) foreach (DB::$INFO as $param => $value) $this->$param = $value;
		// initialize the connection if the auto flag is set to true.
		if (DB::$AUTO===true) $this->init();
	}
	
	// initialize the database connection.
	public function init(){
		// if an existting connection resource is available there's no need to connect again
		if (is_resource($this->DB) || is_object($this->DB)) return true;
		// connect to the database
		$this->DB = ($this->pconnect==false)? $this->_connect() : $this->_pconnect();
		// send an error if unable to connect
		if (!$this->DB) DB::error('DBCONN','LIBTIT',__CLASS__);
		// select the database (if no database name is specified send an error).
		if (!$this->name) DB::error('DBNAME','LIBTIT',__CLASS__);
		if (!$this->_set()) DB::error('DBSELE','LIBTIT',array(__CLASS__, $this->name));
		// set the character set
		if (!$this->_charset($this->charset, $this->collat)) 
			DB::error('DBCHAR','LIBTIT',array(__CLASS__,$this->charset,$this->collat));
		return true;
	}
	
	public function connect(){
		return $this->_connect();
	}
	
	public function pconnect(){
		return $this->_pconnect();
	}
	
	public function set(){
		return $this->_set();
	}

	public function version(){
		$sql = $this->_version();
		$qry = $this->query($sql);
		return $qry->row('ver');
	}

	// Accept an SQL string as input and returns a result object upon success
	// of a "read" type query. returns boolean true upon a "write" type query.
	public function query($sql=false, $binds=false, $error=true){
		if (!$sql) DB::error('DBRQRY','LIBTIT',__CLASS__);
		// check for an existing cached version of the sql and return it if available
		// we do this by getting the command from the sql (we search for the first space)
		// and we check if that command is one of our defined "read type" queries.
		$cmd = substr(($sql=trim($sql)),0,strpos($sql,' '));
		if ($this->cache==true && $this->_is_read($cmd) && ($cache=DB_Cache::read($sql))!==false)
			return $cache;
		// if the sql contains bindings, parse them.
		if ($binds !== false) $sql = $this->_bind($sql, $binds);		
		// we run the query
		if (($this->RS = $this->_query($sql, $error))===false) return false;
		// if enabled, save the query for later debugging.
		if ($this->query_save===true){
			$this->query[] = $sql;
			++$this->queries;
		}
		// if the query was a write type we return the result_write object;
		if ($this->_is_write($cmd)){
			// and since we're making changes to the database content, 
			// we need to clean our cache. only if the option is enabled ofcourse.
			if ($this->cache === true && $this->cache_clean == true) DB_Cache::delete();
			$driver = 'DB_'.$this->driver.'_Result_Write';
			return new $driver($this->RS);
		}
		// load the result driver
		$driver = 'DB_'.$this->driver.'_Result_Read';
		$RES = new $driver($this->RS);
		
		// if query cached is enabled, we have to instantiate only the result 
		// library (without the platform specific driver).
		// we do this, because we can't serialize the mysql resource variable,
		// but since we already instantiated the result class, we can assign the
		// required values by hand.
		if ($this->cache === true){
			$CRS = new DB_Result_Read();
			$CRS->object = $RES->result();
			$CRS->array  = $RES->result(true);
			$CRS->rows	 = $RES->rows();
			// propagate current results.
			DB_Cache::write($sql, $CRS);
		}
		return $RES;
	}

	public function query_last(){
		return end($this->query);
	}

	public function queries(){
		return $this->queries;
	}
	
	public function query_log(){
		return $this->query;
	}
	/**************
	 *  DATABASE  *
	**************/
	
	// database()
	public function exists($name){
		return (!in_array($name, $this->names()))? false : true;
	}
	
	// databases()
	public function count(){
		return count($this->names());
	}

	// database_list()
	public function names(){
		if (isset($this->data['databases'])) return $this->data['databases'];
		$sql = $this->_list();
		$ret = array();
		$qry = $this->query($sql);
		if ($qry->rows()>0){
			foreach($qry->result(true) as $row){
				if (!isset($row['DATABASE_NAME'])) $ret[] = array_shift($row);
				else $ret[] = $row['DATABASE_NAME'];
			}
		}
		return $this->data['databases'] = $ret;
	}

	// database_add
	public function add($name){
		$sql = $this->_add($name);
		return $this->query($sql);
	}
	
	// database_drop
	public function drop($name){
		// the database must exist
		if (!$this->exists($name)) return false;
		$sql = $this->_drop($name);
		return $this->query($sql);
	}
	
	
	public function optimize(){}
	public function backup(){}

	

	/************
	 *  TABLES  *
	 ************/
	 
	public function table($name, $error=false){
		if (($str = is_string($name))) {
			// we check if the table prefix is present and remove it, 
			// to prevent errors when other methods call this.
			if ($this->prefix && strpos($name, $this->prefix)!==false) 
				$name = str_replace($this->prefix,'',$name);
		}
		if (!$str || !in_array($this->prefix.$name, $this->table_list(false, $error))) 
			return $error===true? DB::error('DBTREQ','LIBTIT',__METHOD__) : false;
		// table found
		return $this->prefix.$name;
	}
	 
	public function tables(){
		return count($this->table_list());
	}
	
	// list available tables on the database, you can optionally limit 
	// the list to only the tables that have the previously defined prefix.
	public function table_list($prefix_limit=false, $error=true){
		if (isset($this->data['tables'])) return $this->data['tables'];
		$sql = $this->_table_list($prefix_limit);
		$ret = array();
		$qry = $this->query($sql);
		if ($qry->rows()>0){
			foreach($qry->result(true) as $row){
				if (!isset($row['TABLE_NAME'])) $ret[] = array_shift($row);
				else $ret[] = $row['TABLE_NAME'];
			}
		}
		// store data and return it
		return $this->data['tables'] = $ret;
	}
		
	// create table
	public function table_add($table=false, $ifnotexists=false){
		if (!is_string($table)) DB::error('DBTREQ','LIBTIT',__METHOD__);
		if (count($this->field)==0) DB::error('DBTNOF','LIBTIT',__METHOD__);
		$sql = $this->_table_add($this->prefix.$table, $this->field, $ifnotexists);
		if (!($qry = $this->query($sql))) return false;
		// add the name of the new table to the data array.
		$this->data['tables'][] = $table;
		// reset the fields array
		$this->field = array();
		return $qry;
	}
	
	// drop table
	public function table_drop($table=false, $error=true){
		// check if the table exists and append its prefix if available.
		if (!($table = $this->table($table,$error))) return false;
		$sql = $this->_table_drop($table);
		if(!($qry = $this->query($sql))) return false;
		// we have to remove the table from the data array.
		$key = array_search($table,$this->data['tables']);
		unset($this->data['tables'][$key]);
		return $qry;
	}
	
	// rename table
	public function table_rename($table=false, $new_name=false, $error=true){
		// check if the table exists and append its prefix if available.
		if (!($table = $this->table($table,$error))) return false;
		if (!$new_name || !is_string($new_name)) DB::error('VARREQ','LIBTIT',array(__METHOD__, '$new_name'));
		$sql = $this->_table_rename($table, $new_name);
		if (!($qry = $this->query($sql))) return false;
		// update the info on the data array
		$key = array_search($table,$this->data['tables']);
		$this->data['tables'][$key] = $new_name;
		return $qry;
	}
	
	public function table_data($table=false, $error=true){
		if (!($table = $this->table($table,$error))) return false;
		$sql = $this->_field_data($table);
		$qry = $this->query($sql);
		$arr = $qry->result(true);
		foreach ($arr as $b=>$a){
			foreach ($arr[$b] as $k=>$v){
				if ($v==''||$v=='NO') unset($arr[$b][$k]);
			}
			$arr[$b] = array_change_key_case($arr[$b],CASE_LOWER);
		}
		return $arr;
	}
		
	public function table_optimize(){}
	public function table_repair(){}

	
	/*************
	 *  COLUMNS  *
	 *************/	
	
	public function column_add(){}
	
	public function column_drop(){}
	
	public function column_rename(){}	
	
		
	/**********
	 *  ROWS  *
	 **********/
		
	public function row(){}
	
	public function rows(){}
	
	public function row_first(){}
	
	public function row_last(){}
	
	public function row_next(){}
	
	public function row_prev(){}
	
	
	/************
	 *  FIELDS  *
	 ************/

	// check if a field exists
	public function field($field=false, $table=false, $error=false){
		if (!is_string($field) || !in_array($field, $this->field_list($table)))
			return $error===true? DB::error('DBFREQ','LIBTIT',__METHOD__) : false;
		return true;
	}

	// return number of fields
	public function fields(){
		return count($this->field_list());
	}

	// adds a field(s)
	public function field_add($field=false, $table=false, $after=false){
		if (!$field) DB::error('DBFREQ','LIBTIT',__METHOD__);
		// get the fields array and merge it with the main 
		if (!($field = $this->_field_check($field))) DB::error('DBFDFO','LIBTIT',__METHOD__);;
		$this->field = array_merge($this->field, $field);
		// if no table is specified we're done.
		if (!$table) return true;
		// if  the table doesn't exist. we simply create it and return.
		if (!($ntable=$this->table($table))) return $this->table_add($table);
		// the table exists
		// if "after" is provided, we have to check if that field actually exists
		if ($after!==false && !$this->field($after, $table)) DB::error('DBFDNO','LIBTIT', array(__METHOD__, $after));
		$sql = $this->_field_add($field,$table, $after);
		if (!($qry = $this->query($sql))) return false;
		// update the data array
		foreach ($field as $f) $this->data['fields'][$table] = $f['NAME'];
		return $qry;
	}
	
	// remove a field(s)
	public function field_drop($field=false, $table=false, $error=true){
		// check for valid field and table; send error if not found.
		if (!$this->field($field, $table, $error)) return false;
		$sql = $this->_table_alter('DROP', $table, $field);
		if (!($qry = $this->query($sql))) return false;
		// we have to remove the field from the data array
		$key = array_search($field, $this->data['fields'][$table]);
		unset($this->data['fields'][$table][$key]);
		return $qry;
	}
	
	// rename a field
	public function field_rename($field=false, $field_new=false, $table=false, $error=true){
		// check for valid field and table;
		if (!$this->field($field, $table, $error)) return false;
		if (!is_string($field_new)) DB::error('VARREQ','LIBTIT',array(__METHOD__,'$new_field_name'));
		$sql = $this->_field_rename($field, $field_new, $table);
		if (!($qry = $this->query($sql))) return false;
		$key = array_search($field, $this->data['fields'][$table]);
		$this->data['fields'][$table][$key] = $field_new;
		return $qry;
	}

	// list available fields on specified table
	public function field_list($table=false){
		if (!($table = $this->table($table))) 	DB::error('DBTBNO','LIBTIT',array(__METHOD__,$table));
		if (isset($this->data['fields'][$table])) return $this->data['fields'][$table];
		$sql = $this->_field_list($table);
		$ret = array();
		$qry = $this->query($sql);
		foreach ($qry->result(true) as $row) {
			if (isset($row['COLUMN_NAME'])) $ret[] = $row['COLUMN_NAME'];
			else $ret[] = current($row);
		}
		// store data and return it.
		return $this->data['fields'][$table] = $ret;
	}
		
	public function field_data($field=false, $table=false, $error=true){
		if (!$this->field($field, $table, $error)) return false;
		$sql = $this->_field_data($table, $field);
		$qry = $this->query($sql);
		$arr = $qry->result(true);
		foreach ($arr[0] as $k=>$v) if($v==''||$v=='NO') unset( $arr[0][$k]);
		return array_change_key_case($arr[0],CASE_LOWER);
	}	
	
	// we have to unify the fields if they are provided as an array
	private function _field_check($field, $recursion=null){
		// if field is string and it isn't a template id we're dealing with 
		// a raw sql declaration. but if and templata id found, retrieve structure.
		if (is_string($field)) {
			if (($tmp = $this->_field_template($field))) return $recursion? $tmp : array($tmp);
			// if we reach here, we're probably dealing with a raw declaration.
			// so the string must have spaces, if not... it probably won't be useful.
			return strpos($field,' ')!==false? ($recursion? $field : array($field)) : false;
		}
		// we have to be sure that the field is an array
		if (!is_array($field)) return $field; //'not array';
		// check the array
		$check = false;
		$found = 0;
		$result = array();
		foreach($field as $key => $val){
			if (!is_array($val)){
				// only add to result array valid values.
				if (
				  (in_array($ukey=strtoupper($key),$this->field_allowed)) &&
				  (is_string($val) || is_numeric($val) || $val===true))
				{
					$check = true;
					if (in_array($ukey, $this->field_required)) $found++;
					$result[$ukey] = $val; 
				}
				// if the value is string and a valid template id
				// replace it with the key definition array.
				if (($field[$key] = $this->_field_template($val,$key)))  $result[] = $field[$key];
				// skip to next element.
				continue;
			}
			// if we reach here, we have to be sure we're dealing with an array.
			if (($field[$key] = $this->_field_check($field[$key],$key))) $result[] = $field[$key];
		}
		// if the user sent the field definition using a multilevel array we'll use the key name
		// ad field name, overriding the NAME key, if any.
		if ($check) {
			if (is_string($recursion)) {
				if (!isset($result['NAME'])) $found++;
				$result['NAME'] = $recursion;
			}
			if (count($this->field_required)!=$found) return false;	
			// check for valid types
			if (!in_array(($result['TYPE'] = strtoupper($result['TYPE'])), $this->field_type)) return false;
			// length must always be an integer.
			if (!is_numeric($result['LENGTH'])) return false; else $result['LENGTH'] = (int)$result['LENGTH'];
			// the name shouldn't contain spaces or tabs
			if (strpos($result['NAME'],' ')!==false) $result['NAME'] = str_replace(' ','',$result['NAME']);
			if (strpos($result['NAME'],"\t")!==false) $result['NAME'] = str_replace("\t",'',$result['NAME']);	
		}
		// we have to determine if the result array has more than one element.
		// we do this by checking if the first element of the array is actually an array.
		if(is_null($recursion)) {
			reset($result);
				$didrec = is_array(current($result));
		} else 	$didrec = true;
		// return false if the result array has no elements
		return count($result)==0? false : $didrec? $result : array($result);
	}
	
	private function _field_template($field,$key=false){
		if (
		 !is_string($field)  || 
		 substr_count($field,'%') < 2 ||
		 !isset($this->field_template[$id=str_replace('%','',$field)])
		) return false;
		$field = $this->field_template[$id];
		if (is_string($key) || !isset($field['NAME'])) $field['NAME'] = $key? $key : $id;
		return $field;
	}

	/*************
	 *  SUPPORT  *
	 *************/

	public function identifiers_protect($item, $prefix_single = false){
		return $this->_identifiers_protect($item, $prefix_single);
	}
 
	public function escape($str){
		switch (gettype($str)){
			case 'string'  : $str = "'".$this->_escape($str)."'"; break;
			case 'boolean' : $str = ($str === false)? 0 : 1; break;
			default		   : $str = ($str === null)? 'NULL' : $str; break;
		}
		return $str;
	}
		
	// check if provided SQL is write type.
	private function _is_write($cmd=false){
		if (!is_string($cmd) || !in_array(strtoupper($cmd), $this->query_wtype)) return false;
		return true;
	}
	// check if provided command is read type.
	private function _is_read($cmd=false){
		if (!is_string($cmd) || !in_array(strtoupper($cmd), $this->query_rtype)) return false;
		return true;
	}

	// Parse Query bindings	
	private function _bind($sql, $binds){
		// if we can't find the binding character on the sql, we simply return it unmodified.
		if (strpos($sql, $this->char_bind) === false) return $sql;
		// if binds isn't an array, we convert it.
		if (!is_array($binds)) $binds = array($binds);
		// get the segments from the sql
		$segs = explode($this->char_bind, $sql);
		// the count of binds must be one less than the count of segments
		// if the user specifies more, we trim them down.
		if (count($binds) >= count($segs)) $binds = array_slice($binds, 0, count($segs)-1);
		// construct the binded query
		$res = $segs[0]; $i=0;
		foreach ($binds as $bind){
			$res .= $this->_identifiers_escape($bind);
			$res .= $segs[++$i];
		}
		return $res;
	}
	
	
	// this function escapes column and table names
	protected function _identifiers_escape($item){
		if ($this->char_escape =='') return $item;
		foreach ($this->ident_reserve as $id){
			if (strpos($item,'.'.$id)!==false){
				$str = $this->char_escape.str_replace('.',$this->char_escape.'.', $item);
				// remove duplicates if the user already included escapes
				return preg_replace('/['.$this->char_escape.']+/', $this->char_escape, $str);
			}
		}
		if (strpos($item,'.') !==false) 
			 $str = $this->char_escape.str_replace('.',$this->char_escape.'.'.$this->char_escape,$item).$this->char_escape;
		else $str = $this->char_escape.$item.$this->char_escape;
		// remove duplicates if the user already included escapes
		return preg_replace('/['.$this->char_escape.']+/', $this->char_escape, $str);
	}
	
	// This function takes a column or table name (optionally with an alias) and inserts
	// the table prefix onto it.  Some logic is necessary in order to deal with
	// column names that include the path.  Consider a query like this:
	//
	// SELECT * FROM hostname.database.table.column AS c FROM hostname.database.table
	//
	// Or a query with aliasing:
	//
	// SELECT m.member_id, m.member_name FROM members AS m
	//
	// Since the column name can include up to four segments (host, DB, table, column)
	// or also have an alias prefix, we need to do a bit of work to figure this out and
	// insert the table prefix (if it exists) in the proper position, and escape only
	// the correct identifiers.
	protected function _identifiers_protect($item, $prefix_single=false, $protect_identifiers = null, $field_exists = true){
		if (!is_bool($protect_identifiers)) $protect_identifiers = $this->ident_protect;
		if (is_array($item)){
			$arr = array();
			foreach ($item as $key=>$val) 
				$arr[$this->_identifiers_protect($key)] = $this->_identifiers_protect($val);
			return $arr;
		}
		// convert tabs or multiple spaces into single spaces
		$item = preg_replace('/[\t ]+/', ' ', $item);
		// if the item has an alias declaration we remove it and set it aside
		// basically we remove everything to the right of the first space
		$alias  = '';
		if (strpos($item,' ') !== false){
			$alias = strstr($item, " ");
			$item = substr($item, 0, - strlen($alias));
		}
		// this is a bugfix for queries using MAX, MIN, ETC
		// if a parenthesis is found we know that we don't need to escape the data or add a prefix
		if (strpos($item,'(')!==false) return $item.$alias;
		// Break the string apart, if it contains periods insert the table prefix 
		// in the correct location, assuming the period doesn't indicate we're dealing
		// with an alias. While we're at it, we will escape the components.
		if (strpos($item, '.')!==false){
			$parts = explode('.',$item);
			// does the first segment of the exploded item match one of the alias previously identified
			// if so, we have nothing to do other than escape that item.
			if (in_array($parts[0], $this->ar_aliased_tables)){
				if ($protect_identifiers === true){
					foreach($parts as $key=>$val)
						if (!in_array($val, $this->ident_reserve)) $parts[$key] = $this->_identifiers_escape($val);
					$item = implode('.', $parts);
				}
				return $item.$alias;	
			}
			// is there a table prefix defined in the config file? if not, do nothing.
			if ($this->prefix != ''){
				// we now add the table prefix based on some logic.
				// do we hace 4 segments (hostnmae.database.table.column)?
				// if so, we add the table prefix to the column name in the 3rd segment.
				if (isset($parts[3]))$i=2;
				// do we have 3 segments (database.table.column)?
				// if so, we add the table prefix to the column name in 2nd position.
				elseif(isset($parts[2]))$i=1;
				// do we have 2 segments (table.column)?
				// if so, we add the table prefix to the column name in the 1st segment.
				else $i=0;
				// this flag is set when the supplied item does not contain a field name.
				// this can happen when this function is being called from a JOIN.
				if ($field_exists === false) ++$i;
				// we only add the table prefix if it does not already exists
				if (substr($parts[$i],0,strlen($this->prefix)) != $this->prefix)
					$parts[$i] = $this->prefix.$parts[$i];
				// put the parts back together
				$item = implode('.', $parts);
			}
			if ($protect_identifiers === true) $item = $this->_identifiers_escape($item);
			return $item.$alias;
		}
		// is there a table prefix? if not , do nothing.
		if ($this->prefix != '' && ($prefix_single===true && (substr($item,0, strlen($this->prefix))!= $this->prefix )))
			$item = $this->prefix.$item;
		if ($protect_identifiers===true && !in_array($item, $this->ident_reserve))
			$item = $this->_identifiers_escape($item);
		return $item.$alias;
	}	
}
?>
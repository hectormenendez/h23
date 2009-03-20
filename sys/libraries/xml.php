<?php if(!defined('OK')) die('<h1>403</h1>');
/*
	todo: handle several domdocuments at the same time
		  * _dom() : check that the document has valid XML before attempting to load, so we can prevent errors.
*/
class XML {
	
	private static $_PTH;	// The current XML file path
	private static $_CHR;	// Character set (from config)
	private static $_VER;	// The XML version to use
	private static $_PWS;	// Preserve White Spaces
	private static $_FOU;	// Format output
	private static $_DOM;	// The XML DomDocument Object.
	private static $_XPT;	// The XPATH object.
	private static $_QRY;	// current XPATH's dom query
	private static $_LDD;	// wheter a file is loaded.
	
	final public static function _construct(){
		self::$_CHR = Core::config('charset');
		self::$_VER = Core::config('xml_version');
		self::$_PWS = Core::config('xml_white_spaces');
		self::$_FOU = Core::config('xml_format_output');
	}
	
	/**
	 *  CREATE OR SET A XML DOCUMENT
	 *  set a XML file to work with, so it won't be necessary
	 *  to specify it everytime a method is called. 
	 *  if the requested file doesn't exists, we create it.
	 *  @param	domdocument || string	The domdocument or filepath of the XML to work
	 *  @return	void
	**/
	public static function set($objorpath=false){
		self::_checkdom($objorpath,true);
	}
	
	/**
	 *  LOAD OR SET A XML DOCUMENT
	 *  Loads contents of an existent domdocument and/or set it as current
	 *  This is almost identical to XML::set. the only diference is that 
	 *  this method will check if the specified file/object has contents on it.
	**/
	public static function load($objorpath=false){
		self::_checkdom($objorpath, false, true);
	}
	/** 
	 *  REMOVES A FILE, OR ALL CHILDS OF GIVEN ELEMENT
	 *  if nothing specifiied removes all elements (everything but the root element)
	 *  of current DomDocument. if you set delete to true, you can delete the current file.
	 *  you can optionally specify a query and the function will remove all its childs.
	 *  TODO: Add support for physically delete a file, specified by obj and not only by string
	**/
	public static function clear($objorpath=false, $delete=false, $query=false){
		// physically delete the file if specified
		if ($delete && is_string($objorpath)) return unlink(self::path($objorpath,false,1));
		// set the domdocument. and make sure that an XML file is loaded.
		self::_checkdom($objorpath,false, true);
		// if there's a query use it, otherwise use the root element.
		$obj = $query? self::query($query) : array(self::$_DOM->documentElement);
		foreach($obj as $node){
			while($node->hasChildNodes()) $node->removeChild($node->childNodes->item(0));	
		}
		return self::save(false, false);
	}
	
	/**
	 *  PHYSICALLY DELETE A XML FILE
	 *  Identical to XML::core($objorpath, true)
	 *  TODO: Add support for physically delete a file, specified by obj and not only by string
	**/
	public static function delete($objorpath=false){
		if (is_string($objorpath)) return unlink(self::path($objorpath,false,1));
	}
	
	/**
	 *  RETURNS THE ROOT ELEMENT
	 *  if nothing specified it will return the current's DomDocument Root
	 *  optionally you can specify a path or a DomDocument.
	**/
	public static function root($objorpath=false){
		self::_checkdom($objorpath);
		// if the root element couldn't be found send an error
		if (!self::$_DOM->documentElement) Core::error('XMLROT','LIBTIT');
		return  self::$_DOM->documentElement;
	}
	
	/**
	 *  GET ELEMENT BY ID
	 *  Returns an element matched by an id. an XML must be set or loaded.
	**/
	public static function get_id($id=false, $objorpath=false, $returnarray=true){
		return self::_byid('get', $id, $objorpath, $returnarray);
	}
	
	/**
	 *	DELETE AN ELEMENT BY ID
	**/
	public static function del_id($id=false, $objorpath=false){
		return self::_byid('del', $id, $objorpath, false);
	}
	
	/** 
	 *  Appends an element.
	 *  uses the same logic for arrays found in _arraytoXML
	*/
	public static function append($array=false, $objorpath=false, $query=false){
		// make sure that at least a tagname is being specified in the array
		if (!isset($array['tagname'])) Core::error('VARREQ','LIBTIT',array(__METHOD__,"\$array['tagname']"));
		// create or load domdocument.
		self::_checkdom($objorpath, true);
		// use a query if available, otherwise check for a root element in the file, or create one if necessary.
		$obj = $query? self::query($query) : array(!self::$_DOM->documentElement? self::$_DOM->createElement('root') : self::$_DOM->documentElement);
		if (!$obj) return false;
		foreach ($obj as $node){
			// if node has a textnode, we set it to an attribute so it doesn't mess up the XML tree
			if($node->firstChild instanceof DOMText && !$node->firstChild->isWhitespaceInElementContent()) {
				$node->setAttribute('value', $node->firstChild->textContent);
				$node->removeChild($node->firstChild);
			}
			// transform the array and append it.
			$newnode = self::_array2XML(array($array), $node);
			if (!$node->parentNode) self::$_DOM->appendChild($newnode);
			else $node->parentNode->appendChild($newnode);
		}
		
		return self::save(false,false);
	}
	
	public static function set_value($query=false, $value=false, $objorpath=false){
		return self::replace_value($query, $value, $objorpath, true);
	}
	
	// checks if query has value defined and replace it. if no value defined, returns false.
	// unless $create is set to true, where the function will force the creation of the value.
	public static function replace_value($query=false, $value=false, $objorpath=false, $create=false){
		// set the domdocument. and make sure that an XML file is loaded.
		self::_checkdom($objorpath, false, true);
		$done = false;
		// check that required arguments are set
		if (!$query) $error = 'query'; if (!$value) $error = 'value';
		if (isset($error)) Core::error('VARREQ','LIBTIT', array(__METHOD__,$error));
		if (!$query = self::query($query)) return false; 
		$i = 0;
		foreach ($query as $element){
			if (!$element instanceof DOMElement) continue;
			$hastxtcnt = false;
			// if element has children, look for textcontent.
			if ($element->hasChildNodes()){
				foreach ($element->childNodes as $child){
					// if this is a text content child, replace it.
					if ($child instanceof DOMText && !$child->isWhitespaceInElementContent()){
						// if the value has specialchars use cdata instead of textnode
						$rep = htmlspecialchars($value)!=$value? self::$_DOM->createCDATASection($value): self::$_DOM->createTextNode($value);
						$element->replaceChild($rep, $child);
						$hastxtcnt = $done = true;
						// there's no need of continue through the children
						break;
					}
				}
			}
			// if element has no textcontent, look for the attribute 'value' and replace it
			// or create it if $create is enabled.
			if ((!$hastxtcnt) && ($create || $element->hasAttribute('value'))) if($done=true) $element->setAttribute('value',$value);
		}
		if ($done) return self::save(false,false);
		return false;
	}
	
	// XML to array
	public static function to_array($objorpath=false, $query='/root/*'){
		// set domdocument. and make sure that an XML file is loaded.
		self::_checkdom($objorpath, false, true);
		return self::_XML2array(self::query($query));
	}

	// array to XML
	public static function from_array($array, $objorpath=false, $root='root'){
		if (!is_array($array)) Core::error('ARRTYP','LIBTIT',array(__METHOD__,''));
		// create or load domdocument.
		self::_checkdom($objorpath, true);
		// create the root element
		$root = self::$_DOM->createElement($root);
		// pass the array 
		self::_array2XML($array, $root);
		// if XMLfile exists replace the root document
		if ($oldroot = self::$_DOM->documentElement) self::$_DOM->replaceChild($root, $oldroot);	
		// otherwise just append the recently created tree
		else self::$_DOM->appendChild($root);
		// save the file and return the XML tree
		return self::save(false,false);
	}

	// remember that if you want to search realitve to a domelement
	// you must use an array to put the query string and the domelement together
	public static function query($string, $objorpath=false, $check=true){
		// set domdocument. and make sure that an XML file is loaded.
		if($check) self::_checkdom($objorpath, false, true);
		$relto = false;
		// if query string is an array in means user is giving an object
		// which can be used as a relative guide to the query
		if (is_array($string)){
			if (count($string)!=2) Core::error('ARRNUM','LIBTIT', array(__METHOD__,'$string',2));
			$relto  = $string[1];
			$string = $string[0];
		}
		// do the query
		$query = $relto? self::$_XPT->query($string, $relto) : self::$_XPT->query($string);
		// if nothing found return false, else return the result.
		if ($query->length==0) return false;
		return $query;
	}

	public static function path($path=false, $create=false, $_doublebacktrace=false){
		// if this is a call from a private method, set the method 2 traces back
		$method = '__METHOD'.(!$_doublebacktrace?'':(int)$_doublebacktrace).'__';
		// if no path is previously defined and no path string is provided, send error.
		if (!$path && !self::$_PTH) Core::error('XMLPTH','LIBTIT',__CLASS__);
		// if no path is provided return the currently defined one.
		if (!$path) return self::$_PTH;
		// extract the file name
		$file = substr($path,($pos=strrpos($path,_SH))?$pos+1:0);
		// if an extension is not specified, add it to path
		if (substr($path,-4)!='.xml') $path.='.xml';
		// get the working directory
		$dir  = substr($path,0,strrpos($path,_SH)+1);
		// if provided directory doesn't exists use the default one
		if (!file_exists($dir)) {
			$path = TXML.$file;
			$dir  = TXML;
		}
		$err = array($method,$file);
		// if we're not creating a new file check that path exists and is writable
		if (!$create){
			if (!file_exists($path)) Core::error('404MSG','LIBTIT',$err);
			if (!is_writable($path)) Core::error('XMLWRI','LIBTIT',$err);
		} else {
		// if we're creating the file, make sure the directory has write access.
			if (!is_writable($dir))  Core::error('XMLDIR','LIBTIT',$err);
		}
		// if we reach here, is safe to return the path.	
		return self::$_PTH = $path;
	}

	/** 
	 * SAVE THE CURRENT XML 
	**/
	public static function save($objorpath=false, $check=true){
		// set the domdocument
		if ($check) self::_checkdom($objorpath);
		self::$_DOM->save(self::path());
		return self::$_DOM->saveXML();
	}


// -------------------------------------------------------------  Support Functions

	/**
	  ARRAY TO XML:  Usage example.
	  v1 - 05/May/2008
	 $a = array(
		'title' => array(
			'value'   => 'this is a section element with an id = title',
			'tagname' => 'section' // the element will be <section id="title">this is a ...</section>
		),
		// same as above 
		'section' => array(
			'value'	=> 'this is a section element with an id = title2',
			'id'    => 'title2'
		),
		'metatags' => array(
			'tagname' => 'section',
			// if you want to declare several elements with the same tag use numbers
			'meta'    => array(
				0 => array(
					'http-equiv' => 'content-language',
					'content'    => 'es-mx',
					'id'         => 'numbered'
				),
				1 => array(
					'name'    => 'description',
					// this will be added as attribute and all the html characters will be converted
					'content' => 'Giro Diseño - Su mejor <a>opción</a> en el desarrollo web'
				),
				2 => array(
					'value'  => 'text value',
					'tagname' => 'othermeta' // this method of giving an id won't work and will be ignored
				),
				'a' => 'foo'  // this is invalid it will be removed
			)	
		),
		'content' => array(
			'tagname' => 'section',
			'value'   => '<h1>Ñ</h1><p>Hola Mundo! ñoño!</p>' // this will use CDATA
		),
		'tagname' => 'foo',		// this is invalid. it will be ignored
		'value' => 'hola mundo' // this is invalid. it will be ignored
	);
	**/
	private static function _array2XML($array, $obj=false, $valid=false){
		// if we want to enable formatoutput, we must generate valid XML.
		// so, we must prevent the creation of textcontent elements in the 
		// same level as element nodes. ej: <element><otherelement />text content</element>
		// for this, we must prevent the array to have mixed values, 
		// so, we check for arrays within arrays (element nodes) and if found something
		// remove all non array values (textcontent elements).
		// of course this could be expensive if we're dealing with large arrays
		// so this is disabled by default and it's only enabled if formatoutput is set to true
		if ($valid){
			if(Arrays::value_has_type('array',$array))
				if($naa = Arrays::value_get_type('!array',$array)) foreach ($naa as $k=>$v) unset($array[$k]);
		}
		// start the normal inspection of the array
		foreach ($array as $key => $val){
			$key = strtolower($key);
			// if value isn't array it means that this is a value or an attribute
			if (!is_array($val)) {
				$val = utf8_encode($val);
				// determine if the value must be enclosed with cdata;
				$cdata = false; if (htmlspecialchars($val)!=$val) $cdata = true;
				// if a tagname key is defined ignore it
				if ($key=='tagname') continue;
				// set the value if any
				if ($key=='value') {
					// if the value has specialchars use cdata instead of textnode
					if($cdata) $o = self::$_DOM->createCDATASection($val);
					else $o = self::$_DOM->createTextNode($val);
					$obj->appendChild($o);
				}
				// everything else will be treated as an attribute
				else $obj->setAttribute($key,$cdata?htmlspecialchars($val):$val);
				// skip to the next key;
				continue;
			}
			// value is an array
			$tag = $key; $id = null;
			// if a tagname key exists, set it's value as an element name.
			// and store the key name as an id attribute, also, we uset the 
			// tagname key so it doesn't interfere later.
			if (array_key_exists('tagname', $val)) { 
				$tag = $val['tagname'];
				$id  = $key;
				unset ($val['tagname']); 
			}
			// if current array has numbered arrays, it means that 
			// we're dealing with the creation of several elements with
			// the same tagname. to deal with this we force an extra foreach loop.
			if (Arrays::key_has_type('int',$val)) {
				// unset any array value which its key variable type isn't a integer
				if ($nan = Arrays::key_get_type('!int',$val)) foreach ($nan as $k=>$v) unset($val[$k]);
			// we make sure the foreach loop runs at least once. 
			} else $val = array($val);
			foreach ($val as $e){
				$node = self::$_DOM->createElement($tag);
				if ($id) $node->setAttribute('id',$id);
				self::_array2XML($e, $node, $valid);
				$obj->appendChild($node);
			}
		}
		return $obj;
	}

	/**
	 *  XML TO ARRAY
	 *  v3.1 - 31 / May / 2008
	 * 	changelog:
	 *  - forced the creation of an id element,
	 *    commenting out a "continue" statement.
	 *	v3.0 - 04 / May / 2008
	 *	changelog:
	 * 	- methods are now static 
	 * 	  no more instantiating an XML object everytime a conversion is needed.
	 * 	- added htmlspecialchars_decode to attribute values
	 *  - cleaned code a lot.
	 *  - commented everything
	 *  - added the code for removing empty 'value' keys. 
	 *    and commented it out, for backwards compability.
	**/
	private static function _XML2array($query){
		$unique = true; $array = array();
		foreach ($query as $node){
			// check that this node is an element
			if (!$node instanceof DOMElement) continue;
			// reset vars
			$cont=array(); $id='';
			// if the node has children use recursion an get the values.
			if ($node->childNodes->length > 1) $cont = self::_XML2array($node->childNodes);
			else $cont['value'] = $node->textContent;
			// check for attributes and store them in the cont array 
			// as if they were regular values.
			if ($node->hasAttributes()){
				foreach ($node->attributes as $attribute){
					if ($attribute->name=='id') { $id = $attribute->value; }//continue; }
					// we make sure html chars will be converted
					$cont[$attribute->name] = htmlspecialchars_decode($attribute->value);
				}
			}
			// uncoment the code below if you want to remove the 'value' keys when they
			// are empty. This happens when the node doesn't use a close tag. and doesn't
			// specify a value attribute. EJ. <element src="someurl"/>
			/** if (isset($cont['value'])) if($cont['value']=='') unset($cont['value']); **/

			// if the node doesn't have an ID attribute, use the tagname.
			// but first, check that this tagname is unique, so we don't 
			// overwrite data.
			if (!$id) $unique = self::_isunique($id = $node->tagName, $node);
			// if the node does have an ID, we set a special key so the user
			// can find out later what tagname did this node had.
			else $cont['tagname'] = $node->tagName;
			if ($unique) $array[$id] = $cont; 
			else $array[$id][] = $cont;
		}
		return $array;
	}
	
	private static function _isunique($id, $obj){
		$x = self::query(array($id, $obj->parentNode));
		return ($x->length > 1)? false : true;
	}

	private static function _byid($action=false, $id=false, $objorpath=false, $returnarray=true){
		$action = strtolower($action);
		if (!$id) Core::error('VARREQ','LIBTIT',array('__METHOD-1__','id'));
		// set the domdocument. and make sure that an XML file is loaded.
		self::_checkdom($objorpath, false, true, true);
		if (!$query = self::query("//*[@id='$id']")) return false;
		$query = $returnarray? self::_XML2array($query) : $query;
		// return the first ocurrence from the query
		foreach($query as $element) {
			if ($action=='get') return $element;
			if ($action=='del') {
				if ($element->parentNode) $element->parentNode->removeChild($element);
				else self::$_DOM->removeChild($element);
				return self::save(false,false);
			}
		}
	}

	/**
	 *  CHECK FOR VALID DOMDocument
	 *  Check if given variable is a valid DOMDocument object or a string 
	 *  containing a valid path (unless $create is set to true in which case
	 *  we must attempt to create the file)
	**/
	private static function _checkdom($objorpath=false, $create=false, $checkloaded=false, $_doublebacktrace=false){
		// if this is a call from a private method, set the method 2 traces back
		$method = '__METHOD'.(!$_doublebacktrace?'-1':'-2').'__';
		// if there is no instantiated domxpath document and 
		// the user doesn't specify one either, we show an error.
		if (!$objorpath && !self::$_XPT) Core::error('XMLOBJ','LIBTIT',$method);
		// check if an object or a path is defined. if path provided, validate path and create new dom
		// if object provided, validate it and set it as new current domdocument.
		if (is_string($objorpath)) self::_domxpath($objorpath, false, true, $create);
		else if (is_object($objorpath)) self::_domxpath(false, $objorpath, true, $create);
		// if we want a file loaded at this point, and if it isn't. show an error.
		if ($checkloaded && !self::$_LDD) Core::error('XMLLDD','LIBTIT',$method);
	}

	/** 
	 *  INSTANTIATE DOMDocument
	 *  create or set a new Domdocument based upon a string or an already declared object
	**/
	private static function _domxpath($path=false, $obj=false, $force=false, $create=false){
		self::$_LDD = false;
		// check that DomDocument and DomXPath are available
		// we don't need to send an error since the Core::library will handle that.
		if (!class_exists('DomDocument') || !class_exists('DOMXPath')) die;
		// return if $_DOM is already declared and we're not forcing re-instantiation.
		if (self::$_DOM instanceof DomDocument && !$force) return self::$_DOM;
		// if no object is specified, instantiate a new one.
		if(!$obj){
			self::$_DOM = new DomDocument(self::$_VER, self::$_CHR);
			self::$_DOM->preserveWhiteSpace = self::$_PWS;
			self::$_DOM->formatOutput = self::$_FOU;
		// if object provided is a valid instance, use it instead.
		} elseif ($obj instanceof DomDocument) {
			self::$_DOM = $obj;
			// we override force, so xpath will be reinstantiated again.
			$force = true;
		// if object isn't dom... show error.
		} else Core::error('VARTYP','LIBTIT', array(__CLASS__,'object','DomDocument'));
		// if there's a path set it and load it.
		if ($path){
			// we make sure the path is set correctly
			$path = self::path($path,$create,3);
			//  if the specified file doesn't exist, or the file exists 
			//  but it doesn't have a root element defined AND the function
			//  needs a file to be created, do so.
			if ($create && (!file_exists($path) || !self::$_DOM->documentElement)){
				self::$_DOM->appendChild(self::$_DOM->createElement('root'));
				self::save(false,false);
				self::$_LDD = true;
			//  if we're not creating but the file already exists and has content on it, load it.
			} elseif (!$create && file_exists($path) && filesize($path)>0){
				self::$_DOM->load($path);
				self::$_LDD = true;
			}
		}
		// instantiate the domxpath object
		if (!self::$_XPT instanceof DOMXPath || $force)
			self::$_XPT = new DOMXPath(self::$_DOM);
		return self::$_DOM;
	}


}

?>
<?php if(!defined('OK')) die('<h1>403</h1>');

class File {

	private static $_MME = false;	// mime types array

	// CHMOD
	const FR 		= 0644;		// FILE READ
	const FW 		= 0666;		// FILE WRITE
	const DR 		= 0755;		// DIR READ
	const DW 		= 0777;		// DIR WRITE
	// FOPEN MODES
	const R		= 'rb';		// READ
	const RW		= 'r+b';	// READ WRITE
	const WCD		= 'wb';		// WRITE CREATE DESTRUCTIVE
	const RWCD		= 'w+b';	// READ WRITE CREATE DESTRUCTIVE
	const WC		= 'ab';		// WRITE CREATE
	const RWC		= 'a+b';	// READ WRITE CREATE
	const WCS		= 'xb';		// WRITE CREATE STRICT
	const RWCS		= 'x+b';	// READ WRITE CREATE STRICT

	// opens the specified file and returns it as a string
	public static function read($file=false){
		if (!$file || !file_exists($file)) return false;
		return file_get_contents($file);
	}

	// writes data to specified file, creates new file if non existent
	public static function write($path=false, $data, $chmod=self::FW, $mode=self::WCD){
		// open, lock, write data, unlock and close file.
		if (!$fp = @fopen($path, $mode)) return false;
		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);
		// change user permissions.
		@chmod($path, $chmod);
		return true;
	}

	// deletes a file or a dir, no matter if the dir isn't empty.
	// (this last behaviour can be set to false)
	public static function delete($file=false, $all=true){
		if (!is_string($file)) return false;
		if (is_file($file)) return @unlink($file);
		return self::rmdir($file,$all);
	}

	public static function rmdir($file, $recursive=false){
		if (!is_dir($file)) return false;
		if (!$recursive) return @rmdir($file);
		// delete the directory and its contents
		$d = @opendir($file);
		while(($f=readdir($d))!==false){
			if ($f=='.' || $f=='..') continue;
			if (!@unlink($file._SH.$f)) self::rmdir($file._SH.$f,true);
		}
		closedir($d);
		@rmdir($file);
		return true;
	}

	public static function info($file=false){
		if (!$file || !file_exists($file)) return false;
		$info['name']  = substr(strrchr($file, '/'), 1);
		$info['path']  = $file;
		$info['size']  = filesize($file);
		$info['date']  = filectime($file);
		$info['read']  = is_readable($file);
		$info['write'] = is_writable($file);
		$info['exec']  = is_executable($file);
		$info['perms'] = fileperms($file);
		return $info;
	}

	public static function mime($file=false){
		$ext = substr(strrchr($file, '.'), 1);
		if (!self::$_MME) {
			if (!file_exists(($path=CORE.'mimes'.EXT))) return false;
			include($path);
			if (!is_array($_MME)) return false;
			self::$_MME = $_MME;
			unset($_MME);
		}
		if (!array_key_exists($ext, self::$_MME)) return false;
		// if the extension has multiple mime types, return the first one.
		if (is_array(self::$_MME[$ext])) return Arrays::first(self::$_MME[$ext]);
		return self::$_MME[$ext];
	}

	public static function mkdir($dir=false, $chmod=self::DW){
		if (!is_string($dir) || is_dir($dir) || !@mkdir($dir, $chmod)) return false;
		@chmod($dir,$chmod);
		return true;
	}
}
?>

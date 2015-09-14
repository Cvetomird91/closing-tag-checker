<?php

/**
 * Tool for scanning for PHP scripts with a closing tag at the end of the file.
 * 
 * @copyright Tsvetomir Denchev
 * @author Tsvetomir Denchev
 * @version	1.0
 */

class TokenChecker {

	// idea: SPL RecursiveDirectoryIterator for child directory check
	public $dir;
	public $files;
	public $with_t_closing = array();

	public function __construct($dir) {

		$this->dir = $dir;
		$files = scandir($this->dir);

		$sort = array();
		foreach ($files as $f) {
			if(preg_match('/^.*\.php/', $f) == 1) {
					$sort[] = $f;
			}
		}
		$this->files = $sort;

		foreach($this->files as $files) {
			if ($this->check_tokens($files)) {
				$this->with_t_closing[] = $files;
			}
		}
		print_r($this->with_t_closing);
	}

	/** 
	 * @file string
	 * @returns string
	 */

	public function check_tokens($file) { 
		$tokens = token_get_all(file_get_contents($file));
		$end = end($tokens);
		if ($end[0] == '378') { 
			return $file;	
		}
	}

}

$obj = new TokenChecker('./');

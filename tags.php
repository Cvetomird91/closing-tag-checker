<?php

	/**
	 *
	 *
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
	//check only if T_CLOSE_TAG is on the last line of the file
		print_r($this->with_t_closing);
	}

	//idea: add second argument - an array which will be filled with the names of the files w/ T_CLOSING_TAG
	//idea: unset values in $this->files where there is no closing tag
	public function check_tokens ($file) {
			$tokens = token_get_all(file_get_contents($file));
			$lex = [];
			foreach ($tokens as $token) {
				$lex[]=$token[0];
				if (in_array('376', $lex)){
						return $file;
				}
			}
		}

}

$obj = new TokenChecker('./');

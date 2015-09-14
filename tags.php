<?php

	/**
	 *
	 *
	 */

class TokenChecker {

	// idea: SPL RecursiveDirectoryIterator for child directory check
	public $dir;
	public $files;
	public $with_closing = array();

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
		print_r($this->files);

	}

	//idea: add second argument - an array which will be filled with the names of the files w/ T_CLOSING_TAG
	//idea: unset values in $this->files where there is no closing tag
	public function check_tokens ($file) {
			$tokens = token_get_all(file_get_contents($file));
			$lex = [];
			$checked = array();
			foreach ($tokens as $token) {
				$lex[]=$token[0];
				if (in_array('378', $lex)){
						$checked[] = $files;
				}
			}
		return $checked;
		}
}

$obj = new TokenChecker('./');

//foreach ($obj->check_tokens($obj->files) as $f) {
//	print_r($f);
//}

?>

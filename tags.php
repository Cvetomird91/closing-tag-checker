<?php


/**
 * Tool for scanning for PHP scripts with a closing tag at the end of the file.
 *
 * @copyright Tsvetomir Denchev
 * @author Tsvetomir Denchev
 * @version	1.0
 */

class TokenChecker {

	private $dir;
	private $files;
	public $with_t_closing = array();

	//exclude directory/directories

	public function __construct($dir, $recursive){
		$this->dir = $dir;

		if ($recursive == true) {
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dir));
		} else {
			$files = scandir($this->dir);
		}

		$sort = array();
		foreach ($files as $f) {
			$recursive = $f->getPathname();
			if(preg_match('/.*\.php$/', $recursive)) {
					$sort[] = str_replace ('./', realpath($this->dir) . '/', $recursive);
			}
		}
		$this->files = $sort;

		foreach($this->files as $file) {
			if ($this->check_tokens($file) && ($this->check_for_one_liner($file) == false)){
				$this->with_t_closing[] = $file;
			}
		}
		print_r($this->with_t_closing);
	}

	/**
	 * @check_tokens string
	 * @returns string
	 */

	public function check_tokens($file) {
		$tokens = file_get_contents($file);
		$tokens = token_get_all($tokens);

		$end = end($tokens);
		if ($end[0] == T_CLOSE_TAG){
			return $file;
		}
	}

	/**
	 * Checks if the last line contains a PHP closing and opening tag on the last line
	 * @check_for_one_liner string
	 * @returns string
	 */

	public function check_for_one_liner($file) {

		$tokens = file_get_contents($file);
		$tokens = token_get_all($tokens);

		$lines = array_column($tokens, 2);
		$all_lines = array_unique($lines);
		$last_line = max($all_lines);

		$has_open_tag = '';
		$has_closing_tag = '';

		foreach ($tokens as $token) { 
			$has_open_tag = '';
			$has_closing_tag = '';

			if (is_array($token) && $token[2] == $last_line && ($token[0] == T_OPEN_TAG || $token[0] == T_OPEN_TAG_WITH_ECHO)) {
				$has_open_tag = true;
			}

			if (is_array($token) && $token[2] == $last_line && $token[0] == T_CLOSE_TAG) {
				$has_closing_tag = true;
			}

			if ($has_open_tag && $has_closing_tag) {
				return true;
			} 
			return false;
		}

	//add check for tokens T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO and T_CLOSE_TAG on the same line

	/*	$last_line_tokens = array();

		$flipped = array_reverse($lines); 

	foreach($flipped as $flip){ 
		 	if ($flip == $last_line) 
		 		{ 
		 			$last_line_tokens[] = $flip; 
		 		} else { 
		 			break;  
		 		}
		 } */
	}	 

}

if (isset($argv[1])) {
	chdir($argv[1]);
	$obj = new TokenChecker('./', true);
} else {
	$obj = new TokenChecker('./', true);
}
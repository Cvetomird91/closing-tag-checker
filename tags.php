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

		foreach($this->files as $files) {
			if ($this->check_tokens($files)) {
				$this->with_t_closing[] = $files;
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

	}

}

if (isset($argv[1])) {
	chdir($argv[1]);
	$obj = new TokenChecker('./', true);
} else {
	$obj = new TokenChecker('./', true);
}

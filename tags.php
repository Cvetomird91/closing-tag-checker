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

	public function __construct($dir) {
		$this->dir = $dir;
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dir));

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
		$tokens = token_get_all(file_get_contents($file));
		$end = end($tokens);
		if ($end[0] == T_CLOSE_TAG){
			return $file;
		}
	}

}

if (isset($argv[1])) {
	chdir($argv[1]);
	$obj = new TokenChecker('./');
} else {
	$obj = new TokenChecker('./');
}

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
	private $dir;
	private $files;
	public $with_t_closing = array();
#	private $php_version;
	public $t_closing_id;


	public function __construct($dir) {
		$this->dir = $dir;
#		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dir));

		$files = scandir($this->dir);

		$sort = array();
		foreach ($files as $f) {
			if(preg_match('/^.*\.php/', $f) == 1) {
					$sort[] = $f;
			}
		}
		$this->files = $sort;
		$this->check_php_version();

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
		if ($end[0] == $this->t_closing_id) {
			return $file;
		}
	}
	/**
	 * @check_php_version void 
	 * @returns void
	 */

	public function check_php_version () { 
		$version = phpversion();
		$toks = explode('.', $version);
		$ver = floatval($toks[0].'.'.$toks[1]);
		switch ($ver) { 
			case 5.1:
				$this->t_closing_id = 369;
   			break;
			case 5.2:
				$this->t_closing_id = 369;
			break;
			case 5.3:
				$this->t_closing_id = 370;
			break;
			case 5.4:
				$this->t_closing_id = 374;
			break;
			case 5.5:
				$this->t_closing_id = 376;
			break;
			case 5.6:
				$this->t_closing_id = 378;
			break;
			default: 
				$this->t_closing_id = 376;
			break;
			}
		}
}
if (isset($argv[1])) { 
	$obj = new TokenChecker($argv[1]);
} else { 
	$obj = new TokenChecker('./');
}
?>

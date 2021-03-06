#!/usr/bin/env php
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

    public function __construct($dir, $recursive){
        $this->find_files_with_closing_tag($dir, $recursive);
    }

    /**
     * @check_tokens string
     * @returns string
     */

	public function find_files_with_closing_tag($dir, $recursive) {

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

		$this->print_file_names();

	}

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
		$last_line = max($lines);

		$last_line_tokens = array();

		foreach ($tokens as $token) {
			if (is_array($token) && $token[2] == 8) {
				$last_line_tokens[] = $token[0];
			}
		}

		if (in_array(T_OPEN_TAG, $last_line_tokens) && in_array(T_CLOSE_TAG, $last_line_tokens)) {
			return true;
		}

		if (in_array(T_OPEN_TAG_WITH_ECHO, $last_line_tokens) && in_array(T_CLOSE_TAG, $last_line_tokens)) {
			return true;
		}
	}

	private function print_file_names() {
		foreach($this->files as $file) {
			if ($this->check_tokens($file) && !$this->check_for_one_liner($file)){
				$this->with_t_closing[] = $file;
			}
		}

		foreach($this->with_t_closing as $filename)
			print($filename."\n");
	}

}

if (isset($argv[1])) {
	chdir($argv[1]);
	$obj = new TokenChecker('./', true);
} else {
	$obj = new TokenChecker('./', true);
}

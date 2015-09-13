<?php

$dir = './';

$files = scandir($dir);

function check_tokens ($file) { 
	$tokens = token_get_all(file_get_contents($file));
	$lex = [];
	foreach ($tokens as $token) { 
		$lex[]=$token[0];
		if (in_array('378', $lex)){ 
				return $file;
		}
	}
}

$with_tags = [];
foreach ($files as $d) { 
	if(preg_match('/^.*\.php/', $d) == 1) {
		$with_tags[] = check_tokens($d);
	}
}	

//print_r($files);

print_r($with_tags);
//print_r(gettype($with_tags[0])); 

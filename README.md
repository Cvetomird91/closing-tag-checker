A tool to check the current directory for PHP scripts with a closing tag. 

I used the Tokenizer PHP functions to scan for the T_CLOSE_TAG
token.

Portability was added because of different token ids for T_CLOSE_TAG inbetween different PHP versions.

The script prints an array with the files containing a closing PHP tag at EOF.

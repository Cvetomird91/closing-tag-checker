A tool to check the current directory for files without a closing tag. I used the Tokenizer PHP functions to find the T_CLOSE_TAG
token. Portability was added because of different token ids for T_CLOSE_TAG inbetween different PHP versions.

The script is supposed to work with PHP versions from 5.1 to 5.6.

The script prints an array with the files containging a closing PHP tag at EOF.
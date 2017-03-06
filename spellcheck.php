<?php

function customErrorHandler($errno, $errstr, $errfile, $errline) {
	echo "\tERROR: " . $errstr . ' :'.$errline . "\n\n";
	die;
}
set_error_handler( 'customErrorHandler' );

echo "\n";

$argv[1] = isset( $argv[1] ) ? $argv[1] : null;
$argv[2] = isset( $argv[2] ) ? $argv[2] : null;

$spellChecker = new SpellChecker( $argv[1], $argv[2] );
$spellChecker->check();

echo "\n\n";

class SpellChecker {

	private $dictionary;
	private $fileToCheck;

	private $allMisspelledWords;

	function __construct( $dict, $file ) {
		
		if( is_null( $dict ) ) {
			trigger_error( 'Dictionary file not provided.' );
		} else if( !file_exists( $dict ) ) {
			trigger_error( $dict . ' does not exist.' );
		}

		if( is_null( $file ) ) {
			trigger_error( 'No file given to spell check.' );
		} else if( !file_exists( $file ) ) {
			trigger_error( $file . ' does not exist.' );
		}

		$this->dictionary = array_flip( array_map('trim', file( $dict ) ) );
		$this->fileToCheck = file( $file );

	}

	function check() {
		for( $i = 0; $i < count( $this->fileToCheck ); $i++ ) {
			$words = array(); 
			preg_match_all( "/[a-zA-Z\']+/", $this->fileToCheck[$i], $words ); 
			$this->checkLine( $i+1, $words[0], $this->fileToCheck[$i] );
		}

		if( count( $this->allMisspelledWords ) ) {
			echo "Misspelled words:\n";
			echo implode( "\n", $this->allMisspelledWords );
		} else {
			echo "No spelling mistakes found!";
		}
	}

	function checkLine( $lineNum, $lineWords, $fullLine ) {
		$misspelled = [];
		foreach( $lineWords as $word ) {

			if( !isset( $this->dictionary[strtolower( $word )] ) ) {
				$misspelled[] = $word;
			}
		}

		if( count( $misspelled ) ) {
			$column = 0;
			$match = array();
			foreach( $misspelled as $badWord ) {
				preg_match( '/\b' . $badWord . '\b/', substr( $fullLine, $column ), $match, PREG_OFFSET_CAPTURE );
				$column += $match[0][1];
				$this->allMisspelledWords[] = $lineNum . ':' . ( $column + 1 ) . "\t" . $badWord;
				$column++;
			}
		}
	}

}

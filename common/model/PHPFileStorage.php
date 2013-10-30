<?php

namespace common\model;


/**
 * A way of storing data in PHP files
 *
 * PHPFileStorage builds a .php file from the data
 * Data is appended to the end of the file nothing is removed
 * 
 * <?php
 * //key=3C%3Ea651a78bcf1b9a72b026ef639d0d3453a70aedd8%3C%3ECLzb4IjsvHkTUFESoaqRKM6lnYf3yhJ5tA7QwcpD%3E%3C1373277822
 * 
 * Pros: Cannot be read from client and no need for chown or chmod
 * 		 Fast write
 * Cons: Could be misstaken for code
 *       Usual file read/write problems
 *       Slow read
 *       File grows when appended with same key
 *       Nothing is removed
 *
 * @author  daniel.toll@lnu.se
 * 
 */
class PHPFileStorage {

	/**
	 * @param String $filePath Filepath
	 */
	public function __construct($filePath) {
		$this->filePath = $filePath;

		if (file_exists($this->filePath) == FALSE) {
			file_put_contents($this->filePath, "<?php");
		}
	}

	/**
	 * @param  String $key     key that stores the 
	 * @param  String $content [description]
	 */
	public function writeItem($key, $content) {
		//make safe by replacing characters by their url tokens
		$safeKey = urlencode($key);
		$safeContent = urlencode($content);

		//hide data as a comment<>key<>content
		$newLine = "\n//<>$safeKey<>$safeContent";
		
		//lock file for writing (append)
		$fileHandle = fopen($this->filePath, "a");
		fwrite($fileHandle, $newLine, strlen($newLine)); 
		fclose($fileHandle);

		\Debug::log("Wrote to PHPFileStorage", true, $content);
	}

	/**
	 * 
	 * @throws \Exception if item is not found
	 * @param  String $key 
	 * @return String Content
	 */
	public function readItem($key) {
		$safeKey = urlencode($key);

		$fileContents = file_get_contents($this->filePath);
		$fileLines = explode("\n", $fileContents);
		$numLines = count($fileLines);
		for ($i = 1; $i < $numLines; $i++) {
			$line = &$fileLines[$i];
			$lineParts = explode("<>", $line);

			if (strcmp($safeKey, $lineParts[1]) == 0) {
				$contentFound = $lineParts[2];
				//we do not break since there can be a later addition with same key
			}
		}
		if (isset($contentFound)) {
			return urldecode($contentFound);
		}
		throw new \Exception("Content not found");
	}

	/**
	 * Read all items in storage
	 * @return array String(key) String(content)
	 */
	public function readAll() {
		$ret = array();

		$fileContents = file_get_contents($this->filePath);
		$fileLines = explode("\n", $fileContents);
		$numLines = count($fileLines);
		for ($i = 1; $i < $numLines; $i++) {
			$line = &$fileLines[$i];
			$lineParts = explode("<>", $line);

			$ret[urldecode($lineParts[1])] = urldecode($lineParts[2]); 
		}
		
		return $ret;
	}
}

/*
$file = new PHPFileStorage("./testPHPFileStorage.php");

$key = rand();
$content = "fgenhr847=0qrui<?php ?>j erjnlkwqj//r\n30'985r0+%&\"#¤%/\"(YHG)";
$file->writeItem($key, $content);

$file->writeItem(2, "item");

assert (strcmp($content, $file->readItem($key)) == 0);

$dataArray = $file->readAll();
var_dump($dataArray);
*/
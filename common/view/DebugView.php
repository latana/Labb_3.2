<?php

namespace common\view;

require_once("common/Debug.php");

class DebugView {
	
	/**
	 * @return String HTML
	 */
	public function getDebugData() {
		$debugItems = "";
		foreach (array_reverse(\Debug::getList()) as $item) {
			$debugItems .= $this->showDebugItem($item);
		}

		$dumps = "
		<hr/>
			<h2>Debug Items</h2>
				   			<ol>
				   				$debugItems
				   			</ol>
			<h2>Debug Globals</h2>
			";
		
		$dumps .= $this->arrayDump($_GET, "GET");
		$dumps .= $this->arrayDump($_POST, "POST");
		
		$dumps .= $this->arrayDump($_COOKIE, "COOKIES");
		$dumps .= $this->arrayDump($_SESSION, "SESSION");
		$dumps .= $this->arrayDump($_SERVER, "SERVER");
		
		
		
		return $dumps;
	}
	
	/**
	 * @param  commonmodelDebugItem $item [description]
	 * @return String HTML
	 */
	private function showDebugItem(\common\model\DebugItem $item) {
		
		if ($item->debugBacktrace != null) {
			$debug = "<h4>Trace:</h4>
					 <ul>";
			foreach (array_reverse($item->debugBacktrace) AS $key => $row) {
				if ($key == 0) { //skip debug
					continue;
				}
				$debug .= "<li> $key " . $row['file'] . " Line : " . $row["line"] .  "</li>";
			}
			$debug .= "</ul>";
		} else {
			$debug = "";
		}
		
		if ($item->object != null)
			$object = var_export($item->object, true);
		else 
			$object = "";
		$ret =  "<li>
					<Strong>$item->item </strong> $item->calledFromFile
					<pre>$object</pre>
					
					$debug
					
				</li>";
				
		return $ret;
	}
	
	
	/**
	 * @param  array $array
	 * @param  String $title
	 * @return String HTML
	 */
	private function arrayDump($array, $title) {
		$ret = "<h3>$title</h3>
		
				<ul>";
		foreach ($array as $key => $value) {
			$stringValue = print_r($value, true);
			$htmlSafe = htmlspecialchars($stringValue);
			$ret .= "<li>$key => [$htmlSafe]</li>";
		}
		$ret .= "</ul>";
		return $ret;
	}
}

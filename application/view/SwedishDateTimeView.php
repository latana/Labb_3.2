<?php

namespace application\view;

class SwedishDateTimeView {

	/**
	 * Get HTML representation of a timestamp in Swedish
	 *
	 * @param  int unix timestamp ex. from (time())
	 * @return String HTML
	 */
	public function getTimeString($timeStamp) {
		$month = date("m");
		$day = date("w");
		$dayofMonth = date("j");
		$year = date("Y");
		$timeSeconds = date("H:i:s"); 
		
		$dayname = $this->translateDay($day);
		$monthName = $this->translateMonth($month);
		
		return "<p>$dayname, den $dayofMonth $monthName år $year. Klockan är [$timeSeconds]. " ;
	}
	
	
	/**
	 * @param integer $dayOfWeekInteger, Values[1-7]
	 * @return String HTML
	 */
	private function translateDay($dayOfWeekInteger) {
		assert($dayOfWeekInteger >= 1);
		assert($dayOfWeekInteger <= 7);

		$dayname = "";
		//Translate to Swedish
		switch ($dayOfWeekInteger) {
			case 1 : $dayname = "Måndag"; break;
			case 2 : $dayname = "Tisdag"; break;
			case 3 : $dayname = "Onsdag"; break;
			case 4 : $dayname = "Torsdag"; break;
			case 5 : $dayname = "Fredag"; break;
			case 6 : $dayname = "Lördag"; break;
			case 7 : $dayname = "Söndag"; break;
		}
		return $dayname;
	}
	
	/**
	 * @param integer $monthsInteger, Values[1-12]
	 * @return String HTML
	 */
	private function translateMonth($monthsInteger) {
		assert($monthsInteger >= 1);
		assert($monthsInteger <= 12);
		//Translate to Swedish
		$monthNames = array("Januari", 
							"Februari",
							"Mars",
							"April",
							"Maj",
							"Juni",
							"Juli",
							"Augusti", 
							"September", 
							"Oktober", 
							"November", 
							"December");
	 	return $monthNames[$monthsInteger-1];
	}
}
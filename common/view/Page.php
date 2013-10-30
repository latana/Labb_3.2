<?php

  namespace common\view;
  /**
   * Basic class to accumulate several properties of a webbpage
   * Used in order to let several controllers contribute to different parts of the (X)HTML document 
   * and still just return one object.
   *
   */
  class Page {
      
    //Properties of the document
    public $title = "";
    public $body = "";
    
  	public function __construct($title, $body) {
  		$this->title = $title;
  		$this->body = $body;
  	}
	
    
    
    /** Merges(adds) two Page object and returns the combined result 
     * $param Page $otherPage page to merge with
     * @return Page
     */
    public function Merge(Page $otherPage) {
      $ret = new Page();
      
      //Note $this must be used to access the member-variables of the object!!!
      $ret->title = $this->title . " " . $otherPage->title;
      $ret->body = $this->body . "\n" .$otherPage->body;
      
      return $ret;
    }
  }


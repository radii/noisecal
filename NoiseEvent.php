<?php
class Event {
	private $mOriginTitle;
	private $mDetails;

	private function __construct() {}

	/**
	 * Factory function
	 */
	public static function newFromTitle($title) {
		$evt = new self();
		$evt->mOriginTitle = $title;
		$evt->initialize();
		return $evt;
	}
	
	protected function initialize() {
		global $wgParser;
		$article = new Article($this->mOriginTitle);
		$content = $article->getContent();
        $this->parse($content);
	}

	protected function parse($text) {
	    global $wgParser;
	    $wgParser->disableCache();
        $parser = new Parser();
	    
	    // Find all the headings.
        preg_match_all('/^(={1,6})(.*)\1$(.*)$/m', $text, $matches,  PREG_SET_ORDER);

        $headings = array();
        foreach ($matches as $match) {
            // $level = strlen($match[1]);
            $heading = trim($match[2]);
            $headings[] = $heading;
        }
        
        $heading_index = array_search("Event Details", $headings);
        if ($heading_index === FALSE) {
            // No event details contained in this article.
        } else {
            $section = $parser->getSection($text, $heading_index + 1);
            preg_match_all('/^\* ([A-Za-z]*): (.*)$/m', $section, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $this->mDetails[strtolower($match[1])] = $match[2];
            }
        }
	}
}
?>

<?php
App::import("Vendor", "Markdown");
class MarkdownHelper extends AppHelper {
	private $cachePrefix = "markdown_";
	private $cacheStore = 'default';
	/**
	* Returns the HTML equivalent of the markdown
	*/
	public function render($markdown, $stripHtmlEntities = false) {
		$key = $this->getCacheKey($markdown, $stripHtmlEntities);
		$html = Cache::read($key, $this->cacheStore);
		if(!$html) {
			$markdown = $stripHtmlEntities === true ? htmlspecialchars($markdown) : $markdown;
			$html = Markdown($markdown);
			Cache::delete($key, $this->cacheStore);
			Cache::write($key, $html, $this->cacheStore);
		}
		return $html;
	}

	private function getCacheKey($markdown, $stripHtmlEntities) {
		$strip = $stripHtmlEntities ? "-cleaned" : null;
		return $this->cachePrefix . md5($markdown) . $strip;
	}
}
?>
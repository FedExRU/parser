<?php

final class HtmlParser
{
	private $libraryPath;

	public function __construct($pathToSimpleDomHtmlLibrary)
	{
		$this->libraryPath = $pathToSimpleDomHtmlLibrary;

		include $this->libraryPath;

		$this->validateLibrary();
	}

	public function getLastPageNumber(string $page):int
	{
		$pageNumber = 0;

		$html = str_get_html($page);

		$html = $html->find('div[data-qa=pager-block] .bloko-button');

		foreach($html as $element){
			$newPageNumber = (int)$element->plaintext;
			if($newPageNumber > $pageNumber)
				$pageNumber = $newPageNumber;
		}

		return $pageNumber;
	}

	private function validateLibrary():void
	{
		if(!class_exists('simple_html_dom_node') && !property_exists('simple_html_dom', 'find'))
			throw new Exception("It is not simplehtmldom library");	
	}
}

?>
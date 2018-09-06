<?php

final class HtmlParser implements MasterParserInterface, HtmlParserInterface
{
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

	public function parseVacanciesLinks(string $page):array
	{
		$html = str_get_html($page);

		$links = $html->find('a[data-qa=vacancy-serp__vacancy-title]');

		$urls = [];

		foreach ($links as $link) {
			array_push($urls, $link->href);
		}

		return $urls;
	}

	public function parseVacancyInfo(array $pages):array
	{	
		$fullData = [];

		foreach($pages as $page){

			$html = str_get_html($page['content']);

			if(is_a($html, 'simple_html_dom')){
				$data = [
					'title' => htmlspecialchars_decode(stripslashes($this->presentText($html->find('[data-qa=vacancy-title]')))),
					'text' => htmlspecialchars_decode(stripslashes($this->presentText($html->find('div[data-qa=vacancy-description] *')))),
					'address' => $html->find('[data-qa=vacancy-view-raw-address]', 0)->plaintext,
					'salaryString' => $html->find('[class=vacancy-salary]', 0)->plaintext,
					'salaryMin' => (int) $html->find('meta[itemprop="minValue"]', 0)->content,
					'salaryMax' => (int) $html->find('meta[itemprop="maxValue"]', 0)->content,
					'datePosted' => $html->find('meta[itemprop="datePosted"]', 0)->content,
				];

				if(empty($data['address']))
				{
					$data['address'] = $html->find('[data-qa=vacancy-company] p', 1)->plaintext;
				}
			}
				
			$data['url'] = $page['url'];

			$fullData[] = $data;

		}

		return $fullData;
	}

	private function presentText(array $initialText):string
	{	
		$text = '';

		foreach ($initialText as $element) {
			$text .= $element->plaintext.' ';
		}

		return $text;
	}

}

?>
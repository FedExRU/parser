<?php 

final class Parser
{
	const MASTER_URL = 'https://hh.ru/search/vacancy';

	const PAGE_ATTRIBUTE = '?page=';

	private $urls = [];

	protected $currentPage;

	private $firstPageNumber = 0;

	private $lastPageNumber = 0;

	public function initUrls():void
	{
		for($i = $this->firstPageNumber; $i < $this->lastPageNumber; $i++){
			array_push($this->urls, self::MASTER_URL.self::PAGE_ATTRIBUTE.$i);
		}
	}
	
	/**
	 * @var Getters and Setters
	 */

	public function getFirstPageNumber():int
	{
		return $this->firstPageNumber;
	}

	public function setFirstPageNumber(int $number):void
	{
		$this->firstPageNumber = $number;
	}

	public function getLastPageNumber():int
	{
		return $this->lastPageNumber;
	}

	public function setLastPageNumber(int $number):void
	{
		$this->lastPageNumber = $number;
	}

	public function getFirstPage():string
	{
		$ch = curl_init(self::MASTER_URL);

		self::buildChannel($ch);

		$html = curl_exec($ch);

		curl_close($ch);

		return $html;
	}

	public function parse()
	{
		$multi = curl_multi_init();

		$handles = [];

		$htmls = [];

		foreach($this->urls as $url){

			$ch = curl_init($url);

			self::buildChannel($ch);

			curl_multi_add_handle($multi, $ch);

			$handles[$url] = $ch;
		}

		self::doCurlMultiExec($multi, $active);

		while ($active && $mrc == CURLM_OK) {
			
			if(curl_multi_select($multi) == -1){
				usleep(100);
			}

			self::doCurlMultiExec($multi, $active);
		}

		foreach ($handles as $channel) {
			$html = curl_multi_getcontent($channel);
			
			$this->currentPage = $html;

			array_push($htmls, $html);
			curl_multi_remove_handle($multi, $channel);
		}

		curl_multi_close($multi);

		return $htmls;
	}

	private static function doCurlMultiExec(&$multi, &$active)
	{
		do {
			$mrc = curl_multi_exec($multi, $active);
		} while ( $mrc == CURLM_CALL_MULTI_PERFORM);
	}

	private static function buildChannel(&$ch):void
	{
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	}
}
<?php 

class SuperjobParser extends BaseParser
{
	const MASTER_URL = 'https://api.superjob.ru/2.0/vacancies/?count=100';

	const PAGE_ATTRIBUTE = '&page=';

	const MIN_LIMIT = 100;

	const APP_SECRET = 'v1.r07a368b2d2e415a1d43c71b136c50b07e3c48ff7041b4ef2c23e56720bcd8b698930bff9.885cf751dcb333a06da19d321d44980efa6e941e';

	private $firstPageNumber = 0;

	private $lastPageNumber = 5;

	private $urls = [];

	private $vacancies = [];

	private $vacanciesParser;

	public function __construct(\Curl\MultiCurl $sourse, VacanciesParserInterface $vacanciesParser)
	{
		parent::__construct($sourse);

		$this->vacanciesParser = $vacanciesParser;
	}

	public function initUrls():void
	{
		$catsData = $this->parsePage('https://api.superjob.ru/2.0/catalogues/', false);
		
		for($i = $this->firstPageNumber; $i < $this->lastPageNumber; $i++){
			foreach($catsData as $cat)
				array_push($this->urls, self::MASTER_URL.self::PAGE_ATTRIBUTE.$i.'&catalogues='.$cat->key);
		}

		// echo "<pre>";
		// var_dump($this->urls);
		// die();

	}

	public function getFirstPageNumber():int
	{
		return $this->firstPageNumber;
	}

	public function setFirstPageNumber(int $number):void
	{
		$this->firstPageNumber = $number;
	}

	public function getLastPageNumber($page = null):int
	{	
		if(is_null($page))
			return $this->lastPageNumber;
		else
			return $page->meta->total;
	}

	public function setLastPageNumber(int $number):void
	{
		$this->lastPageNumber = $number;
	}

	public function getFirstPage()
	{	
		return $this->parsePage(self::MASTER_URL, false);
	}

	public function getUrls()
	{
		return $this->urls;
	}

	public function successCallback($content, ...$attributes)
	{	
		// if(is_array($this->vacancies))
		$this->vacancies = array_merge($this->vacancies, $this->vacanciesParser->parseVacancy($content));
		// else{
		// 	echo "<pre>";
		// 	var_dump($this->vacanciesParser->parseVacancy($content));
		// 	die();
		// }
	}

	public function addHeaders(&$curlSourse)
	{
		$curlSourse->setHeader('X-Api-App-Id', self::APP_SECRET);
	}

	public function getVacansies($asJson = false)
	{
		if(!$asJson)
			return $this->vacancies;
		else
			return json_encode($this->vacancies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
<?php

final class HhRuParser extends BaseParser
{
	const MASTER_URL = 'https://hh.ru/search/vacancy';

	const PAGE_ATTRIBUTE = '?page=';

	private $firstPageNumber = 0;

	private $lastPageNumber = 0;

	private $urls = [];

	private $vacanciesParser;

	public function __construct(\Curl\MultiCurl $sourse, VacanciesParserInterface $vacanciesParser)
	{
		parent::__construct($sourse);

		$this->vacanciesParser = $vacanciesParser;
	}

	public function initUrls():void
	{
		for($i = $this->firstPageNumber; $i < $this->lastPageNumber; $i++){
			array_push($this->urls, self::MASTER_URL.self::PAGE_ATTRIBUTE.$i);
		}
	}

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
		return $this->parsePage(self::MASTER_URL);
	}

	public function getUrls()
	{
		return $this->urls;
	}

	public function successCallback($content, ...$attributes)
	{
		$this->vacanciesParser->parseVacancy($content);
	}
}
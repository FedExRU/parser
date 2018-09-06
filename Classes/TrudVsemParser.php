<?php 

class TrudVsemParser extends BaseParser
{
	const MASTER_URL = 'http://opendata.trudvsem.ru/api/v1/vacancies?limit=100';

	const PAGE_ATTRIBUTE = '&offset=';

	const MIN_LIMIT = 100;

	const CATS = 'DeskWork,Safety,Management,StateServices,Resources,HomePersonal,Communal,Medicine,InformationTechnology,Culture,HumanRecruitment,Consulting,RootLightIndustry,Forest,Marketing,MechanicalEngineering,Metallurgy,Food,Sales,Industry,NotQualification,WorkingSpecialties,Agricultural,BuldindRealty,Transport,Restaurants,ServiceMaintenance,Finances,ChemicalAndFuelIndustry,Jurisprudence';

	private $firstPageNumber = 0;

	private $lastPageNumber = 0;

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
		$cats = explode(',', self::CATS);

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
		if(!is_array($this->vacancies)){
			$this->vacancies = [];
		}

		if(!is_array($this->vacanciesParser->parseVacancy($content))){
			var_dump($this->vacanciesParser->parseVacancy($content));
			var_dump($content);
			die();
		}

		$this->vacancies = array_merge($this->vacancies, $this->vacanciesParser->parseVacancy($content));


	}

	public function getVacansies($asJson = false)
	{
		if(!$asJson)
			return $this->vacancies;
		else
			return json_encode($this->vacancies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
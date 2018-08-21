<?php 

class VacansiesParser implements VacanciesParserInterface
{
	private $sourse;

	private $parser;

	private $htmlParser;

	public function __construct(PDO $connection, ParserInterface $parser, HtmlParserInterface $htmlParser)
	{
		$this->sourse 		= $connection;

		$this->parser 		= $parser;

		$this->htmlParser 	= $htmlParser;
	}

	public function parseVacancy($content)
	{
		$vacanciesUrl = $this->htmlParser->parseVacanciesLinks($content);

		if(!empty($vacanciesUrl)){

			$vacanciesPages = $this->parser->parsePages($vacanciesUrl, true);

			if(!empty($vacanciesPages)){
				$vacanciesData = $this->htmlParser->parseVacancyInfo($vacanciesPages);
			}

		}


		// 'data-qa="vacancy-description"';
		echo "<pre>";
		var_dump($vacanciesData);
		die();
		
	}
}
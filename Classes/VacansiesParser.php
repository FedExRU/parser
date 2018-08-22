<?php 

class VacansiesParser implements VacanciesParserInterface
{
	private $saver;

	private $parser;

	private $htmlParser;

	public function __construct(VacancySaverInterface $saver, ParserInterface $parser, HtmlParserInterface $htmlParser)
	{
		$this->saver 		= $saver;

		$this->parser 		= $parser;

		$this->htmlParser 	= $htmlParser;
	}

	public function parseVacancy($content)
	{
		$vacanciesPages = null;

		$vacanciesUrl = $this->htmlParser->parseVacanciesLinks($content);

		if(!empty($vacanciesUrl)){

			$vacanciesPages = $this->parser->parsePages($vacanciesUrl, true, false);

			if(!empty($vacanciesPages)){

				$vacanciesData = $this->htmlParser->parseVacancyInfo($vacanciesPages);

				if(!empty($vacanciesData))
					$this->saver->save($vacanciesData);
			}

		}

		return $vacanciesData;
		
	}
}
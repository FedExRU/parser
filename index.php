<?php

require_once('./Config/Dependencies.php');

use \Curl\MultiCurl;

/*
 * Initialize db connection
 */

header('Content-type: application/json');

$db = DB::getInstance()->getConnection();

$saver = new VacancyDbSaver($db);

/*
 * Initialize classes
 */

$htmlParser = new HtmlParser();

$parser = new HhRuParser(
	new MultiCurl(), 
	new VacansiesParser(
		$saver, 
		new Parser(
			new MultiCurl()
		), 
		$htmlParser
	)
);

/*
 * Define settings valiables
 */

$page = $parser->getFirstPage();

$lastPageNumber = $htmlParser->getLastPageNumber($page);

/*
 * Setting up classes
 */

$parser->setLastPageNumber(10);

$parser->initUrls();

/*
 * Get total html pages
 */

$parser->parsePages($parser->getUrls());


//var_dump($parser->getVacansies());
echo $parser->getVacansies(true);
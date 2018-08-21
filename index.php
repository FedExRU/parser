<?php

require_once('./Config/Dependencies.php');

use \Curl\MultiCurl;

/*
 * Initialize db connection
 */

$db = DB::getInstance()->getConnection();

/*
 * Initialize classes
 */

$htmlParser = new HtmlParser();

$parser = new HhRuParser(new MultiCurl(), new VacansiesParser($db, new Parser(new MultiCurl()), $htmlParser));

/*
 * Define settings valiables
 */

$page = $parser->getFirstPage();

$lastPageNumber = $htmlParser->getLastPageNumber($page);

/*
 * Setting up classes
 */

$parser->setLastPageNumber($lastPageNumber);

$parser->initUrls();

/*
 * Get total html pages
 */

echo "<pre>";

var_dump(count($parser->parsePages($parser->getUrls())));

die();

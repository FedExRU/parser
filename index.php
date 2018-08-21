<?php

require('./Classes/DB.php');
require('./Classes/HtmlParser.php');
require('./Classes/Parser.php');

require __DIR__ . '/vendor/autoload.php';

use \Curl\MultiCurl;

$db = DB::getInstance()->getConnection();

/*
 * Initialize classes
 */

$parser = new Parser(new MultiCurl());

$htmParser = new HtmlParser('./LIbs//SimpleDom/simple_html_dom.php');

/*
 * Define settings valiables
 */

$page = $parser->getFirstPage();

$lastPageNumber = $htmParser->getLastPageNumber($page);

/*
 * Setting up classes
 */

$parser->setLastPageNumber($lastPageNumber);

$parser->initUrls();

/*
 * Get total html pages
 */

echo "<pre>";
var_dump($parser->parse());
die();

// foreach($html->find('img') as $element) 
//        echo $element . '<br>';





// echo "<pre>";
// var_dump($html->find('div[data-qa=pager-block]'));
// die();
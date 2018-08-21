<?php

require('./Classes/DB.php');
require('./Classes/Parser.php');
require('./Classes/HtmlParser.php');

$db = DB::getInstance()->getConnection();

/*
 * Initialize classes
 */

$parser = new Parser();

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
var_dump(count($parser->parse()));
die();

// foreach($html->find('img') as $element) 
//        echo $element . '<br>';





// echo "<pre>";
// var_dump($html->find('div[data-qa=pager-block]'));
// die();
<?php

set_time_limit(10000000);

require_once('./Config/Dependencies.php');

use \Curl\MultiCurl;

/*
 * Initialize db connection
 */

header('Content-type: application/json');

// ini_set("memory_limit", "2000M");







$db = DB::getInstance()->getConnection();

$saver = new VacancyDbSaver($db);




// $sjParser = new SuperjobParser(
// 	new MultiCurl(),
// 	new SuperjobApiParser($saver)
// );

// $sjParser->initUrls();

// // var_dump($sjParser->getUrls());

// //$sjParser->parsePages($sjParser->getUrls());

// //var_dump($sjParser->getUrls());

// $newUrls = array_chunk($sjParser->getUrls(), 50);

// 	foreach($newUrls as $urls){
// 		$sjParser->parsePages($urls);
// 	}

//var_dump(count($sjParser->getVacansies()));

//$saver->save($sjParser->getVacansies());

// die();






// $jsonSaver = new JsonVacansiesParser($saver, [
// 	'container' => [
// 		'results' => [
// 			'vacancies'
// 		]
// 	],
// 	'singleContainer' => 'vacancy',
// 	'title' => 'job-name',
// 	'text' => [
// 		'duty',
// 		'requirement' => [
// 			'education',
// 			'qualification'
// 		],
// 	],
// 	'address' => [
// 		'addresses' => [
// 			'address' => [
// 				'location',
// 			]
// 		],
// 	],
// 	'salaryString' => 'salary',
// 	'salaryMin' => 'salary_min',
// 	'salaryMax' => 'salary_max',
// 	'datePosted' => 'creation-date',
// 	'url' => 'vac_url'
// ]);

// $tvParser = new TrudVsemParser(
// 	new MultiCurl(),
// 	$jsonSaver
// );

// $page = $tvParser->getFirstPage();


// $lastPageNumber = $tvParser->getLastPageNumber($page);


// $tvParser->setLastPageNumber(100);

// $tvParser->initUrls();

// $tvParser->parsePages($tvParser->getUrls());

// echo "<pre>";
// var_dump($tvParser->getLastPageNumber());
// die();







// die();


// for($i = 0; $i < 50; $i++){

	// $db = DB::getInstance()->getConnection();

	// $saver = new VacancyDbSaver($db);

	// /*
	//  * Initialize classes
	//  */

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

	
	 // * Define settings valiables
	 

	$page = $parser->getFirstPage();

	$lastPageNumber = $htmlParser->getLastPageNumber($page);

	/*
	 * Setting up classes
	 */

	$parser->setLastPageNumber($lastPageNumber);

	$parser->initUrls();

	$newUrls = array_chunk($parser->getUrls(), 1000);

	foreach($newUrls as $urls){
		$parser->parsePages($urls);
	}

	/*
	 * Get total html pages
	 */





	var_dump($parser->getVacansies());
	echo $parser->getVacansies(true);
// }

<?php

require('./Classes/DB.php');
require('./Classes/Parser.php');

$db = DB::getInstance()->getConnection();

$parser = new Parser();

echo "<pre>";
var_dump($parser->parse());
die();
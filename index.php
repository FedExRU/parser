<?php

require('./Classes/DB.php');


$db = DB::getInstance()->getConnection();



echo "<pre>";
var_dump($db);
die();
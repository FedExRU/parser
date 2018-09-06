<?php

interface MasterParserInterface
{
	public function getLastPageNumber(string $page):int;

	public function parseVacancyInfo(array $pages):array;
}	


	
<?php

interface HtmlParserInterface
{
	public function parseVacanciesLinks(string $page):array;

	public function getLastPageNumber(string $page):int;

	public function parseVacancyInfo(array $pages):array;
}
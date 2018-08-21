<?php

interface ParserInterface
{
	public function getPages():array;

	public function parsePage(string $url):string;

	public function parsePages(array $urls, bool $returnUrls = false):array;
}
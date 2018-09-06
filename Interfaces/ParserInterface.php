<?php

interface ParserInterface
{
	public function getPages():array;

	public function parsePage(string $url, bool $withCallback = true);

	public function parsePages(array $urls, bool $returnUrls = false, bool $withCallback = true);
}
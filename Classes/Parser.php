<?php 

final class Parser
{
	const MASTER_URL = 'https://hh.ru/search/vacancy';

	const PAGE_ATTRIBUTE = '?page=';

	private $sourse;

	private $urls = [
		'https://hh.ru/search/vacancy?enable_snippets=true&clusters=true&page=0',
		'https://hh.ru/search/vacancy?enable_snippets=true&clusters=true&page=1',
	];

	public function parse()
	{
		$multi = curl_multi_init();

		$handles = [];

		foreach($this->urls as $url){

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			curl_multi_add_handle($multi, $ch);
			$handles[$url] = $ch;
		}

		self::doCurlMultiExec($multi, $active);

		while ($active && $mrc == CURLM_OK) {
			
			if(curl_multi_select($multi) == -1){
				usleep(100);
			}

			self::doCurlMultiExec($multi, $active);
		}

		foreach ($handles as $channel) {
			$html = curl_multi_getcontent($channel);
			var_dump($html);
			curl_multi_remove_handle($multi, $channel);
		}

		curl_multi_close($multi);
	}

	private static function doCurlMultiExec(&$multi, &$active)
	{
		do {
			$mrc = curl_multi_exec($multi, $active);
		} while ( $mrc == CURLM_CALL_MULTI_PERFORM);
	}
}
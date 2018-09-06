<?php 

abstract class BaseParser implements ParserInterface
{
	const BAD_GATEWAY_ERROR_CODE = 502;

	private $pages = [];

	private $curlSourse;

	public function __construct(\Curl\MultiCurl $sourse)
	{
		$this->curlSourse = $sourse;
	}
	
	public function getPages():array
	{
		return $this->pages;
	}

	public function parsePage(string $url, bool $withCallback = true)
	{
		return array_pop($this->parsePages([$url], false, $withCallback));
	}

	public function parsePages(array $urls, bool $returnUrls = false, bool $withCallback = true, int $startFrom = 0, int $endTo = 100 )
	{	
		$this->resetPages();
		//$i = $startFrom;
		foreach($urls as $index => $url){
			// if($i == $endTo){
			// 	break;
			// }
			$this->curlSourse->addGet($url);
			$this->addHeaders($this->curlSourse);
			//$i++;
		}


		$this->curlSourse->success(function($instance) use ($returnUrls, $withCallback, &$i) {
			
			var_dump($i);
			// die();

			if(!$returnUrls)
				$content = $instance->response;
			else{
				$content = [
					'content' => $instance->response,
					'url' => $instance->url,
				];
			}

			var_dump('success '.$instance->url);


			$this->pages[] = $content;

			if($withCallback)
				$this->successCallback($content);

			$i++;

		});

		$this->curlSourse->error(function($instance) {
			// var_dump($instance);
			// die();
			// if($instance->errorCode === self::BAD_GATEWAY_ERROR_CODE)
				$this->parsePages([$instance->url]);
		});

		$this->curlSourse->start();

		return $this->getPages();
	}

	private function resetPages():void
	{
		$this->pages = [];
	}

	abstract public function successCallback($content, ...$attributes);

	public function addHeaders(&$curlSourse) {}
}
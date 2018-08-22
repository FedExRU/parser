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

	public function parsePage(string $url, bool $withCallback = true):string
	{
		return array_pop($this->parsePages([$url], false, $withCallback));
	}

	public function parsePages(array $urls, bool $returnUrls = false, bool $withCallback = true):array
	{	
		$this->resetPages();

		foreach($urls as $url){
			$this->curlSourse->addGet($url);
		}

		$this->curlSourse->success(function($instance) use ($returnUrls, $withCallback) {
			
			if(!$returnUrls)
				$content = $instance->response;
			else{
				$content = [
					'content' => $instance->response,
					'url' => $instance->url,
				];
			}

			$this->pages[] = $content;

			if($withCallback)
				$this->successCallback($content);

		});

		$this->curlSourse->error(function($instance) {
			if($instance->errorCode === self::BAD_GATEWAY_ERROR_CODE)
				$this->pages[] = array_pop($this->parsePages([$instance->url]));
		});

		$this->curlSourse->start();

		return $this->getPages();
	}

	private function resetPages():void
	{
		$this->pages = [];
	}

	abstract public function successCallback($content, ...$attributes);
}
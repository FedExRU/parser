<?php

class JsonVacansiesParser implements VacanciesParserInterface
{
	private $saver;

	private $structure;

	CONST CONTAINER = 'container';

	CONST SINGLE_CONTAINER = 'singleContainer';

	CONST TITLE = 'title';

	CONST TEXT = 'text';

	CONST URL = 'url';

	CONST ADDRESS = 'address';

	CONST SALARY_STRING =  'salaryString';

	CONST SALARY_MIN =  'salaryMin';

	CONST SALARY_MAX =  'salaryMax';

	CONST DATE_POSTED =  'datePosted';


	public function __construct(VacancySaverInterface $saver, array $structure)
	{
		$this->saver = $saver;

		$this->structure = $structure;
	}

	public function parseVacancy($content)
	{
		$container = $this->getContainer($content, self::CONTAINER);

		$single = $this->structure[self::SINGLE_CONTAINER];


		foreach($container as $vacancy){


			$vacancy = $vacancy->$single;

			$text =  preg_replace('#<[^>]+>#', ' ', $this->getContainer($vacancy, self::TEXT));

			$data[] = [

					'title' => $this->getContainer($vacancy, self::TITLE),
					'text' => preg_replace("/&#?[a-z0-9]+;/i","",$text),
					'address' => $this->getContainer($vacancy, self::ADDRESS),
					'salaryString' => $this->getContainer($vacancy, self::SALARY_STRING),
					'salaryMin' =>  $this->getContainer($vacancy, self::SALARY_MIN),
					'salaryMax' => $this->getContainer($vacancy, self::SALARY_MAX),
					'datePosted' => $this->getContainer($vacancy, self::DATE_POSTED),
					'url' => $this->getContainer($vacancy, self::URL),
			];

			$this->saver->save($data);

			//3789
		}

		return $data;
	}

	private function getContainer($content, $name = null, $customContainer = null)
	{
		if(is_null($customContainer))
			$container = $this->structure[$name];
		else{
			$container = $customContainer;
		}

		$newContent = $content;

		if(is_array($container)){

			if(sizeof($container) > 1){


				foreach ($container as $key => $field) {

					$result .= ' '.$this->getArrayData([ $key  => $field], $newContent, $name);

				}

			}
			else
				$result = $this->getArrayData($container, $newContent, $name);

			$newContent = $result;
		}
		else{
			$newContent = $newContent->$container;
		}

		return $newContent;
	}

	private function getArrayData($container, $newContent, $name = null)
	{	

		

		while(is_array($container)){

			if(sizeof($container) > 1){

				$data = [];

				foreach($container as $subField){
					$data[] = $this->getContainer($newContent, null, $subField);
				}

				$newContentData = implode(" ", $data);

			}
			else if(is_numeric(key($container))){
				$contentName = $container[key($container)];
			}
			else{
			
				$contentName = key($container);
			}

			$containerName = key($container);

			/*
			 * !!!! КОСТЫЛЬ !!!!
			 */

			if($name == 'address' && $contentName == 'location'){

				$foo = true;
				

				if(is_array($newContent))
					$newContent = array_shift($newContent);
				else
					$newContent = $newContent;

				$container = null;
			}
			/*
			 * !!!! КОНЕЦ КОСТЫЛЯ !!!
			 */
			else
				$container = $container[$containerName];

			if(!empty($newContentData))
				$newContent = $newContentData;
			else
				$newContent = $newContent->$contentName;


		}

		return $newContent;
	}
}
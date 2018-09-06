<?php

class SuperjobApiParser implements VacanciesParserInterface
{

	private $saver;

	public function __construct(VacancySaverInterface $saver)
	{
		$this->saver = $saver;
	}

	public function parseVacancy($content)
	{
		$data = [];
		//if($content->total != 0){
			foreach($content->objects as $vacancy){
				$d = [
					'title' => $vacancy->profession,
					'text' => $vacancy->candidat.' '.$vacancy->work,
					'address' => is_null($vacancy->address) ? $vacancy->client->town->title : $vacancy->address,
					'salaryString' => $vacancy->payment,
					'salaryMin' => $vacancy->payment_from,
					'salaryMax' => $vacancy->payment_to,
					'url' => $vacancy->link,
					'datePosted' => date('Y-m-d', $vacancy->date_published)
				];

				$data[] = $d;
				$this->saver->save([$d]);
			}
			if(count($data) != 0)
				$this->saver->save($data);
			else{
				var_dump($data);
				var_dump($content);
				//die();
			}
		//}

		return $data;

	}
}

//C:\Users\uruadyshev\Downloads\open_server_5_2_7_ultimate.exe
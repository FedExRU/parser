<?php

final class VacancyDbSaver implements VacancySaverInterface
{
	public $sourse;

	public function __construct(PDO $connection)
	{
		$this->sourse = $connection;
	}

	public function save(array $data)
	{	
		foreach($data as $vacancy)
		{
			if(!$this->hasVacancy($vacancy['url']))
			{	
				$this->insertVacancy($vacancy);	
			}	
		}
	}

	private function insertVacancy(array $vacancy):bool
	{
		$stmt = $this->sourse->prepare("INSERT INTO  vacancies (title, text, address, salary_string, salary_min, salary_max, url, date_posted) VALUES (:title, :text, :address, :salary_string, :salary_min, :salary_max, :url, :date_posted) ");

		$stmt->bindParam(':title', $vacancy['title']);
		$stmt->bindParam(':text', $vacancy['text']);
		$stmt->bindParam(':address', $vacancy['address']);
		$stmt->bindParam(':salary_string', $vacancy['salaryString']);
		$stmt->bindParam(':salary_min', $vacancy['salaryMin']);
		$stmt->bindParam(':salary_max', $vacancy['salaryMax']);
		$stmt->bindParam(':url', $vacancy['url']);
		$stmt->bindParam(':date_posted', $vacancy['datePosted']);

		try {

			$valid = $stmt->execute();

		} catch (PDOException | Exception $e) {

			echo "DataBase Error: ".$e->getMessage();

		}

		return $valid;
	}

	private function hasVacancy(string $url)
	{
		$query = $this->sourse->prepare('SELECT id FROM vacancies WHERE url = :url');


		$query->execute([':url' => $url]);


		return !is_bool($query->fetch(PDO::FETCH_LAZY));
	}
}
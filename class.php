<?php

	class tenders
	{
		function getDirections()
		{
			$directions = [];
			if (Yii::$app->id == 'crm')
			{
				$sql = 'SELECT * FROM directions';
				$rows = Yii::$app->db->createCommand($sql)->queryAll();
				foreach ($rows as $row)
				{
					$directions[$row['id']] = $row;
				}
			}
			else
			{
				$directions = [
					1 => [
						'id' => 1,
						'name' => 'Обучение'
					],
					2 => [
						'id' => 2,
						'name' => 'СОУТ',
					],
					3 => [
						'id' => 3,
						'name' => 'Производственный контроль',
					],
					4 => [
						'id' => 4,
						'name' => 'Оценка производственных рисков'
					],
					5 => [
						'id' => 5,
						'name' => 'Аутсорсинг охраны труда',
					],
					6 => [
						'id' => 6,
						'name' => 'Аудит охраны труда'
					],
				];
			}
			return $directions;
		}
		
		function prepare_summ($summ)
		{
			$summ = str_replace(' ', '', $summ);
			$summ = str_replace(',', '.', $summ);
			return $summ;
		}
		
	}
?>
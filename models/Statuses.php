<?php
	namespace app\plugins_code\tenders\models;
	
	class Statuses
	{
		public $statuses = [
			/*0 => [
				'name' => 'На согласовании',
				'bg' => 'white',
			],
			1 => [
				'name' => 'Не согласован',
				'bg' => 'white',
			],*/
			2 => [
				'name' => 'Подача заявок',
				'bg' => 'white',
			],
			3 => [
				'name' => 'Ожидание результатов',
				'bg' => 'white',
				'colors' => 'red'
			],
			4 => [
				'name' => 'Проигран',
				'bg' => '#f2dede',
			],
			5 => [
				'name' => 'Выигран',
				'bg' => 'white',
				'colors' => 'red'
			],
			7 => [
				'name' => 'Услуга оказывается',
				'bg' => 'white',
				'bgb' => '#dff0d8'
			],			
			6 => [
				'name' => 'Завершен',
				'bg' => '#dff0d8',
			],
			8 => [
				'name' => 'Отмена закупки по инициативе заказчика',
				'bg' => 'white',
				'bgb' => '#f2dede'
			],	
		];
	}
	
?>
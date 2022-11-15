<?php
	// тендеры
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders', [
		'id' => 'pk',
		'client_id' => 'integer NOT NULL DEFAULT 0',		// заказчик
		'manager_id' => 'integer NOT NULL DEFAULT 0',		// контактное лицо
		'platform_id' => 'integer NOT NULL DEFAULT 0',		// уникальный идентификатор платформы
		'platform_link' => 'text',							// ссылка на тендер
		'tender_status' => 'integer NOT NULL DEFAULT 0',	// уникальный идетификатор тендера
		'description' => 'text',							// описание
		'tender_sum' => 'text',								// итоговая сумма тендера
		'direction_id' => 'integer NOT NULL DEFAULT 0',		// уникальный идентификатор направления
		'deleted' => 'integer NOT NULL DEFAULT 0',
	]);
	$command->query();
	
	// платформы не которых находятся тендеры
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders_platforms', [
		'id' => 'pk',
		'name' => 'text',
		'deleted' => 'integer NOT NULL DEFAULT 0',
		
	]);
	$command->query();
	
	// уникальный идентификаторы статусов
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders_statuses', [
		'id' => 'pk',
		'name' => 'text',
		'color' => 'text',
		'color_text' => 'text',
		'deleted' => 'integer NOT NULL DEFAULT 0',
	]);	
	$command->query();

	// таблица с проверяющими тендеры
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders_verifications', [
		'id' => 'pk',
		'user_id' => 'integer NOT NULL DEFAULT 0',
		'direction_id' => 'integer NOT NULL DEFAULT 0',
	]);
	$command->query();
	
	// файлы тендеров
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders_files', [
		'id' => 'pk',
		'tender_id' => 'integer NOT NULL DEFAULT 0',
		'file' => 'text',
		'name' => 'text',
		'size' => 'integer',
		'deleted' => 'integer NOT NULL DEFAULT 0',
	]);	
	$command->query();
?>
<?php
	// тендеры
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders_and_contracts', [
		'id' => 'pk',
		'tender_id' => 'integer NOT NULL DEFAULT 0',
		'contract_id' => 'integer NOT NULL DEFAULT 0',
	]);
	$command->query();
?>
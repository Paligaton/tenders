<?php
	$command = Yii::$app->db->createCommand();
	$command->createTable('plugins_tenders_comments', [
		'id' => 'pk',
		'user_id' => 'integer NOT NULL DEFAULT 0',
		'time' => 'integer NOT NULL DEFAULT 0',
		'text' => 'text',
		'deleted' => 'integer NOT NULL DEFAULT 0',
	]);
	$command->query();
?>
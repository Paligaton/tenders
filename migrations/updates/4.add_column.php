<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'author_id', 'int default 0');
	$command->query();
	
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'created_time', 'int default 0');
	$command->query();	
	
?>
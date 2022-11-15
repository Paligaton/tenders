<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders_files', 'time', 'int default 0');
	$command->query();
?>
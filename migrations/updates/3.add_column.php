<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'verification_id', 'int default 0');
	$command->query();
?>
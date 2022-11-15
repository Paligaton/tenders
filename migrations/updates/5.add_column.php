<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders_comments', 'tender_id', 'int default 0');
	$command->query();
?>
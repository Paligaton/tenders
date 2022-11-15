<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'eis_link', 'text');
	$command->query();
?>
<?php
	$command = Yii::$app->db->createCommand();
	$command->alterColumn('plugins_tenders', 'date_of_contarct', 'int default 0');
	$command->query();
?>
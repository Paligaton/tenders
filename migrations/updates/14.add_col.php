<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'date_of_contarct', 'datetime');
	$command->query();
?>
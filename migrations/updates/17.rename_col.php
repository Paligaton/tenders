<?php
	$command = Yii::$app->db->createCommand();
	$command->renameColumn('plugins_tenders', 'date_of_contarct', 'date_contarct');
	$command->query();
?>
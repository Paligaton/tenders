<?php
	$command = Yii::$app->db->createCommand();
	$command->renameColumn('plugins_tenders', 'date_contarct', 'date_contract');
	$command->query();
?>
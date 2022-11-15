<?php
	$command = Yii::$app->db->createCommand();
	$command->alterColumn('plugins_tenders', 'date_of_contarct', 'integer NOT NULL DEFAULT 0');
	$command->query();
?>
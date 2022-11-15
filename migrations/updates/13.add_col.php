<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'deadline_for_filing_an_application_for_participation', 'integer NOT NULL DEFAULT 0');
	$command->query();
?>
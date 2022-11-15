<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'tender_victor', 'text');
	$command->query();
	
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'tender_summ_victor', 'text');
	$command->query();	
	
?>
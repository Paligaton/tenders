<?php
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'verification_user_id', 'int default 0');
	$command->query();
	
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'date_start', 'int default 0');
	$command->query();

	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'tender_summ', 'text');
	$command->query();

	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'tender_summ_min', 'text');
	$command->query();	
	
	$command = Yii::$app->db->createCommand();
	$command->addColumn('plugins_tenders', 'organization_name', 'text');
	$command->query();		
	
?>
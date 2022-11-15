<?php

	use app\models\Alerts;

	$request = Yii::$app->request;

	$id = $request->get('id');
	
	Yii::$app->db->createCommand()->delete('plugins_tenders_verifications', 'id='.$id)->execute();
	
	$Alerts = new Alerts();
	$Alerts->user_id = \Yii::$app->user->id;
	$Alerts->type = "success";
	$Alerts->text = "Проверяющий тендеры удален успешно";
	$Alerts->date_create = time();
	$Alerts->date_view = 0;
	$Alerts->save(false);				
	
	header('Location: /admin/plugins/view/?plug_name=tenders&action=index');
	exit();			
	
?>
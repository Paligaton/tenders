<?php

	use app\models\Alerts;

	$request = Yii::$app->request;
	
	$id = $request->get('id');
	$action = $request->post('action');
	
	if ($action == 'update')
	{
		$name = $request->post('name');
		
		$params = [
			'name' => $name,
		];
		Yii::$app->db->createCommand()->update('plugins_tenders_platforms', $params, 'id='.$id)->execute();		
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Площадка изменена успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);			
		
		header('Location: /admin/plugins/view/?plug_name=tenders&action=index');
		exit();		
	}
	
	$sql = 'SELECT * FROM plugins_tenders_platforms WHERE id = '.$id;
	$platform = Yii::$app->db->createCommand($sql)->queryOne();
	
	$params['platform'] = $platform;
	
?>
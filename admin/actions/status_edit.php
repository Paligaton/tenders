<?php

	use app\models\Alerts;

	$request = Yii::$app->request;
	
	$id = $request->get('id');
	$action = $request->post('action');
	
	if ($action == 'update')
	{
		$name = $request->post('name');
		$color = $request->post('color');
		$color_text = $request->post('color_text');		
		
		$params = [
			'name' => $name,
			'color' => $color,
			'color_text' => $color_text,
		];
		Yii::$app->db->createCommand()->update('plugins_tenders_statuses', $params, 'id='.$id)->execute();		
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Статус изменен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);			
		
		header('Location: /admin/plugins/view/?plug_name=tenders&action=index');
		exit();		
	}
	
	$sql = 'SELECT * FROM plugins_tenders_statuses WHERE id = '.$id;
	$status = Yii::$app->db->createCommand($sql)->queryOne();
	
	$params['status'] = $status;
	
?>
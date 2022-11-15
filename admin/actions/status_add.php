<?php

	use app\models\Alerts;

	$request = Yii::$app->request;
	
	$action = $request->post('action');
	
	if ($action == 'create')
	{
		$name = $request->post('name');
		$color = $request->post('color');
		$color_text = $request->post('color_text');
		$deleted = 0;
		
		$params = [
			'name' => $name,
			'color' => $color,
			'color_text' => $color_text,
			'deleted' => $deleted
		];
		Yii::$app->db->createCommand()->insert('plugins_tenders_statuses', $params)->execute();		
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Статус создан успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);				
		
		header('Location: /admin/plugins/view/?plug_name=tenders&action=index');
		exit();		
	}
?>
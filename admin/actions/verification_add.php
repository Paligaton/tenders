<?php

	use app\models\Alerts;

	$request = Yii::$app->request;
	
	$action = $request->post('action');
	
	if ($action == 'create')
	{
		$user_id = $request->post('user_id');
		$direction_id = $request->post('direction_id');
		
		$params = [
			'user_id' => $user_id,
			'direction_id' => $direction_id
		];
		Yii::$app->db->createCommand()->insert('plugins_tenders_verifications', $params)->execute();		
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Проверяющий тендер создан успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);				
		
		header('Location: /admin/plugins/view/?plug_name=tenders&action=index');
		exit();		
	}
	
	// получаем список всех пользователей
	$users = [];
	$sql = 'SELECT * FROM user_profiles ORDER BY name';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$users[$row['user_id']] = $row;
	}

	// вытаскиваем все направления
	$directions = $tenders->getDirections();

	$params['users'] = $users;
	$params['directions'] = $directions;
	
?>
<?php
	use app\plugins_code\tenders\models\tender;
	use app\models\Alerts;
	use app\helpers\CryptoHelper;
	use app\models\Settings;
	
	$request = Yii::$app->request;
	$id = $request->get('id');
	$action = $request->post('action');

	// получаем список согласовывающих договора
	$verifications = [];
	$sql = 'SELECT * FROM plugins_tenders_verifications';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$verifications[$row['id']] = $row;
	}

	// получаем список направлений
	$directions = $tenders->getDirections();

	// получаем список всех пользователей
	$users = [];
	$sql = 'SELECT * FROM user_profiles';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$users[$row['user_id']] = $row;
	}
	
	// получаем список тендерных площадок
	$platforms = [];
	$sql = 'SELECT * FROM plugins_tenders_platforms';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$platforms[$row['id']] = $row;
	}	

	// получаем информацию о тендере
	$sql = 'SELECT * FROM plugins_tenders WHERE id = '.$id;
	$row = Yii::$app->db->createCommand($sql)->queryOne();
	
	$tender = new tender($row, $verifications, $directions, $users, $platforms);
	
	if ($action == 'agreement')
	{
		$tender_status = $request->post('tender_status');
		$params = [
			'tender_status' => $tender_status,
		];
		Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
		
		// добавляем комментарий к тендеру
		$params = [
			'user_id' => \Yii::$app->user->id,
			'time' => time(),
			'tender_id' => $id,
		];			
		if ($tender_status == 4)
		{
			// тендер согласован
			$params['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' пометил тендер как проигранный.';
		}
		else if ($tender_status == 5)
		{
			// тендер не согласован	
			$params['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' пометил тендер как выигранный.';
		}
		else if ($tender_status == 7)
		{
			// тендер не согласован	
			$params['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' пометил тендер как выигранный и услуга оказывается.';
		}
		Yii::$app->db->createCommand()->insert('plugins_tenders_comments', $params)->execute();
		
		// создаем уведомление пользователю
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Статус тендера изменен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);		

		// редиректим
		header('Location: /plugins?plug_name=tenders&action=view&id='.$id);
		exit();		
	}
	
	$params['tender'] = $tender;
	
?>
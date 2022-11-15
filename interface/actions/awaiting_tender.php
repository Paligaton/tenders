<?php

	use app\models\Alerts;
	
	$request = Yii::$app->request;

	$tender_id = $request->get('id');
	
	// получаем список всех пользователей
	$users = [];
	$sql = 'SELECT * FROM user_profiles';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$users[$row['user_id']] = $row;
	}	
	
	// меняем статус у тендера
	$params = [
		'tender_status' => 3
	];
	Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$tender_id)->execute();
	
	// добавляем комментарий
	$params = [
		'user_id' => \Yii::$app->user->id,
		'time' => time(),
		'tender_id' => $tender_id,
		'text' => 'Пользователь '.$users[\Yii::$app->user->id]['name'].' перевел тендер в статус "Ожидание участия в тендере"',
	];			
	Yii::$app->db->createCommand()->insert('plugins_tenders_comments', $params)->execute();	
	
	// добавляем алерт
	$Alerts = new Alerts();
	$Alerts->user_id = \Yii::$app->user->id;
	$Alerts->type = "success";
	$Alerts->text = "Статус изменен успешно";
	$Alerts->date_create = time();
	$Alerts->date_view = 0;
	$Alerts->save(false);		
	
	// делаем редирект
	header('Location: /plugins?plug_name=tenders&action=view&id='.$tender_id);
	exit();		
?>
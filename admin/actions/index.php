<?php

	use app\models\Alerts;

	$request = Yii::$app->request;
	$action = $request->post('action');
	
	if ($action == 'save_panel_4')
	{
		$settings = $request->post('settings');
		foreach ($settings as $var => $val)
		{
			$c = Yii::$app->db->createCommand('SELECT COUNT(id) FROM plugins_settings WHERE plugin_eng = "tenders" AND var = "'.$var.'"')->queryScalar();
			if ($c > 0)
			{
				$params = [
					'val' => $val,
				];
				Yii::$app->db->createCommand()->update('plugins_settings', $params, 'plugin_eng="tenders" AND var="'.$var.'"')->execute();
			}
			else
			{
				$params = [
					'plugin_eng' => 'tenders',
					'var' => $var,
					'val' => $val,
				];
				Yii::$app->db->createCommand()->insert('plugins_settings', $params)->execute();
			}
		}
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Настройки оповещения сохранены успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);
	}
	else if ($action == 'save_settings')
	{
		$settings = $request->post('settings');
		foreach ($settings as $var => $val)
		{
			$c = Yii::$app->db->createCommand('SELECT COUNT(id) FROM plugins_settings WHERE plugin_eng = "tenders" AND var = "'.$var.'"')->queryScalar();
			if ($c > 0)
			{
				$params = [
					'val' => $val,
				];
				Yii::$app->db->createCommand()->update('plugins_settings', $params, 'plugin_eng="tenders" AND var="'.$var.'"')->execute();
			}
			else
			{
				$params = [
					'plugin_eng' => 'tenders',
					'var' => $var,
					'val' => $val,
				];
				Yii::$app->db->createCommand()->insert('plugins_settings', $params)->execute();
			}
		}
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Общие настройки сохранены успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);		
	}

	// получаем список платоформ
	$platforms = [];
	$sql = 'SELECT * FROM plugins_tenders_platforms ORDER BY name ASC';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$platforms[$row['id']] = $row;
	}
	
	// получаем список статусов
	$statuses = [];
	$sql = 'SELECT * FROM plugins_tenders_statuses ORDER BY name ASC';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$statuses[$row['id']] = $row;
	}
	
	// получаем список всех пользователей согласовывающих тендеры
	$verifications = [];
	$sql = 'SELECT * FROM plugins_tenders_verifications';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$verifications[$row['id']] = $row;
	}
	
	// получаем список пользователей
	$users = [];
	$sql = 'SELECT * FROM user_profiles';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$users[$row['user_id']] = $row;
	}
	
	// получаем список направлений
	$directions = $tenders->getDirections();
	
	// получаем список настроек плагинов
	$settings = [
		'plugins_tenders_list_alert_emails' => '',
		'plugins_tenders_theme' => '',
		'plugins_tenders_tpl_email' => '',
		'rechange_status_wait_torg_dont_download_doc' => 0,
	];
	$sql = 'SELECT * FROM plugins_settings WHERE plugin_eng = "tenders"';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$settings[$row['var']] = $row['val'];
	}
	
	$params['platforms'] = $platforms;
	$params['statuses'] = $statuses;
	$params['verifications'] = $verifications;
	$params['users'] = $users;
	$params['directions'] = $directions;
	$params['settings'] = $settings;
	
	
?>
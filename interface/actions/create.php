<?php

	use app\models\Alerts;
	
	$action = Yii::$app->request->post('action');
	
	if ($action == "save")
	{
		
		$platform_string = Yii::$app->request->post('platform_string');
		if ($platform_string != '')
		{
			$params = [
				'name' => $platform_string,
			];
			Yii::$app->db->createCommand()->insert('plugins_tenders_platforms', $params)->execute();
			$platform_id = Yii::$app->db->lastInsertID;
		}
		else
		{
			$platform_id = Yii::$app->request->post('platform_id');
		}
		$date_start = Yii::$app->request->post('date_start');
		if ($date_start != '')
		{
			$date_start = strtotime($date_start);
		}
		else
		{
			$date_start = 0;
		}
		
		$deadline_for_filing_an_application_for_participation = Yii::$app->request->post('deadline_for_filing_an_application_for_participation');
		if ($deadline_for_filing_an_application_for_participation != '')
		{
			$deadline_for_filing_an_application_for_participation = strtotime($deadline_for_filing_an_application_for_participation);
		}
		else
		{
			$deadline_for_filing_an_application_for_participation = 0;
		}
		
		$tender_summ = $tenders->prepare_summ(Yii::$app->request->post('tender_summ'));
		$tender_summ_min = $tenders->prepare_summ(Yii::$app->request->post('tender_summ_min'));
		$params = [
			'client_id' => 0,
			'manager_id' => 0,
			'platform_id' => $platform_id,
			'platform_link' => Yii::$app->request->post('platform_link'),
			'eis_link' => Yii::$app->request->post('eis_link'),
			'tender_status' => 2,
			'deleted' => 0,
			'direction_id' => Yii::$app->request->post('direction_id'),
			'author_id' => \Yii::$app->user->id,
			'created_time' => time(),
			'date_start' => $date_start,
			'tender_summ' => $tender_summ,
			'tender_summ_min' => $tender_summ_min,
			'organization_name' => Yii::$app->request->post('organization_name'),
			'deadline_for_filing_an_application_for_participation' => $deadline_for_filing_an_application_for_participation,
		];
		Yii::$app->db->createCommand()->insert('plugins_tenders', $params)->execute();
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Тендер создан успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);

		header('Location: /plugins?plug_name=tenders&action=index');
		exit();
		
	}

	// получаем список тендерных площадок
	$platforms = [];
	$sql = 'SELECT * FROM plugins_tenders_platforms WHERE deleted = 0';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$platforms[$row['id']] = $row;
	}

	// получаем список всех пользователей
	$users = [];
	$sql = 'SELECT * FROM user_profiles ORDER BY name';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$users[$row['user_id']] = $row;
	}
	
	// получаем список всех проверяющих тендеры
	$verifications = [];
	$sql = 'SELECT * FROM plugins_tenders_verifications';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$verifications[] = $row;
	}	
	
	// вытаскиваем все направления
	$directions = $tenders->getDirections();

	// рассортировываем проверяющих по направлениям
	foreach ($verifications as $verification)
	{
		$directions[$verification['direction_id']]['verifications'][$verification['user_id']] = $verification;
	}

	$params['platforms'] = $platforms;
	$params['users'] = $users;
	$params['verifications'] = $verifications;
	$params['directions'] = $directions;
	
?>
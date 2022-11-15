<?php

	use app\models\Alerts;
	$request = Yii::$app->request;
	
	$action = Yii::$app->request->post('action');
	$id = $request->get('id');   
	
	if ($action == "save")
	{
		$platform_string = Yii::$app->request->post('platform_string');
		if ($platform_string != '')
		{
			$sql = 'SELECT * FROM plugins_tenders_platforms WHERE name='.$platform_string.'';
			$rows = Yii::$app->db->createCommand($sql)->queryOne();
			if($rows = ''){
				$params = [
				'name' => $platform_string,
				];
				Yii::$app->db->createCommand()->insert('plugins_tenders_platforms', $params)->execute();
				$platform_id = Yii::$app->db->lastInsertID;
			}
			else
			{
				$platform_id =$rows['id'];
			}
			
			
		}
		else
		{
			$platform_id = Yii::$app->request->post('platform_id');
		}
		
		$params = [
			'platform_id' => $platform_id,
			'platform_link' => Yii::$app->request->post('platform_link'),
			'verification_id' => Yii::$app->request->post('verification_id'),
		];
		Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Тендер изменен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);

		header('Location: /plugins?plug_name=tenders&action=view&id='.$id);
		exit();
	}
	else if ($action == 'save2')
	{
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

		$platform_string = Yii::$app->request->post('platform_string');
		if ($platform_string != '')
		{
			$sql = 'SELECT * FROM plugins_tenders_platforms WHERE name="'.$platform_string.'" AND deleted = 0';
			$rows = Yii::$app->db->createCommand($sql)->queryAll();
			if(empty($rows)){
				$params = [
				'name' => $platform_string,
				];
				Yii::$app->db->createCommand()->insert('plugins_tenders_platforms', $params)->execute();
				$platform_id = Yii::$app->db->lastInsertID;
			}
			else
			{
				foreach($rows as $row)
				{
					$platform_id =$row['id'];
				}
				//$platform_id =$rows[0]['id'];
			}
			
			
		}
		else
		{
			$platform_id = Yii::$app->request->post('platform_id');
		}
		$params = [
			'date_start' => $date_start,
			'tender_summ' => $tender_summ,
			'tender_summ_min' => $tender_summ_min,
			'organization_name' => Yii::$app->request->post('organization_name'),
            'direction_id' => Yii::$app->request->post('direction_id'),
			'deadline_for_filing_an_application_for_participation' => $deadline_for_filing_an_application_for_participation,
			'platform_link' => Yii::$app->request->post('platform_link'),
			'platform_id' => $platform_id,
		];
        $tender_status = Yii::$app->request->post('tender_status');
		$sql = 'SELECT * FROM plugins_tenders WHERE id = '.$id;
		$tender = Yii::$app->db->createCommand($sql)->queryOne();
        if($tender_status != $tender['tender_status'])
        {
			$users = [];
			$sql = 'SELECT * FROM user_profiles';
			$rows = Yii::$app->db->createCommand($sql)->queryAll();
			foreach ($rows as $row)
			{
				$users[$row['user_id']] = $row;
			}
            $params['tender_status']=$tender_status;
			$params2 = [
				'user_id' => \Yii::$app->user->id,
				'time' => time(),
				'tender_id' => $id,
			];			
			if ($tender_status == 2)
			{					// тендер согласован
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Подача заявок".';
			}
			else if ($tender_status == 3)
			{				// тендер не согласован	
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Ожидание результатов".';
			}
			else if ($tender_status == 4)
			{				// тендер не согласован	
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Проигран".';
			}
			else if ($tender_status == 5)
			{				// тендер не согласован	
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Выигран".';
			}
			else if ($tender_status == 6)
			{				// тендер не согласован	
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Завершен".';
			}
			else if ($tender_status == 7)
			{				// тендер не согласован	
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Услуга оказывается".';
			}
			else if ($tender_status == 8)
			{				// тендер не согласован	
				$params2['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' изменил статус тендера на "Отмена закупки по инициативе заказчика".';
			}
			Yii::$app->db->createCommand()->insert('plugins_tenders_comments', $params2)->execute();
        }
		$date_contract = Yii::$app->request->post('date_contract');
		if($date_contract != '')
        {
		$params['date_contract']=strtotime($date_contract);
        }
		Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Тендер изменен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);

		header('Location: /plugins?plug_name=tenders&action=view&id='.$id);
		exit();		
	}
	else if ($action == 'save3')
	{
		$date_start = Yii::$app->request->post('date_start');
		$tender_summ_victor = $tenders->prepare_summ(Yii::$app->request->post('tender_summ_victor'));
		$tender_victor = Yii::$app->request->post('tender_victor');
		
		$params = [
			'tender_victor' => $tender_victor,
			'tender_summ_victor' => Yii::$app->request->post('tender_summ_victor'),
			'client_id' => Yii::$app->request->post('client_id'),
		];
		Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
		
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Тендер изменен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);

		header('Location: /plugins?plug_name=tenders&action=view&id='.$id);
		exit();		
	}	

	// получаем информацию о тендере
	$sql = 'SELECT * FROM plugins_tenders WHERE id = '.$id;
	$tender = Yii::$app->db->createCommand($sql)->queryOne();

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
	
	// выгружаем юридические лица
	$clients = [];
	$sql = "SELECT id, name, inn FROM clients WHERE type = 0 ORDER BY id DESC";
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$clients[$row["id"]]["name"] = $row["name"];
		$clients[$row["id"]]["id"] = $row["id"];
		$clients[$row["id"]]["inn"] = $row["inn"];
	}	

	if (isset($_GET['client_id']))
	{
		$tender['client_id'] = $_GET['client_id'];
	}

	$params['platforms'] = $platforms;
	$params['users'] = $users;
	$params['verifications'] = $verifications;
	$params['directions'] = $directions;
	$params['tender'] = $tender;
	$params['clients'] = $clients;
	
?>
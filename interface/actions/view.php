<?php
	
	use app\plugins_code\tenders\models\tender;
	use app\plugins_code\tenders\models\TypeFiles;
	use app\models\Alerts;
	use app\models\Settings;
	
	$request = Yii::$app->request;
	$id = $request->get('id');
	$action = $request->post("action");

	if ($action == "add_comment")
	{
		$text = $request->post("text");
		if ($text != "")
		{
			$params = [
				'text' => $text,
				'user_id' => \Yii::$app->user->id,
				'time' => time(),
				'deleted' => 0,
				'tender_id' => $id,
			];
			Yii::$app->db->createCommand()->insert('plugins_tenders_comments', $params)->execute();

			$Alerts = new Alerts();
			$Alerts->user_id = \Yii::$app->user->id;
			$Alerts->type = "success";
			$Alerts->text = "Комментарий добавлен успешно";
			$Alerts->date_create = time();
			$Alerts->date_view = 0;
			$Alerts->save(false);  				
		}
	}	

	// получаем список согласовывающих договора
	$verifications = [];
	$sql = 'SELECT * FROM plugins_tenders_verifications';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$verifications[$row['id']] = $row;
	}
	$tst = [];
	//$sql = 'SELECT * FROM plugins_tenders_platforms WHERE name="11"';
	//$rows = Yii::$app->db->createCommand($sql)->queryAll();
	//foreach ($rows as $row)
	//{
	//	$tst[$row['id']] = $row;
	//}

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

	// получаем список документов в тендере
	$files = [];
	$sql = 'SELECT * FROM plugins_tenders_files WHERE tender_id = '.$id;
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$files[$row['id']] = $row;
	}

	// получаем информацию о тендере
	$sql = 'SELECT * FROM plugins_tenders WHERE id = '.$id;
	$row = Yii::$app->db->createCommand($sql)->queryOne();
	
	$tender = new tender($row, $verifications, $directions, $users, $platforms, $files);
	//$tst = $row;
	$comments = [];
	// получаем информацию о всех комментариях
	$sql = 'SELECT * FROM plugins_tenders_comments WHERE deleted = 0 AND tender_id = '.$id.' ORDER BY time DESC';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$comments[] = $row;
	}
	
	// получаем список шаблонов договоров закрепленных за направлением
	$tpls = [];
	if (Yii::$app->id == 'crm')
	{
		$sql = 'SELECT * FROM docstemplate WHERE id IN (SELECT tpl_id FROM directions_tpl WHERE direction_id = '.$tender->direction_id.' AND type_doc_id = 2)';
		$rows = Yii::$app->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$tpls[$row['id']] = $row;
		}
	}
	
	// получаем информацию о направлении
	$sql = 'SELECT * FROM directions WHERE id = '.$tender->direction_id;
	if (Yii::$app->id == 'crm')
	{
		$direction = Yii::$app->db->createCommand($sql)->queryOne();
	}
	else
	{
		$direction = $directions[$tender->direction_id];
	}
	
	// вытаскиваем настройки
	$settings = [
		"mail_enabled" => 0,
		"direction_type_1_name" => "",
		"direction_type_1_description" => "",
		"direction_type_2_name" => "",
		"direction_type_2_description" => "",
		"direction_type_3_name" => "",
		"direction_type_3_description" => "",
		"direction_type_4_name" => "",
		"direction_type_4_description" => "",	
	];	
	$settings_arr = Settings::find()->where(["var" => array_keys($settings)])->all();
	foreach ($settings_arr as $set)
	{
		$settings[$set->var] = $set->val;
	}	
	
	// получаем список настроек плагинов
	$plugins_settings = [
		'rechange_status_wait_torg_dont_download_doc' => 0,
	];
	$sql = 'SELECT * FROM plugins_settings WHERE plugin_eng = "tenders"';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$plugins_settings[$row['var']] = $row['val'];
	}	
	
	$params = [
		'tender' => $tender,
		'users' => $users,
		'comments' => $comments,
		'files' => $files,
		'TypeFiles' => new TypeFiles(),
		'tpls' => $tpls,
		'direction' => $direction,
		'settings' => $settings,
		'plugins_settings' => $plugins_settings,
		'tst' =>$tst
	];
?>
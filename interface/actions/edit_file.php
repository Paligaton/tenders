<?php
	
	use app\models\Alerts;
	use app\plugins_code\tenders\models\tender;
	use app\plugins_code\tenders\models\TypeFiles;
	
	$request = Yii::$app->request;
	
	$id = $request->get('id');
	$action = $request->post("action");	
	
	// получаем информацию о файле
	$sql = 'SELECT * FROM plugins_tenders_files WHERE id = '.$id;
	$file = Yii::$app->db->createCommand($sql)->queryOne();
	
	if ($action == 'update')
	{
		$params = [];
		if ($_FILES["file"]["name"] != "")
		{
			// загружаем файл
			$dir = '/plugins_files/tenders/'.$file['tender_id'].'/';
			if (!is_dir($_SERVER["DOCUMENT_ROOT"].$dir))
			{
				mkdir($_SERVER["DOCUMENT_ROOT"].$dir, 777, true);
				chmod($_SERVER["DOCUMENT_ROOT"].$dir, 777);
			}
			$b = explode(".", $_FILES["file"]["name"]);
			$type = $b[count($b)-1];
			$name = time().'.'.$type;		
			move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER["DOCUMENT_ROOT"].$dir.$name);
			$params = [
				'file' => $dir.$name,
				'name' => $_FILES["file"]["name"],
				'size' => $_FILES["file"]["size"],
				'time' => time(),
				'user_id' => \Yii::$app->user->id,
			];			
		}
		// обновляем запись в СУБД
		$params['type'] = $request->post("type");
		$params['time'] = time();
		$params['user_id'] = \Yii::$app->user->id;

		Yii::$app->db->createCommand()->update('plugins_tenders_files', $params, 'id='.$id)->execute();
		
		// выводим уведомление
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Документ изменен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);				
		
		// редиректим на страницу с тендером
		header('Location: /plugins?plug_name=tenders&action=view&id='.$file['tender_id']);
		exit();				
		
	}
	
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
	$sql = 'SELECT * FROM plugins_tenders WHERE id = '.$file['tender_id'];
	$row = Yii::$app->db->createCommand($sql)->queryOne();
	
	$tender = new tender($row, $verifications, $directions, $users, $platforms);

	$params = [
		'tender' => $tender,
		'TypeFiles' => new TypeFiles(),
		'file' => $file,
	];
	
?>
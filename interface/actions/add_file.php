<?php
	
	use app\models\Alerts;
	use app\plugins_code\tenders\models\tender;
	use app\plugins_code\tenders\models\TypeFiles;
	
	$request = Yii::$app->request;
	
	$id = $request->get('id');
	$action = $request->post("action");	
	
	if ($action == 'create')
	{
		// загружаем файл
		$dir = '/plugins_files/tenders/'.$id.'/';
		if (!is_dir($_SERVER["DOCUMENT_ROOT"].$dir))
		{
			mkdir($_SERVER["DOCUMENT_ROOT"].$dir, 0777, true);
			chmod($_SERVER["DOCUMENT_ROOT"].$dir, 0777);
		}
		$b = explode(".", $_FILES["file"]["name"]);
		$type = $b[count($b)-1];
		$name = time().'.'.$type;		
		move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER["DOCUMENT_ROOT"].$dir.$name);
		
		// добавляем запись в СУБД
		$params = [
			'tender_id' => $id,
			'file' => $dir.$name,
			'name' => $_FILES["file"]["name"],
			'size' => $_FILES["file"]["size"],
			'deleted' => 0,
			'type' => $request->post("type"),
			'time' => time(),
			'user_id' => \Yii::$app->user->id,
		];
		Yii::$app->db->createCommand()->insert('plugins_tenders_files', $params)->execute();
		
		// выводим уведомление
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Документ загружен успешно";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);				
		
		// редиректим на страницу с тендером
		header('Location: /plugins?plug_name=tenders&action=view&id='.$id);
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
	$sql = 'SELECT * FROM plugins_tenders WHERE id = '.$id;
	$row = Yii::$app->db->createCommand($sql)->queryOne();
	
	$tender = new tender($row, $verifications, $directions, $users, $platforms);

	$params = [
		'tender' => $tender,
		'TypeFiles' => new TypeFiles(),
	];
	
?>
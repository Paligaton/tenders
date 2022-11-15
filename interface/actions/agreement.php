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
			'verification_user_id' => \Yii::$app->user->id,
		];
		Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
		
		// добавляем комментарий к тендеру
		$params = [
			'user_id' => \Yii::$app->user->id,
			'time' => time(),
			'tender_id' => $id,
		];			
		if ($tender_status == 2)
		{
			// тендер согласован
			$params['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' согласовал участие в тендере';
			$redirect_url = '/plugins?plug_name=tenders&action=view&id='.$id;
		}
		else if ($tender_status == 1)
		{
			// тендер не согласован	
			$reason = $request->post('reason');
			$params['text'] = 'Пользователь '.$users[\Yii::$app->user->id]['name'].' принял решение не участвовать в тендере по причине: '.$reason;
			$redirect_url = '/plugins?plug_name=tenders&action=index';
		}
		Yii::$app->db->createCommand()->insert('plugins_tenders_comments', $params)->execute();
		
		// создаем уведомление пользователю
		$Alerts = new Alerts();
		$Alerts->user_id = \Yii::$app->user->id;
		$Alerts->type = "success";
		$Alerts->text = "Тендер согласован";
		$Alerts->date_create = time();
		$Alerts->date_view = 0;
		$Alerts->save(false);		
		
		if ($tender_status == 2)
		{
			// Отправляем письмо с оповещением
			// получаем список настроек плагинов
			$settings_tenders = [
				'plugins_tenders_list_alert_emails' => '',
				'plugins_tenders_theme' => '',
				'plugins_tenders_tpl_email' => '',
			];
			$sql = 'SELECT * FROM plugins_settings WHERE plugin_eng = "tenders"';
			$rows = Yii::$app->db->createCommand($sql)->queryAll();
			foreach ($rows as $row)
			{
				$settings_tenders[$row['var']] = $row['val'];
			}
			
			if ($settings_tenders['plugins_tenders_list_alert_emails'] != "")
			{
				$CryptoHelper = new CryptoHelper();
				$settings = [
					"mail_enabled" => 0,
					"mail_class" => "",
					"mail_host" => "",
					"mail_username" => "",
					"mail_password" => "",
					"mail_port" => "",
					"mail_encryption" => "",
					"mail_send_from" => 1,
				];	
				
				$settings_arr = Settings::find()->where(["var" => array_keys($settings)])->all();
				foreach ($settings_arr as $set)
				{
					if (($set->var == "mail_password")and($set->val != ""))
					{
						$set->val = $CryptoHelper->decrypt($set->val);
					}
					$settings[$set->var] = $set->val;
				}

				if ($settings["mail_send_from"] == 1)
				{
					// отправка ведется с одной общей почты
					Yii::$app->mailer->setTransport([
						'class' => $settings["mail_class"],
						'host' => $settings["mail_host"],
						'username' => $settings["mail_username"],
						'password' => $settings["mail_password"],
						'port' => $settings["mail_port"],
						'encryption' => $settings["mail_encryption"],
					]);
				}
				else
				{
					// отправка ведется с персонального почтового ящика
					
					// получаем данные по учетке пользователя
					$user = Yii::$app->db->createCommand('SELECT email, password_email FROM user WHERE id=:id', ['id' => \Yii::$app->user->id])->queryOne();
					
					if ($user["password_email"] == "")
					{
						$Alerts = new Alerts();
						$Alerts->user_id = \Yii::$app->user->id;
						$Alerts->type = "danger";
						$Alerts->text = "Не возможно отправить письмо так как отсутствует пароль от почтовой учетной записи";
						$Alerts->date_create = time();
						$Alerts->date_view = 0;
						$Alerts->save(false);	

						return $this->redirect(['/user/profile/']);
						
					}
					else
					{
						$password_email = $CryptoHelper->decrypt($user["password_email"]);
						
						Yii::$app->mailer->setTransport([
							'class' => $settings["mail_class"],
							'host' => $settings["mail_host"],
							'username' => $user["email"],
							'password' => $password_email,
							'port' => $settings["mail_port"],
							'encryption' => $settings["mail_encryption"],
						]);						
					}
				}			
				
				$setTo = [];
				$b = explode(',', $settings_tenders['plugins_tenders_list_alert_emails']);
				foreach ($b as $key => $val)
				{
					$setTo[] = trim($val);
				}
				
				$from_email = $users[\Yii::$app->user->id]['email'];
				$theme = $settings_tenders['plugins_tenders_theme'];
				$content = $settings_tenders["plugins_tenders_tpl_email"];
				
				$re = '/{+.+}/U';
				preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
				foreach ($matches as $str)
				{
					$str = str_replace(["{", "}"], "", $str[0]);
					$val = explode(":", $str);
					$text = false;	

					if ($val[0] == 'CURRENT_USER')
					{
						$text = $users[\Yii::$app->user->id][$val[1]];
					}
					else if ($val[0] == 'TENDER')
					{
						$text = $tender->row[$val[1]];
					}
					else if ($val[0] == 'PLATFORM')
					{
						$text = $tender->platforms[$tender->platform_id][$val[1]];
					}
					
					if ($text !== false)
					{
						$content = str_replace("{".$str."}", $text, $content);
					}				
				}
				
				$message = Yii::$app->mailer->compose();
				$message->setFrom($from_email);
				$message->setTo($setTo);
				$message->setSubject($theme);
				$message->setHtmlBody($content);			
				
				$errno = null;
				$errstr = null;
				try
				{
					$message->send();
				}
				catch(\Swift_TransportException $e)
				{
					$message = $e->getMessage();
					if (mb_strpos($message, "Failed to authenticate on SMTP server with username") !== false)
					{
						$message = "Не удалось отправить письмо. Неправильный логин или пароль";
					}
					$Alerts = new Alerts();
					$Alerts->user_id = \Yii::$app->user->id;
					$Alerts->type = "danger";
					$Alerts->text = str_replace('"', '\"', $message);
					$Alerts->date_create = time();
					$Alerts->date_view = 0;
					$Alerts->save(false);

				}	
			}
		}
		// редиректим
		header('Location: '.$redirect_url);
		exit();		
	}
	
	$params['tender'] = $tender;
	
?>
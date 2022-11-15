<?php
	if (isset($_GET['tender_id']))
	{
		if ($_GET['tender_id'] > 0)
		{
			// получаем информацию о тендере
			$sql = 'SELECT * FROM plugins_tenders WHERE id = '.(int)$_GET['tender_id'];
			$tender = Yii::$app->db->createCommand($sql)->queryOne();
			
			$client_id = $tender['client_id'];
			
			$managers = [];
			$managers_ids = [];
			$sql = "SELECT manager_id FROM client_managers WHERE client_id = ".$client_id;
			$rows = Yii::$app->db->createCommand($sql)->queryAll();
			foreach ($rows as $row)
			{
				$managers_ids[] = $row["manager_id"];
			}
			
			if (count($managers_ids) > 0)
			{
				$sql = "SELECT id, name FROM managers WHERE id IN (".implode(',', $managers_ids).") ORDER BY id DESC";
				$rows = Yii::$app->db->createCommand($sql)->queryAll();
				foreach ($rows as $row)
				{
					$managers[$row["id"]]["id"] = $row["id"];
					$managers[$row["id"]]["name"] = $row["name"];
				}
			}		
			
			// формируем ссылку для создания нового контрагента
			$url_return = '/direction/contracts/create/?direction_id='.$direction["id"].'{AMP}tender_id='.$_GET['tender_id'].'{AMP}tpl_id='.$docstemplate["id"].'{AMP}type='.$type.'{AMP}client_id={CLIENT_ID}{AMP}manager_id={MANAGER_ID}';
			$url_new_client = '/clients/create/?return='.$url_return;	
			
			// формируем ссылку для создания нового менеджера
			$url_return = '/direction/contracts/create/?direction_id='.$direction["id"].'{AMP}tpl_id='.$docstemplate["id"].'{AMP}client_id={CLIENT_ID}{AMP}manager_id={MANAGER_ID}{AMP}type='.$type.'{AMP}tender_id='.$_GET['tender_id'];
			$url_new_manager = '/managers/create/?client_id='.$client_id.'&return='.$url_return;			
			
			// получаем контент договора
			$sql = 'SELECT * FROM plugins_tenders_files WHERE tender_id = '.$_GET['tender_id'].' AND type = 1 ORDER BY id DESC';
			$doc = Yii::$app->db->createCommand($sql)->queryOne();
			
			$full_file = $_SERVER['DOCUMENT_ROOT'].'/'.$doc['file'];
			$full_file = str_replace('//', '/', $full_file);
			$_FILES['word'] = [
				'type' => mime_content_type($full_file),
				'name' => $doc['name'],
				'tmp_name' => $full_file,
			];
			$res = $this->actionHtmlToWord('array');
			
			if ($res['status'] == 'ok')
			{
				$content = $res['word'];
			}
			else
			{
				$Alerts = new Alerts();
				$Alerts->user_id = \Yii::$app->user->id;
				$Alerts->type = "danger";
				$Alerts->text = "Не удалось загрузить документ с шаблоном договора. Неподдерживаемый тип.";
				$Alerts->date_create = time();
				$Alerts->date_view = 0;
				$Alerts->save(false);					
			}
		}
	}
?>
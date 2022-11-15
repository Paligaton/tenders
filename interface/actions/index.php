<?php

	use app\plugins_code\tenders\models\tender;
	use app\plugins_code\tenders\models\Statuses;
	use app\plugins_code\sales_department\assets\DateRangePickerAsset;

	$request = Yii::$app->request;

	$params['filter'] = [
		'status_id' => '',
		'platform_id' => '',
		'direction_id' => '',
		'client' => '',
		'deadline_range' => '',
		'date_start_range' => '',
	];
	
	if (isset($_GET['filter']['status_id']))
	{
		$params['filter']['status_id'] = $_GET['filter']['status_id'];
	}
	if (isset($_GET['filter']['platform_id']))
	{
		$params['filter']['platform_id'] = $_GET['filter']['platform_id'];
	}
	if (isset($_GET['filter']['direction_id']))
	{
		$params['filter']['direction_id'] = $_GET['filter']['direction_id'];
	}	
	if (isset($_GET['filter']['client']))
	{
		$params['filter']['client'] = $_GET['filter']['client'];
	}
	if (isset($_GET['filter']['deadline_range']))
	{
		$params['filter']['deadline_range'] = $_GET['filter']['deadline_range'];
		
	}	
	if (isset($_GET['filter']['date_start_range']))
	{
		$params['filter']['date_start_range'] = $_GET['filter']['date_start_range'];
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

	// получаем список доступных тендеров к просмотру
	$page = (int)$request->get('page');
	if ($page == 0)
	{
		$page = 1;
	}
	$count_in_page = 20;
	$limit_start = (($page*$count_in_page)-$count_in_page);
	$limit = $limit_start.', '.$count_in_page;
	
	$where = [];
	$where[] = '(deleted = 0)';
	if (!Yii::$app->loadAccess->access(['plugins', 'tenders', 'view_all'], 'bool'))
	{
		$where[] = '(author_id = '.\Yii::$app->user->id.')OR(verification_id IN (SELECT id FROM plugins_tenders_verifications WHERE user_id = '.\Yii::$app->user->id.'))';
	}
	if ($params['filter']['status_id'] != '')
	{
		if ($params['filter']['status_id'] == 9)
		{
			$where[] = '(tender_status > 4 AND  tender_status != 8)';
		}else
		{
			$where[] = '(tender_status = '.(int)$params['filter']['status_id'].')';
		}
	}
	if ($params['filter']['platform_id'] != '')
	{
		$where[] = '(platform_id = '.(int)$params['filter']['platform_id'].')';
	}
	if ($params['filter']['direction_id'] != '')
	{
		$where[] = '(direction_id = '.(int)$params['filter']['direction_id'].')';
	}	
	if ($params['filter']['client'] != '')
	{
		$client_name = $params['filter']['client'];
		if (Yii::$app->id == 'crm')
		{
			// Система документооборота
			$sql = 'SELECT id FROM clients WHERE ((name LIKE "%'.$client_name.'%") OR (inn LIKE "%'.$client_name.'%") OR (name_small LIKE "%'.$client_name.'%"))';
			$where[] = '((client_id IN ('.$sql.')) OR (organization_name LIKE "%'.$client_name.'%"))';
		}
		else
		{
			// CRM "Развитие"
			$client_ids = [-1];
			$ApiController = new app\modules\client\controllers\ApiController(0, 'admin');
			$rows = $ApiController->actionSearchClients($params['filter']['client'], 'array');
			foreach ($rows as $row)
			{
				$client_ids[] = $row->rows['id'];
			}
			
			$where[] = '((client_id IN ('.implode(',', $client_ids).')) OR (LOWER(organization_name) LIKE "%'.mb_strtolower(addslashes($client_name)).'%"))';
		}
	}
	$btw='';
	if ($params['filter']['deadline_range'] != '')
	{
		$d = explode('-',$_GET['filter']['deadline_range']);
		$s = explode('.',$d[0]);
        $e = explode('.',$d[1]);

        $start = strtotime($s[2].$s[1].$s[0]);
        $end = strtotime($e[2].'-'.$e[1].'-'.$e[0].' 23:59:59.000000' );
		$btw = 'AND deadline_for_filing_an_application_for_participation BETWEEN '.$start.' AND '.$end.' ';
		
	}
	if ($params['filter']['date_start_range'] != '')
	{
		$d = explode('-',$_GET['filter']['date_start_range']);
        $s = explode('.',$d[0]);
        $e = explode('.',$d[1]);
		$start = strtotime($s[2].$s[1].$s[0]);
        $end = strtotime($e[2].'-'.$e[1].'-'.$e[0].' 23:59:59.000000' );
		$btw = $btw.' AND date_start BETWEEN '.$start.' AND '.$end.' ';
	}
	
	$tenders = [];
	$client_ids = [];
	$sql = 'SELECT * FROM plugins_tenders WHERE ('.implode(' AND ', $where).')'.$btw.' ORDER BY id DESC LIMIT '.$limit;
	
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	foreach ($rows as $row)
	{
		$tenders[$row['id']] = new tender($row, $verifications, $directions, $users, $platforms);
		if ($row['client_id'] > 0)
		{
			$client_ids[$row['client_id']] = $row['client_id'];
		}
	}
	
	// узнаем сколько всего тендеров
	$sql = 'SELECT COUNT(id) FROM plugins_tenders WHERE ('.implode(' AND ', $where).')'.$btw.'';
	$count_tenders = Yii::$app->db->createCommand($sql)->queryScalar();
	$count_page = ceil($count_tenders/$count_in_page);
	
	// получаем список клиентов
	$clients = [];
	if (count($client_ids) > 0)
	{
		$sql = 'SELECT * FROM clients WHERE id IN ('.implode(',', $client_ids).')';
		$rows = Yii::$app->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$clients[$row['id']] = $row;
		}
	}
	
	// Последние комментарии
	$comments = [];
	if (count($tenders) > 0)
	{
		$sql = 'SELECT * FROM plugins_tenders_comments WHERE tender_id IN ('.implode(',', array_keys($tenders)).') ORDER BY time ASC';
		$rows = Yii::$app->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$comments[$row['tender_id']] = $row;
		}
	}
	
	$params['tenders'] = $tenders;
	$params['platforms'] = $platforms;
	$params['clients'] = $clients;
	$params['users'] = $users;
	$params['verifications'] = $verifications;
	$params['Statuses'] = new Statuses();
	$params['comments'] = $comments;
	$params['directions'] = $directions;
	
	$params['count_page'] = $count_page;
	$params['count_in_page'] = $count_in_page;
	$params['page'] = $page;
	
	
?>
<?php
	if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'access'], 'bool'))
	{
		// считаем сколько у пользователя заявок находится на согласовании
		$count_status_sogl_current_user = 0;
		// получаем список всех направлений
		include_once $_SERVER['DOCUMENT_ROOT'].'/../plugins_code/tenders/class.php';
		$tenders = new tenders();
		$filterstatus_id = $request->get('filter');
        $plug_name = $request->get('plug_name');
		$directions = $tenders->getDirections();
		$direction_ids = [];
		foreach ($directions as $direction)
		{
			if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $direction['id'], 'agreement'], 'bool'))
			{
				$direction_ids[] = $direction['id'];
			}
		}
		if (count($direction_ids) > 0)
		{
			$sql = 'SELECT id FROM plugins_tenders WHERE tender_status = 0 AND direction_id IN ('.implode(',', $direction_ids).')';
			$rows = Yii::$app->db->createCommand($sql)->queryAll();
			foreach ($rows as $row)
			{
				$count_status_sogl_current_user++;
			}
		}
		
		if ($count_status_sogl_current_user == 0)
		{
			$tpl = '<a href="{url}">{label}</a>';
		}
		else
		{
			$tpl = '<a style="position:relative;" href="{url}">{label} <span style="background: red;position: absolute;right: 15px;top: 10px;" class="badge">'.$count_status_sogl_current_user.'</span></a>';
		}
		
		$items['plugins_tenders'] = [
			'label' => 'Тендеры',
			'url' => false,
            'options' => ['class' => 'sub-menu',],
            'visible' => Yii::$app->loadAccess->access(['plugins', 'tenders', 'edit_status'], 'bool'),
			'template' => '<a href="{url}" data-ma-action="submenu-toggle">{label}</a>',
            'active' => ($plug_name == 'tenders'   ? true : false),
            'items' => [
                [
                    'label' => 'Общее',
                    'url' => ['/plugins?plug_name=tenders&action=index'],
					'active' => ($plug_name == 'tenders' AND ($filterstatus_id == false OR $filterstatus_id == '3' OR $filterstatus_id == '4' OR $filterstatus_id == '8')  ? true : false),

                ],
                [
                    'label' => 'Ожидание результатов',
                    'url' => ['/plugins?plug_name=tenders&action=index&filter[platform_id]=&filter[client]=&filter[direction_id]=&filter[status_id]=3'],
					'active' => ($plug_name == 'tenders' AND $filterstatus_id['status_id'] == '3' ? true : false),

                ],
                [
                    'label' => 'Проигранные',
                    'url' => ['/plugins?plug_name=tenders&action=index&filter[platform_id]=&filter[client]=&filter[direction_id]=&filter[status_id]=4'],
					'active' => ($plug_name == 'tenders' AND $filterstatus_id['status_id'] == '4' ? true : false),

                ],
                [
                    'label' => 'Выигранные',
                    'url' => ['/plugins?plug_name=tenders&action=index&filter[platform_id]=&filter[client]=&filter[direction_id]=&filter[status_id]=9'],
					'active' => ($plug_name == 'tenders' AND $filterstatus_id['status_id'] == '9' ? true : false),

                ],
            ]
		];
	}
?>
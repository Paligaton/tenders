<?php
	use app\plugins_code\tenders\models\tender;
	use app\plugins_code\tenders\models\Statuses;
	use app\models\Alerts;
	$request = Yii::$app->request;
	//$id = $request->get('id');
	//$params = ['deleted' => 1];
	//Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
$users = [];
$date_begin = $request->post('date_begin');
$date_end = $request->post('date_end');
$part_sql='';
if ($date_begin!='' AND $date_end!='')
{
    $date_begin= new DateTime($date_begin);
    $date_end= new DateTime($date_end.' 23:59:59');

    $date_begin= date_timestamp_get($date_begin);
    $date_end= date_timestamp_get($date_end);

    $part_sql='AND date_start BETWEEN '.$date_begin.' AND '.$date_end;
}
elseif ($date_begin!='')
{
    $date_begin= new DateTime($date_begin);
    $date_begin= date_timestamp_get($date_begin);
    $part_sql='AND date_start>'.$date_begin.'';
}
elseif ($date_end!='')
{
    $date_end= new DateTime($date_end.' 23:59:59');
    $date_end= date_timestamp_get($date_end);
    $part_sql='AND date_start<'.$date_end.'';
}
$sql = 'SELECT * FROM user_profiles';
$rows = Yii::$app->db->createCommand($sql)->queryAll();
foreach ($rows as $row)
{
    $users[$row['user_id']] = $row;
}
	$sql = 'SELECT * FROM plugins_tenders where deleted = 0 '.$part_sql.'';
	$rows = Yii::$app->db->createCommand($sql)->queryAll();
	$sql = 'SELECT * FROM plugins_tenders_platforms';
	$pls = Yii::$app->db->createCommand($sql)->queryAll();
	$sql = 'SELECT * FROM user_profiles';
	$column[1] = array('№ п.п','№ ТЗ', 'Торговая площадка','Ссылка на тендер',
	'Наименование организации', 'Услуга', 'Подать заявку','Тендер / аукцион','Дата заключения договора', 'Начальная', 'Наша минимальная', 'Статус', 'Автор тендера', $date_begin, $date_end);
	$document = new PHPExcel();
	$sheet = $document->setActiveSheetIndex(0);
	$startLine = 1;
	$array = array(
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
	'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
);
	$column[2] = array(
	4.29, 7.86, 19.14, 26.57, 21.14, 39.14, 17.43, 21.57, 16.29, 16.57, 16.86,16.86, 31.71, 31.71, 31.71
	
);
$sheet->getStyle("E")->getAlignment()->setWrapText(true);
$sheet->getStyle("D")->getAlignment()->setWrapText(true);
$sheet->getStyle("C")->getAlignment()->setWrapText(true);
$directions = $tenders->getDirections();
$statuses = new Statuses;
	foreach($column[1] as $key=>$value){
		$sheet->setCellValue($array[$key].$startLine, $value);
		$sheet->getColumnDimension($array[$key])->setWidth($column[2][$key]);
	}
	foreach($rows as $key=>$row){
		$startLine++;
		$sheet->setCellValue($array[0].$startLine, $startLine-1);
		$sheet->setCellValue($array[1].$startLine, $row['id']);
		$sheet->setCellValue($array[2].$startLine, $pls[$row['platform_id']-1]['name']);
		$sheet->setCellValue($array[3].$startLine, $row['platform_link']);
		$sheet->setCellValue($array[4].$startLine, $row['organization_name']);
		$sheet->setCellValue($array[5].$startLine, $directions[$row['direction_id']]['name']); 
		$sheet->setCellValue($array[6].$startLine, ($row['deadline_for_filing_an_application_for_participation'] == 0 ? "Не задано" : date('d.m.Y h:i', $row['deadline_for_filing_an_application_for_participation']))); 
		$sheet->setCellValue($array[7].$startLine, ($row['date_start'] == 0 ? "Не задано" : date('d.m.Y H:i', $row['date_start'])));
		$sheet->setCellValue($array[8].$startLine, ($row['date_contract'] == 0 ? "Не задано" : date('d.m.Y H:i', $row['date_contract'])));
		$sheet->setCellValue($array[9].$startLine, $row['tender_summ']); 
		$sheet->setCellValue($array[10].$startLine, $row['tender_summ_min']); 
		$sheet->setCellValue($array[11].$startLine, $statuses->statuses[$row['tender_status']]['name']); 
		$sheet->setCellValue($array[12].$startLine, $users[$row['author_id']]['name']);
	}
	header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="myfile.xls"');

$objWriter = PHPExcel_IOFactory::createWriter($document, 'Excel5');
$objWriter->save('php://output'); 
 

	/*$Alerts = new Alerts();
	$Alerts->user_id = \Yii::$app->user->id;
	$Alerts->type = "success";
	$Alerts->text = "Тендер удален успешно";
	$Alerts->date_create = time();
	$Alerts->date_view = 0;
	$Alerts->save(false);*/

	exit();	
	
?>
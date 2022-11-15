<table class="table table-bordered">
	<tr>
		<th>
			#
		</th>
		<th>
			Описание
		</th>
	</tr>
	<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'access'], 'Доступ к тендерам')?>
	<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'view_all'], 'Просмотр  всех тендеров')?>
	<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'create_tenders'], 'Создание тендеров')?>
	<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'delete'], 'Удаление тендеров')?>
	<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'edit'], 'Редактирование тендеров')?>
    <?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'edit_status'], 'Редактирование статусов тендеров')?>
    <?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'excel'], 'Экспорт тендеров Excel')?>
</table>

<!--<?php
	include $_SERVER['DOCUMENT_ROOT'].'/../plugins_code/tenders/class.php';
	$tenders = new tenders();
	$directions = $tenders->getDirections();
	foreach ($directions as $direction)
	{
		?>
			<h4><?=$direction['name']?></h4>
			<table class="table table-bordered">
				<tr>
					<th>
						#
					</th>
					<th>
						Описание
					</th>
				</tr>
				<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'directions', $direction['id'], 'agreement'], 'Согласование')?>
				<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'directions', $direction['id'], 'download_files'], 'Подготовка документов и участие в тендере')?>
				<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'directions', $direction['id'], 'create_contracts'], 'Создание договоров на основании тендера')?>
				<?=$controller->getTr($access, ['access_arr', 'plugins', 'tenders', 'directions', $direction['id'], 'end_tender'], 'Завершение оказания услуг по тендеру')?>
				
			</table>
		<?php
	}
?>	-->
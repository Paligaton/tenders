<?php
	$this->title = 'Тендер № '.$params['tender']->id;
	$this->params['breadcrumbs'][] = ['label' => 'Тендерная система', 'url' => '/plugins?plug_name=tenders&action=index'];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
	<div class="card-header ch-alt">
		<div class="row">
			<div class="col-md-8">
				<h2><?=$this->title?></h2>
			</div>
			<div class="col-md-4">
				<?php
					
					// смотрм нужно ли выводить кнопки
					$view_btn = false;
					if ((Yii::$app->loadAccess->access(['plugins', 'tenders', 'edit'], 'bool')) or ($params['tender']->author_id == \Yii::$app->user->id))
					{
						$view_btn = true;
					}
					if (($params['tender']->tender_status == 5)and($params['tender']->client_id > 0))
					{
						if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $params['tender']->direction_id, 'create_contracts'], 'bool'))
						{
							$view_btn = true;
						}
					}
				?>
				<?php if ($view_btn) { ?>
					<div class="btn-group" style="float:right;margin:0px 10px 0px 0px">
						<button data-toggle="dropdown" class="btn btn-sm btn-primary dropdown-toggle">
							<i class="fa fa-gears"></i> <i class="fa fa-caret-down"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-left-2">
							<?php if ((Yii::$app->loadAccess->access(['plugins', 'tenders', 'edit'], 'bool')) or ($params['tender']->author_id == \Yii::$app->user->id)) { ?>
								<li>
									<a href="/plugins?plug_name=tenders&action=edit&id=<?=$params['tender']->id?>">
										Редактировать
									</a>
								</li>	
							<?php } ?>
							<?php if (($params['tender']->tender_status == 5)and($params['tender']->client_id > 0)) { ?>
								<?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $params['tender']->direction_id, 'create_contracts'], 'bool')) { ?>
									<li class="dropdown-submenu-2">
										<a class="dropdown-item" href="#">
											Создать договор
										</a>
										<ul>
											<?php foreach ($params['tpls'] as $t) { ?>
												<li>
													<a class="dropdown-item create-contract-uc-legal" tpl="<?=$t["id"]?>" href="#">
														<?=$t["name"]?>
													</a>
												</li>
											<?php } ?>
										</ul>
									</li>									
								<?php } ?>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="card-body card-padding">
		<ul class="nav nav-tabs tabs_index-page">
			<li class="active">
				<a class="" data-toggle="tab" href="#panel_1">
					Общие сведения по тендеру
				</a>
			</li>	
			<li class="">
				<a class="" data-toggle="tab" href="#panel_2">
					Комментарии
				</a>
			</li>
			<!--<li class="">
				<a class="" data-toggle="tab" href="#panel_3">
					Документы
				</a>
			</li>	
			<?php
			//echo "<a href=/plugins?plug_name=tenders&action=delete&id=".$params['tender']->id." onclick=\"return confirm('Вы уверены что желаете удалить тендер?')\"><i class='zmdi zmdi-delete'></i></a>";
			//if (($params['tender']->tender_status == 5)or($params['tender']->tender_status == 6)or($params['tender']->tender_status == 7)){
			?>
			<li class="">
				<a class="" data-toggle="tab" href="#panel_4">
					Результаты
				</a>
			</li>
			<?php //}?>-->
		</ul>
		<div class="tab-content">
			<div id="panel_1" class="tab-pane fade active in">
				<table class="table table-bordered">
					<thead>
						<th>Параметр</th>
						<th>Значение</th>
					</thead>
					<tbody>
						<tr>
							<td>Направление</td>
							<td><?=$params['direction']['name']?></td>
						</tr>					
						<tr>
							<td>Площадка</td>
							<td><?=$params['tender']->getPlatformName()?></td>
						</tr>
						<tr>
							<td>Ссылка</td>
							<td>
								<a href="<?=$params['tender']->platform_link?>" target="_blank">
									<?=$params['tender']->platform_link?>
								</a>
							</td>
						</tr>	
						<tr>
							<td>Дата окончания подачи заявок на участие</td>
							<td>
								<?php if ($params['tender']->deadline_for_filing_an_application_for_participation == 0) { ?>
									Не задано
								<?php } else { ?>
									<?=date('d.m.Y H:i', $params['tender']->deadline_for_filing_an_application_for_participation)?>
								<?php } ?>
							</td>
						</tr>						
						<tr>
							<td>Дата проведения тендера</td>
							<td>
								<?php if ($params['tender']->date_start == 0) { ?>
									Не задано
								<?php } else { ?>
									<?=date('d.m.Y H:i', $params['tender']->date_start)?>
								<?php } ?>
							</td>
						</tr>
						<?php if ($params['tender']->tender_status> 4) {?>
						<tr>
							<td>Дата заключения договра</td>
							<td>
								<?php if ($params['tender']->date_contract == 0) { ?>
									Не задано
								<?php } else { ?>
									<?=date('d.m.Y ', $params['tender']->date_contract)?>
								<?php } ?>
							</td>
						</tr>						
						<?php }?>
						<tr>
							<td>Начальная сумма тендера</td>
							<td>
								<?php if ($params['tender']->tender_summ == '') { ?>
									Не задано
								<?php } else { ?>
									<?=Yii::$app->formatter->asCurrency($params['tender']->tender_summ)?>
								<?php } ?>							
							</td>
						</tr>	
						<tr>
							<td>Сумма участия / минимальная сумма участия</td>
							<td>
								<?php if ($params['tender']->tender_summ_min == '') { ?>
									Не задано
								<?php } else { ?>
									<?=Yii::$app->formatter->asCurrency($params['tender']->tender_summ_min)?>
								<?php } ?>								
							</td>
						</tr>	
						<tr>
							<td>Организация</td>
							<td><?=$params['tender']->getOrganization()?></td>
						</tr>	
						<tr>
							<td>Статус</td>
							<td>
								<?=$params['tender']->getStatus()['str']?>
							</td>
						</tr>	
						<tr>
							<td>Автор тендера</td>
							<td>
								<?=$params['tender']->getAuthor()?>
							</td>
						</tr>
						<!--<tr>
							<td>Согласовал</td>
							<td>
								<?php if ($params['tender']->verification_user_id > 0) { ?>
									<?=$params['users'][$params['tender']->verification_user_id]['name']?>
								<?php } ?>
							</td>
						</tr>-->					
					</tbody>
				</table>
				<?php 
					//$sql = 'SELECT * FROM user_profiles';
	//$rows = Yii::$app->db->createCommand($sql)->queryAll();
	//print_r ($rows);
                if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'edit_status'], 'bool')){
				if (in_array($params['tender']->tender_status, [2])) { ?>
				
					<!--<?php if ($params['plugins_settings']['rechange_status_wait_torg_dont_download_doc'] == 1) { ?>
						<?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $params['tender']->direction_id, 'download_files'], 'bool')) { ?>
							<a href="/plugins?plug_name=tenders&action=awaiting_tender&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
								Ожидание тендера
							</a>
						<?php } ?>					
					<?php } else { ?>
						<?php if ($params['tender']->view_btn_awaiting_tender) { ?>
							<?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $params['tender']->direction_id, 'download_files'], 'bool')) { ?>
								<a href="/plugins?plug_name=tenders&action=awaiting_tender&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
									Ожидание тендера
								</a>
							<?php } ?>
						<?php } else { ?>
							<p style="color:red">Загрузите образец контракта и техническое задания для перехода к статусу ожидания тендера</p>
						<?php } ?>					
					<?php } ?>-->
					
						<a href="/plugins?plug_name=tenders&action=awaiting_tender&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
							Документы поданы
						</a>						
					
				<?php } ?>
				<?php if (in_array($params['tender']->tender_status, [3])) { ?>
					 
						<a href="/plugins?plug_name=tenders&action=result_tender&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
							Внести результаты
						</a>						
					
				<?php } ?>
				<?php if (in_array($params['tender']->tender_status, [5])) { ?>
					
						<a href="/plugins?plug_name=tenders&action=work_on_tender&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
							Услуги по тендеру оказываются
						</a>						
					
				<?php } ?>					
				<?php if (in_array($params['tender']->tender_status, [7])) { ?>
					
						<a href="/plugins?plug_name=tenders&action=end_tender&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
							Услуги по тендеру оказаны
						</a>						
					
				<?php } ?>
				<?php if ($params['tender']->tender_status == 0) { ?>
					
						<a href="/plugins?plug_name=tenders&action=agreement&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
							Согласовать
						</a>						
					
				<?php }} ?>
			</div>
			<div id="panel_2" class="tab-pane fade">
				<div class="row">
					<div class="col-md-8">
						<?php if (count($params['comments']) == 0) { ?>
							<p>Комментарии не найдены
						<?php } else { ?>
							<div class="comments-list" style="height:400px;widht:100%;overflow:auto;">
								<div id="view_comments">
									<?php foreach ($params['comments'] as $comment) { ?>
										<div class="c-item">
											<span class="c-date"><?=date("d.m.Y H:i", $comment["time"])?></span>
											<span class="c-author"> (<?=$params['users'][$comment["user_id"]]['name']?>)</span>
											<div class="c-comment" style="word-wrap: break-word;">
												<?=$comment["text"]?>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<form method="POST" action="">
							<input type="hidden" name="action" value="add_comment">
							<div class="form-group">
								<label>Введите Ваш комментарий</label>
								<textarea name="text" class="form-control" rows="6"></textarea>
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-primary btn-sm" value="Добавить">
							</div>							
						</form>
					</div>					
				</div>					
			</div>
			<div id="panel_3" class="tab-pane fade">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Тип документа</th>
							<th>Дата загрузки</th>
							<th>Пользователь</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($params['files'] as $file) { ?>
							<?php
								$typeFile = $params['TypeFiles']->names[$file['type']];
							?>
							<tr>
								<td>
									<?php
										$btn = [];
										if (in_array($params['tender']->tender_status, [2,3,4,5]))
										{
											if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $params['tender']->direction_id, 'download_files'], 'bool'))
											{
												$btn[] = [
													"type" => "btn", 
													"url" => '/plugins?plug_name=tenders&action=edit_file&id='.$file['id'], 
													"icon" => "fa fa-pencil", 
													"name" => "Редактировать"
												];
											}
										}
										if (count($btn) > 0)
										{
											echo app\widgets\Dropdown\Dropdown::widget([
												"buttons" => $btn,
												"icon" => "fa-gears",
												"position" => "left",
											]); 
										}
									?>
									<a href="<?=$file['file']?>" target="_blank">
										<?=$typeFile?>
									</a>
								</td>
								<td><?=date('d.m.Y H:i', $file['time'])?></td>
								<td><?=$params['users'][$file['user_id']]['name']?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php if (in_array($params['tender']->tender_status, [2,3,4,5])) { ?>
					<?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'directions', $params['tender']->direction_id, 'download_files'], 'bool')) { ?>
						<a href="/plugins?plug_name=tenders&action=add_file&id=<?=$params['tender']->id?>" class="btn btn-sm btn-success">
							Загрузить файл
						</a>
					<?php } ?>
				<?php } ?>
			</div>
			<div id="panel_4" class="tab-pane fade">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<!--<td><b>Победитель</b></td>
							<td><?=$params['tender']->tender_victor?></td>
						</tr>-->
						<tr>
							<td><b>Сумма</b></td>
							<td><?=$params['tender']->tender_summ_victor?></td>
							
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
window.onload = function() {
	$(".create-contract-uc-legal").click(function()
	{
		var tpl = $(this).attr("tpl");
		
		var url = "/direction/contracts/create/?direction_id=<?=$params["direction"]["id"]?>&tender_id=<?=$params["tender"]->id?>&tpl_id=";
		
		var html = '';
		html = html + '<table class="table table-bordered">';
			html = html + '<thead>';
				html = html + '<tr>';
					html = html + '<th>Вид</th>';
					html = html + '<th>Описание</th>';
					html = html + '<th></th>';
				html = html + '</tr>';
			html = html + '</thead>';
			html = html + '<tbody>';
				html = html + '<tr>';
					html = html + '<td><?=$params["settings"]["direction_type_1_name"]?></td>';
					html = html + '<td class="small"><?=$params["settings"]["direction_type_1_description"]?></td>';
					html = html + '<td>';
						html = html + '<a href="'+url+tpl+'&type=1" class="btn btn-success btn-block">Создать</a>';
					html = html + '</td>';					
				html = html + '</tr>';
				html = html + '<tr>';
					html = html + '<td><?=$params["settings"]["direction_type_2_name"]?></td>';
					html = html + '<td class="small"><?=$params["settings"]["direction_type_2_description"]?></td>';
					html = html + '<td>';
						html = html + '<a href="'+url+tpl+'&type=2" class="btn btn-success btn-block">Создать</a>';
					html = html + '</td>';					
				html = html + '</tr>';	
				
				html = html + '<tr>';
					html = html + '<td><?=$params["settings"]["direction_type_3_name"]?></td>';
					html = html + '<td class="small"><?=$params["settings"]["direction_type_3_description"]?></td>';
					html = html + '<td>';
						html = html + '<a href="'+url+tpl+'&type=3" class="btn btn-success btn-block">Создать</a>';
					html = html + '</td>';					
				html = html + '</tr>';	
				
				html = html + '<tr>';
					html = html + '<td><?=$params["settings"]["direction_type_4_name"]?></td>';
					html = html + '<td class="small"><?=$params["settings"]["direction_type_4_description"]?></td>';
					html = html + '<td>';
						html = html + '<a href="'+url+tpl+'&type=4" class="btn btn-success btn-block">Создать</a>';
					html = html + '</td>';					
				html = html + '</tr>';					
			html = html + '</tbody>';
		html = html + '</table>';
		
		swal({
			"title": "Выберите вид договора",
			"type": "info",
			"html": true,
			"text": html,
			"confirmButtonText": "Закрыть",
		});
	});
}
</script>
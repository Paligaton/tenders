<?php

	app\plugins_code\tenders\assets\TinyMCEAsset::register($this);

	$this->title = 'Тендерная система';
	$this->params['breadcrumbs'][] = ['label' => 'Настройки'];
	$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => ['/admin/plugins/']];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
	<div class="card-header ch-alt">
		<h2><?=$this->title?></h2>
	</div>
	<div class="card-body card-padding">
		<ul class="nav nav-tabs tabs_index-page">
			<li class="active">
				<a class="" data-toggle="tab" href="#panel_5">
					Общие настройки
				</a>
			</li>		
			<li>
				<a class="" data-toggle="tab" href="#panel_1">
					Тендерные площадки
				</a>
			</li>
			<li>
				<a class="" data-toggle="tab" href="#panel_2">
					Статусы
				</a>
			</li>	
			<li>
				<a class="" data-toggle="tab" href="#panel_3">
					Проверяющие тендеры
				</a>
			</li>
			<li>
				<a class="" data-toggle="tab" href="#panel_4">
					Оповещение при согласовании тендера
				</a>
			</li>				
		</ul>
		<div class="tab-content">
			<div id="panel_5" class="tab-pane fade active in">
				<form method="POST" action="">
					<input type="hidden" name="action" value="save_settings">
					<div class="form-group">
						<label>
							<?php
								$checked = false;
								if (isset($params['settings']['rechange_status_wait_torg_dont_download_doc']))
								{
									if ($params['settings']['rechange_status_wait_torg_dont_download_doc'] == 1)
									{
										$checked = true;
									}
								}
							?>
							<input type="hidden" name="settings[rechange_status_wait_torg_dont_download_doc]" value="0">
							<input type="checkbox" name="settings[rechange_status_wait_torg_dont_download_doc]" value="1" <?=($checked ? 'checked' : '')?>>
							 Разрешить переводить тендеры в статус ожидания торгов без загрузки документов
						</label>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-sm btn-primary" value="Сохранить">
					</div>
				</form>
			</div>
			<div id="panel_1" class="tab-pane fade">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Площадка</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($params['platforms'] as $platform) { ?>
							<tr>
								<td><?=$platform['id']?></td>
								<td>
									<?php
										$btn = [];
												
										$btn[] = [
											"type" => "btn", 
											"url" => "/admin/plugins/view/?plug_name=tenders&action=platform_edit&id=".$platform['id'], 
											"icon" => "", 
											"name" => "Редактировать"
										];								
										
										if ($platform['deleted'] == 0)
										{
											$btn[] = [
												"type" => "btn", 
												"url" => "/admin/plugins/view/?plug_name=tenders&action=platform_off&id=".$platform["id"], 
												"icon" => "fa fa-trash", 
												"name" => "Выключить",
												"class" => "btnDelete",
											];	
										}
										else
										{
											$btn[] = [
												"type" => "btn", 
												"url" => "/admin/plugins/view/?plug_name=tenders&action=platform_on&id=".$platform["id"], 
												"icon" => "", 
												"name" => "Включить",
												"class" => "",
											];										
										}

										echo app\widgets\Dropdown\Dropdown::widget([
											"buttons" => $btn,
											"icon" => "fa-gears",
											"position" => "left",
										]); 										
										
									?>
									<?php if ($platform['deleted'] == 1) { ?>
										<s>
									<?php } ?>
									<?=$platform['name']?>
									<?php if ($platform['deleted'] == 1) { ?>
										</s>
									<?php } ?>									
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<a href="/admin/plugins/view/?plug_name=tenders&action=platform_add" class="btn btn-success btn-sm">Добавить</a>
			</div>
			<div id="panel_2" class="tab-pane fade">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Статус</th>
							<th>Пример</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($params['statuses'] as $status) { ?>
							<tr>
								<td><?=$status['id']?></td>
								<td>
									<?php
										$btn = [];
												
										$btn[] = [
											"type" => "btn", 
											"url" => "/admin/plugins/view/?plug_name=tenders&action=status_edit&id=".$status['id'], 
											"icon" => "", 
											"name" => "Редактировать"
										];								
										
										if ($status['deleted'] == 0)
										{
											$btn[] = [
												"type" => "btn", 
												"url" => "/admin/plugins/view/?plug_name=tenders&action=status_off&id=".$status["id"], 
												"icon" => "fa fa-trash", 
												"name" => "Выключить",
												"class" => "btnDelete",
											];	
										}
										else
										{
											$btn[] = [
												"type" => "btn", 
												"url" => "/admin/plugins/view/?plug_name=tenders&action=status_on&id=".$status["id"], 
												"icon" => "", 
												"name" => "Включить",
												"class" => "",
											];										
										}

										echo app\widgets\Dropdown\Dropdown::widget([
											"buttons" => $btn,
											"icon" => "fa-gears",
											"position" => "left",
										]); 										
										
									?>
									<?php if ($status['deleted'] == 1) { ?>
										<s>
									<?php } ?>
									<?=$status['name']?>
									<?php if ($status['deleted'] == 1) { ?>
										</s>
									<?php } ?>									
								</td>
								<td style="background:<?=$status['color']?>;color:<?=$status['color_text']?>">
									Пример
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<a href="/admin/plugins/view/?plug_name=tenders&action=status_add" class="btn btn-success btn-sm">Добавить</a>			
			</div>	
			<div id="panel_3" class="tab-pane fade">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Пользователь</th>
							<th>Направление</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($params['verifications'] as $verification) { ?>
							<tr>
								<td><?=$verification['id']?></td>
								<td>
									<?php
										$btn = [];
												
										$btn[] = [
											"type" => "btn", 
											"url" => "/admin/plugins/view/?plug_name=tenders&action=verification_edit&id=".$verification['id'], 
											"icon" => "", 
											"name" => "Редактировать"
										];								
										$btn[] = [
												"type" => "btn", 
												"url" => "/admin/plugins/view/?plug_name=tenders&action=verification_delete&id=".$verification["id"], 
												"icon" => "fa fa-trash", 
												"name" => "Удалить",
												"class" => "btnDelete",
										];	

										echo app\widgets\Dropdown\Dropdown::widget([
											"buttons" => $btn,
											"icon" => "fa-gears",
											"position" => "left",
										]); 										
									?>	
									<?=$params['users'][$verification['user_id']]['name']?>								
								</td>
								<td>
									<?=$params['directions'][$verification['direction_id']]['name']?>	
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<a href="/admin/plugins/view/?plug_name=tenders&action=verification_add" class="btn btn-success btn-sm">Добавить</a>			
			</div>				
			<div id="panel_4" class="tab-pane fade">
				<form method="POST">
					<input type="hidden" name="action" value="save_panel_4">				
					<div class="form-group">
						<label>
							Введите перечень адресов на которые отправить письмо об успешном согласовании тендеров
							<br><small>Через запятую</small>
						</label>
						<input class="form-control" type="text" name="settings[plugins_tenders_list_alert_emails]" value="<?=$params['settings']['plugins_tenders_list_alert_emails']?>">
					</div>
					<div class="form-group">
						<label>Введите тему письма</label>
						<input class="form-control" type="text" name="settings[plugins_tenders_theme]" value="<?=$params['settings']['plugins_tenders_theme']?>">
					</div>
					<div class="form-group">
						<label>Шаблон письма</label>
						<textarea class="form-control tinymce" name="settings[plugins_tenders_tpl_email]"><?=$params['settings']['plugins_tenders_tpl_email']?></textarea>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-sm btn-primary" value="Сохранить">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
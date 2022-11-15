<?php

	app\plugins_code\tenders\assets\ChosenAsset::register($this);
	app\plugins_code\tenders\assets\DateTimePickerAsset::register($this);

	$this->title = 'Новый тендер';
	$this->params['breadcrumbs'][] = ['label' => 'Тендерная система', 'url' => '/plugins?plug_name=tenders&action=index'];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
	<div class="card-header ch-alt">
		<h2><?=$this->title?></h2>
	</div>
	<div class="card-body card-padding">
		<form method="POST">
			<input type="hidden" name="action" value="save">
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>Тендерная площадка</label>
						<select name="platform_id" class="form-control chosen">
							<?php foreach ($params['platforms'] as $platform) { ?>
								<option value="<?=$platform['id']?>"><?=$platform['name']?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-6">
						<label>Или введите название новой платформы</label>
						<input type="text" name="platform_string" class="form-control">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label>Ссылка на тендер</label>
				<input type="text" name="platform_link" class="form-control" >
			</div>
			<!--<div class="form-group">
				<label>Ссылка на ЕИС</label>
				<input type="text" name="eis_link" class="form-control">
			</div>-->			 
			<div class="form-group">
				<label>Выберите направление</label>
				<select name="direction_id" class="form-control chosen">
					<?php foreach ($params['directions'] as $direction) { ?>
						<option value="<?=$direction['id']?>">
							<?=$direction['name']?>
							<!--<?php if (count($direction['verifications']) > 0) { ?>
								<?php
									$b = [];
									foreach ($direction['verifications'] as $user_id => $val)
									{
										$b[] = $params['users'][$user_id]['name'];
									}
									
								?>
								(<?=implode(',', $b)?>)
							<?php } ?>-->
						</option>
					<?php } ?>
				</select>
			</div>
			<div>
			<?php echo $this->render('../../../../plugins_code/tenders/interface/views/forms/tender_create', [
				'params' => $params,
			]); ?>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success btn-sm" value="Создать">
			</div>
		</form>
	</div>
</div>
<?php

	app\plugins_code\tenders\assets\AgreementAsset::register($this);

	$this->title = 'Согласование тендера';
	
	$this->params['breadcrumbs'][] = ['label' => 'Тендерная система', 'url' => '/plugins?plug_name=tenders&action=index'];
	$this->params['breadcrumbs'][] = ['label' => 'Тендер № '.$params['tender']->id, 'url' => '/plugins?plug_name=tenders&action=view&id='.$params['tender']->id];
	$this->params['breadcrumbs'][] = $this->title;
	
?>
<div class="card">
	<div class="card-header ch-alt">
		<h2><?=$this->title?></h2>
	</div>
	<div class="card-body card-padding">
		<form method="POST">
			<input type="hidden" name="action" value="agreement">
			<div class="form-group">
				<label>Выберите резолюцию</label>
				<select name="tender_status" class="form-control">
					<option value="2">Принять участие</option>
					<option value="1">Отказаться в тендере</option>
				</select>
			</div>
			<div class="form-group div_reason">
				<label>Укажите причину отказа</label>
				<textarea name="reason" class="form-control"></textarea>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-sm btn-primary" value="Сохранить">
			</div>
		</form>
	</div>
</div>
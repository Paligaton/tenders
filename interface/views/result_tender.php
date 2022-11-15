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
				<label>Выберите результат</label>
				<select name="tender_status" class="form-control">
					<option value="4">Проигран</option>
					<option value="5">Выигран</option>
					<option value="7">Выигран, по тендеру оказывается услуга</option>
				</select>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-sm btn-primary" value="Сохранить">
			</div>
		</form>
	</div>
</div>
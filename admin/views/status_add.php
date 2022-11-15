<?php

	app\plugins_code\tenders\assets\MinicolorsAsset::register($this);

	$this->title = 'Новый статус';
	$this->params['breadcrumbs'][] = ['label' => 'Настройки'];
	$this->params['breadcrumbs'][] = ['label' => 'Плагины', 'url' => ['/admin/plugins/']];
	$this->params['breadcrumbs'][] = ['label' => 'Тендерная система', 'url' => ['/admin/plugins/view/?plug_name=tenders&action=index']];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
	<div class="card-header ch-alt">
		<h2>
			<?=$this->title?>
		</h2>
	</div>
	<div class="card-body card-padding">
		<form method="POST">
			<input type="hidden" name="action" value="create">
			<div class="form-group">
				<label>Название статуса</label>
				<input type="text" name="name" class="form-control">
			</div>
			<div class="form-group form-group-minicolors">
				<label>Цвет заливки</label>
				<input type="text" name="color" class="minicolors minicolors-input">
			</div>	
			<div class="form-group form-group-minicolors">
				<label>Цвет текста</label>
				<input type="text" name="color_text" class="minicolors minicolors-input">
			</div>				
			<div class="form-group">
				<input type="submit" class="btn btn-success btn-sm" value="Сохранить">
			</div>
		</form>
	</div>
</div>
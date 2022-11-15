<?php
	$this->title = 'Новая площадка';
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
				<label>Название площадки</label>
				<input type="text" name="name" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success btn-sm" value="Сохранить">
			</div>
		</form>
	</div>
</div>
<?php
	$this->title = 'Редактирование проверяющего тендеры';
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
			<input type="hidden" name="action" value="update">
			<div class="form-group">
				<label>Пользователь ответственный за проверку тендера</label>
				<select name="user_id" class="form-control">
					<?php foreach ($params['users'] as $user) { ?>
						<option value="<?=$user['user_id']?>" <?=($user['user_id'] == $params['verification']['user_id'] ? 'selected' : '')?>><?=$user['name']?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Направление</label>
				<select name="direction_id" class="form-control">
					<?php foreach ($params['directions'] as $direction) { ?>
						<option value="<?=$direction['id']?>" <?=($direction['id'] == $params['verification']['direction_id'] ? 'selected' : '')?>><?=$direction['name']?></option>
					<?php } ?>
				</select>
			</div>			
			<div class="form-group">
				<input type="submit" class="btn btn-success btn-sm" value="Сохранить">
			</div>
		</form>
	</div>
</div>
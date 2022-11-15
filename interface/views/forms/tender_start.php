<form method="POST">
	<input type="hidden" name="action" value="save">
	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<label>Тендерная площадка</label>
				<select name="platform_id" class="form-control chosen">
					<?php foreach ($params['platforms'] as $platform) { ?>
						<option value="<?=$platform['id']?>" <?=($params['tender']['platform_id'] == $platform['id'] ? 'selected' : '')?>><?=$platform['name']?></option>
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
		<input type="text" name="platform_link" class="form-control" value="<?=$params['tender']['platform_link']?>">
	</div>
	<div class="form-group">
		<label>Ответственный за принятие решения об участии</label>
		<select name="verification_id" class="form-control chosen">
			<?php foreach ($params['verifications'] as $verification) { ?>
				<option value="<?=$verification['id']?>" <?=($params['tender']['verification_id'] == $verification['id'] ? 'selected' : '')?>><?=$params['users'][$verification['user_id']]['name']?> (<?=$params['directions'][$verification['direction_id']]['name']?>)</option>
			<?php } ?>
		</select>
	</div>		
	<div class="form-group">
		<input type="submit" class="btn btn-success btn-sm" value="Изменить">
	</div>
</form>